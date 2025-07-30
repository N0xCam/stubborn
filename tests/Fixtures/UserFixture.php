<?php

namespace App\Tests\Fixtures;

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
        $user->setEmail(self::TEST_USER_EMAIL);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'password')
        );

        $manager->persist($user);
        $manager->flush();
    }
}
