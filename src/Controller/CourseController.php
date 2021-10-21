<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/course', name: 'course_index')]
    public function index(): Response
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render(
            'course/index.html.twig',[
                'courses' => $courses
            ]
            );
    }

    #[Route('/course/detail/{id}', name: 'course_detail')]
    public function courseDetail($id){
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Course not found !');
            return $this->redirectToRoute('course_index');
        } else { //$course != null
            return $this->render(
                'course/detail.html.twig',
                [
                    'course' => $course
                ]
            );
        }
    }


    #[Route('/course/delete/{id}', name: 'course_delete')] 
    public function deleteCourse($id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('Error', 'Course not found !');
        } else { //$student != null
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('Success', 'Course has been deleted !');
        }
        return $this->redirectToRoute('course_index');
    }

}
