<?php

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

require_once __DIR__ . '/vendor/autoload.php';

// Charge les variables d'environnement
(new Dotenv())->bootEnv(__DIR__ . '/.env');

// Boot du kernel Symfony
$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
/** @var UserPasswordHasherInterface $hasher */
$hasher = $kernel->getContainer()->get(UserPasswordHasherInterface::class);

$user = new User();
$user->setEmail('admin@stubborn.com');
$user->setStubborn('admin');
$user->setDeliveryAddress('42 rue des root');
$user->setIsVerified(true);
$user->setRoles(['ROLE_ADMIN']);
$user->setPassword($hasher->hashPassword($user, 'admin123'));

$entityManager->persist($user);
$entityManager->flush();

echo "Admin créé avec succès !\n";
