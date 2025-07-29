<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route(name: 'app_admin', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        // Gestion d'ajout de produit
        if ($request->isMethod('POST') && $request->request->has('product')) {
            $data = $request->request->get('product');
            $files = $request->files->get('product');

            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setStockXS($data['stock_xs']);
            $product->setStockS($data['stock_s']);
            $product->setStockM($data['stock_m']);
            $product->setStockL($data['stock_l']);
            $product->setStockXL($data['stock_xl']);
            $product->setSlug($slugger->slug($data['name'])->lower());
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            if ($files && $files['image'] instanceof UploadedFile) {
                $filename = uniqid() . '.' . $files['image']->guessExtension();
                $files['image']->move($this->getParameter('images_directory'), $filename);
                $product->setImage($filename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');
            return $this->redirectToRoute('app_admin');
        }

        $products = $productRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['POST'])]
    public function edit(
        int $id,
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException();
        }

        $product->setName($request->get('name'));
        $product->setPrice($request->get('price'));
        $product->setStockXS($request->get('stock_xs'));
        $product->setStockS($request->get('stock_s'));
        $product->setStockM($request->get('stock_m'));
        $product->setStockL($request->get('stock_l'));
        $product->setStockXL($request->get('stock_xl'));
        $product->setSlug($slugger->slug($product->getName())->lower());
        $product->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        $this->addFlash('success', 'Produit modifié');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/{id}/delete', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('danger', 'Produit supprimé');
        }

        return $this->redirectToRoute('app_admin');
    }
}
