<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER");
 */

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


    /**
     * @IsGranted("ROLE_ADMIN")
     */
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

     /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/course/add', name: 'course_add')]
    public function addCourse (Request $request) {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        
        

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $date1 = $form['endDate']->getData();
            $date11 = $date1->format('Y-m-d');
            $date2 = $form['startDate']->getData();
            $date22 = $date2->format('Y-m-d');
            $duration = floor((strtotime($date11) - strtotime($date22))/(60*60*24));
            if($duration<0){
                $this->addFlash('Error', "Start Date must before End Date");
                return $this->redirectToRoute("course_index");
            }
            $manager->persist($course);
            
            $manager->flush();

            $this->addFlash('Success', "Course has been added successfully !");
            return $this->redirectToRoute("course_index");
        }

        return $this->render (
            "course/add.html.twig", 
            [
                'form' => $form->createView()
            ]
        );
    }

     /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/course/edit/{id}', name: 'course_edit')]
    public function editCourse(Request $request, $id) {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();

            $this->addFlash('Success', "Course has been updated successfully !");
            return $this->redirectToRoute("course_index");
        }

        return $this->render (
            "course/edit.html.twig", 
            [
                'form' => $form->createView()
            ]
        );
    }
}
