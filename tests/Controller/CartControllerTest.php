<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use App\Repository\UserRepository;

class CartControllerTest extends WebTestCase
{
    public function testCartPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        // Connexion d'un utilisateur
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]); 
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/cart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'commande');
    }

    public function testRemoveItemFromCart(): void
    {
        $client = static::createClient();

        // Connexion d'un utilisateur
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);
        $client->loginUser($testUser);

        // Création d'une session mock
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        $cart = [
            ['product_id' => 1, 'size' => 'M'],
        ];
        $session->set('cart', $cart);
        $client->getContainer()->set('session', $session);

        // Appel de la route
        $client->request('GET', '/cart/remove/1/M');

        // Vérifie qu'on est redirigé correctement
        $this->assertResponseRedirects('/cart');
    }
}
