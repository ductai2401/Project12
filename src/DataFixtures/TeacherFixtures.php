<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <=10; $i++){
            $teacher = new Teacher();
            $teacher->setName("Teacher $i");
            $teacher->setAge($i*30);
            $teacher->setPhone("1232345");
            $teacher->setAddress("Greenwich");
            //$teacher->setAvatar("teacher.png");
            $manager->persist($teacher);
        }

        $manager->flush();
    }
}
