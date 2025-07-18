<?php

namespace App\Controller;

use App\Form\ProductFilterType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
   #[Route('/boutique', name: 'app_shop')]
public function index(Request $request, ProductRepository $productRepository): Response
{
    $form = $this->createForm(ProductFilterType::class, null, [
        'method' => 'GET',
    ]);
    $form->handleRequest($request);

    $minPrice = $form->get('minPrice')->getData();
    $maxPrice = $form->get('maxPrice')->getData();

    if ($minPrice !== null || $maxPrice !== null) {
        $products = $productRepository->findByPriceRange($minPrice, $maxPrice);
    } else {
        $products = $productRepository->findAll();
    }

    return $this->render('shop/index.html.twig', [
        'products' => $products,
        'form' => $form->createView(),
    ]);
}

}
