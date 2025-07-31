<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const TEST_USER_EMAIL = 'test@example.com';

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setStubborn('testuser') // <- CHAMP REQUIS
             ->setEmail(self::TEST_USER_EMAIL)
             ->setRoles(['ROLE_USER'])
             ->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
             )
             ->setDeliveryAddress('12 rue des Tests')
             ->setIsVerified(true); // au cas où c’est bloquant

        $manager->persist($user);
        $manager->flush();
    }
}
