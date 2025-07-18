<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
   #[Route('/cart', name: 'app_cart')]
public function index(Request $request, ProductRepository $productRepository): Response
{
    $cart = $request->getSession()->get('cart', []);

    $cartItems = [];

    foreach ($cart as $item) {
        $product = $productRepository->find($item['product_id']);
        if ($product) {
            $cartItems[] = [
                'product' => $product,
                'size' => $item['size'],
                'quantity' => 1, // tu peux gérer les quantités plus tard si besoin
            ];
        }
    }

    return $this->render('cart/index.html.twig', [
        'cart' => $cartItems, // <<< C’est ça qui manquait
    ]);
}

}
