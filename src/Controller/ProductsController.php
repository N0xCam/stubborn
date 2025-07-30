<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('', name: 'app_products', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $filter = $request->query->get('price_range');

        $products = match ($filter) {
            '10_29' => $productRepository->findByPriceRange(10, 29),
            '30_35' => $productRepository->findByPriceRange(30, 35),
            '35_50' => $productRepository->findByPriceRange(35, 50),
            default => $productRepository->findAll(),
        };

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'selected' => $filter
        ]);
    }

   #[Route('/{id}', name: 'app_product_show', methods: ['GET', 'POST'])]
public function show(Product $product, Request $request): Response
{
    $form = $this->createForm(AddToCartType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $size = $form->get('size')->getData();
        $cart = $request->getSession()->get('cart', []);
        $cart[] = [
            'product_id' => $product->getId(),
            'size' => $size
        ];
        $request->getSession()->set('cart', $cart);

        $this->addFlash('success', "Ajouté au panier !");
        return $this->redirectToRoute('app_products');
    }

    return $this->render('products/show.html.twig', [
        'product' => $product,
        'form' => $form->createView(), 
    ]);
}
}
