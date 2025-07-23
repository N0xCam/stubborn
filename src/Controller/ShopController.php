<?php

namespace App\Controller;

use App\Form\ProductFilterType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/boutique', name: 'app_shop')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductFilterType::class);
        $form->handleRequest($request);

        $products = $productRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $range = $form->get('priceRange')->getData();

            switch ($range) {
                case 'less_than_30':
                    $products = $productRepository->findByPriceRange(null, 29.99);
                    break;
                case 'between_30_60':
                    $products = $productRepository->findByPriceRange(30, 60);
                    break;
                case 'more_than_60':
                    $products = $productRepository->findByPriceRange(60.01, null);
                    break;
            }
        }

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
}
