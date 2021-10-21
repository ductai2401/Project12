<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
            $category = new Category();
            $category->setName("Computing");
            $category->setDescription("KKK");
            $manager->persist($category);

            $category = new Category();
            $category->setName("Design");
            $category->setDescription("AAA");
            $manager->persist($category);

            $category = new Category();
            $category->setName("Business");
            $category->setDescription("SSS");
            $manager->persist($category);
        


        $manager->flush();
    }
}
