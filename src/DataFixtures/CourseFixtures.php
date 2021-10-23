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
            $course->setStartDate(\DateTime::createFromFormat("Y-m-d","2021-09-01"));
            $course->setEndDate(\DateTime::createFromFormat("Y-m-d","2021-12-31"));
            $manager->persist($course);
        }

       

        $manager->flush();
    }
}
