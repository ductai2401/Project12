<?php

namespace App\DataFixtures;

use DateTime;
use \App\Entity\Course;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <=10; $i++){
            $course = new Course();
            $course->setName("Course $i");
            $date1 = new DateTime('2021-9-01');
            $dateStart = $date1->format('Y-m-d');
            $date2 = new DateTime('2021-12-31');
            $dateEnd = $date2->format('Y-m-d');

            
            $course->setStartDate($date1);
            $course->setEndDate($date2);
            
            $duration = floor((strtotime($dateEnd) - strtotime($dateStart))/(60*60*24));
            $course->setDuration($duration);
            $manager->persist($course);
        }

        $manager->flush();
    }
}
