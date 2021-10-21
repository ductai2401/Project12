<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <=10; $i++){
            $student = new Student();
            $student->setName("Student $i");
            $student->setAge($i*20);
            $student->setPhone("12345");
            $student->setAddress("Greenwich");
            $student->setAvatar("student.jpg");
            $manager->persist($student);
        }

        $manager->flush();
    }
}
