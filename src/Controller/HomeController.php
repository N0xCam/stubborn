<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
   #[Route('/', name: 'app_home')]
public function index(ProductRepository $productRepository): Response
{
    $products = $productRepository->findBy(
        ['isFeatured' => true],
        ['createdAt' => 'DESC'],
        3
    );

    return $this->render('home/index.html.twig', [
        'products' => $products,
    ]);
}
}

