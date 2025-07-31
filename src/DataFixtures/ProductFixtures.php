<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const TEST_PRODUCT_ID = 1;

    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('T-shirt test')
            ->setPrice(1999)
            ->setImage('tshirt.jpg')
            ->setSlug('t-shirt-test')
            ->setStockXS(10)
            ->setStockS(10)
            ->setStockM(10)
            ->setStockL(10)
            ->setStockXL(10)
            ->setIsFeatured(false);

        $manager->persist($product);
        $manager->flush();
    }
}
