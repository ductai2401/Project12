<?php

namespace App\DataFixtures;

use \App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <=10; $i++){
            $course = new Course();
            $course->setName("Course $i");
            $manager->persist($course);
        }

       

        $manager->flush();
    }
}
