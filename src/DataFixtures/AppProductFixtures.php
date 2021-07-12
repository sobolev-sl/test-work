<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setCount(10);
        $product->setSku("SKU-0001");
        $product->setPrice(1);
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setCount(9);
        $product->setSku("SKU-0002");
        $product->setPrice(2);
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setCount(8);
        $product->setSku("SKU-0003");
        $product->setPrice(3);
        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setCount(1);
        $product->setSku("SKU-0004");
        $product->setPrice(4);
        $manager->persist($product);
        $manager->flush();
    }
}
