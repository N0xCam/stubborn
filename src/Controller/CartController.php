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
                'quantity' => 1,
            ];
        }
    }

    return $this->render('cart/index.html.twig', [
        'cart' => $cartItems,
    ]);
}


  #[Route('/cart/remove/{id}/{size}', name: 'app_cart_remove')]
public function remove(int $id, string $size, Request $request): Response
{
    $cart = $request->getSession()->get('cart', []);

    foreach ($cart as $index => $item) {
        if ($item['product_id'] === $id && $item['size'] === $size) {
            unset($cart[$index]);
            // Re-indexer le tableau pour éviter les trous
            $cart = array_values($cart);
            break;
        }
    }

    $request->getSession()->set('cart', $cart);

    $this->addFlash('success', 'Produit retiré du panier.');

    return $this->redirectToRoute('app_cart');
}


}
