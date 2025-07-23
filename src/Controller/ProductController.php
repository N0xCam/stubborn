<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\AddToCartType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\ProductFilterType; 

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
{
    $form = $this->createForm(ProductFilterType::class);
    $form->handleRequest($request);

    $products = $productRepository->findAll(); // Par défaut

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $range = $data['priceRange'] ?? null;

        if ($range) {
            switch ($range) {
                case 'less_than_30':
                    $products = $productRepository->findByPriceRange(0, 29.99);
                    break;
                case '30_to_60':
                    $products = $productRepository->findByPriceRange(30, 60);
                    break;
                case 'more_than_60':
                    $products = $productRepository->findByPriceRange(60.01, 1000);
                    break;
            }
        }
    }

    return $this->render('product/index.html.twig', [
        'products' => $products,
        'form' => $form->createView(),
    ]);
}

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }

            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());
            $product->setSlug($slugger->slug($product->getName())->lower());

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/view', name: 'app_product_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Product $product): Response
    {
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $size = $form->get('size')->getData();

            // Ajout au panier en session
            $cart = $request->getSession()->get('cart', []);
            $cart[] = [
                'product_id' => $product->getId(),
                'size' => $size,
            ];
            $request->getSession()->set('cart', $cart);

            $this->addFlash('success', "Produit ajouté au panier en taille $size !");
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l’upload de l’image.');
                }

                $product->setImage($newFilename);
            }

            $product->setUpdatedAt(new \DateTimeImmutable());
            $product->setSlug($slugger->slug($product->getName())->lower());

            $entityManager->flush();

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index');
    }
}
