<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\DataFixtures\UserFixture; // <- CORRIGÉ ICI
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckoutControllerTest extends WebTestCase
{
    public function testSuccessPageClearsCartAndDisplaysConfirmation(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        // Récupère le user chargé par les fixtures
        $user = $container->get('doctrine')->getRepository(User::class)->findOneBy([
            'email' => UserFixture::TEST_USER_EMAIL,
        ]);

        $this->assertNotNull($user, 'L’utilisateur de test est introuvable');

        $client->loginUser($user);

        // Simule un panier rempli dans la session
        $session = $client->getContainer()->get('session.factory')->createSession();
        $session->set('cart', [
            ['product_id' => 1, 'size' => 'M']
        ]);
        $session->save();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        $client->request('GET', '/success');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Merci pour votre commande');

        // Vérifie que le panier est vidé
        $sessionAfter = $client->getContainer()->get('session')->all();
        $this->assertArrayNotHasKey('cart', $sessionAfter);
    }
}
