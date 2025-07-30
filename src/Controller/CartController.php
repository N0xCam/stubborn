<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartItems = $this->getCartItems($cart, $productRepository);

        return $this->render('cart/index.html.twig', [
            'cart' => $cartItems,
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
        ]);
    }

    #[Route('/cart/remove/{id}/{size}', name: 'app_cart_remove')]
    public function remove(int $id, string $size, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $cart = $this->removeItemFromCart($cart, $id, $size);
        $session->set('cart', $cart);

        $this->addFlash('success', 'Produit retiré du panier.');

        return $this->redirectToRoute('app_cart');
    }

    private function getCartItems(array $cart, ProductRepository $productRepository): array
    {
        $items = [];
        foreach ($cart as $item) {
            $product = $productRepository->find($item['product_id']);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'size' => $item['size'],
                    'quantity' => 1,
                ];
            }
        }
        return $items;
    }

    private function removeItemFromCart(array $cart, int $productId, string $size): array
    {
        foreach ($cart as $index => $item) {
            if ($item['product_id'] === $productId && $item['size'] === $size) {
                unset($cart[$index]);
                return array_values($cart); // réindexation
            }
        }
        return $cart;
    }
}
