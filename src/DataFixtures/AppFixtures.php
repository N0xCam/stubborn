<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Crée un utilisateur de test
        $user = new User();
        $user->setStubborn('admin')
            ->setEmail('admin@example.com')
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setDeliveryAddress('42 Rue du Test, Paris')
            ->setIsVerified(true);

        $manager->persist($user);

        // Crée un produit de test
        $product = new Product();
        $product->setName('Sweat test')
            ->setPrice(49.99)
            ->setImage('test.jpg')
            ->setSlug('sweat-test')
            ->setStockXS(10)
            ->setStockS(12)
            ->setStockM(15)
            ->setStockL(10)
            ->setStockXL(8)
            ->setIsFeatured(true);

        $manager->persist($product);

        $manager->flush();
    }
}
