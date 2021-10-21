<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function index(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render(
            'student/index.html.twig',[
                'students' => $students
            ]
            );
    }

    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail($id){
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'Student not found !');
            return $this->redirectToRoute('student_index');
        } else { //$student != null
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student
                ]
            );
        }
    }

    #[Route('/student/delete/{id}', name: 'student_delete')] 
    public function deleteStudent($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'Student not found !');
        } else { //$student != null
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been deleted !');
        }
        return $this->redirectToRoute('student_index');
    }

}
