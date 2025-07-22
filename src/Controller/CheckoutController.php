<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session', methods: ['POST'])]
    public function checkout(Request $request, ProductRepository $productRepository): JsonResponse
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $cart = $request->getSession()->get('cart', []);

        $lineItems = [];

        foreach ($cart as $item) {
            $product = $productRepository->find($item['product_id']);
            if (!$product) {
                continue;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getName() . ' - Taille ' . $item['size'],
                    ],
                    'unit_amount' => $product->getPrice() * 100, // en centimes
                ],
                'quantity' => 1,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_home', [], 0) . '?success=1',
            'cancel_url' => $this->generateUrl('app_cart', [], 0) . '?canceled=1',
        ]);

        return $this->json(['id' => $session->id]);
    }

    #[Route('/success', name: 'app_success')]
public function success(Request $request): Response
{
    // On vide le panier
    $request->getSession()->remove('cart');

    return $this->render('checkout/success.html.twig');
}

}
