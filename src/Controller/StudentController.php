<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
* @IsGranted("ROLE_USER")
*/
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

    /**
     * @IsGranted("ROLE_ADMIN")
     */
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
            $this->addFlash('Success', 'Student has been deleted !');
        }
        return $this->redirectToRoute('student_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/student/add', name: 'student_add')]
    public function addStudent(Request $request){
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code x??? l?? ???nh upload
            //B1: l???y ???nh t??? file upload
            $image = $student->getAvatar();
            //B2: t???o t??n m???i cho ???nh => t??n file ???nh l?? duy nh???t
            $imgName = uniqid(); //unique ID
            //B3: l???y ra ph???n ??u??i (extension) c???a ???nh
            $imgExtension = $image->guessExtension();
            //B4: g???p t??n m???i + ??u??i t???o th??nh t??n file ???nh ho??n thi???n
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuy???n file ???nh upload v??o th?? m???c ch??? ?????nh
            try {
                $image->move(
                    $this->getParameter('student_avatar'),
                    $imageName
                    //L??u ??: c???n khai b??o tham s??? ???????ng d???n c???a th?? m???c
                    //cho "author_avatar" ??? file config/services.yaml
                );
            } catch (FileException $e) {
                // throwException($e);
            }
            //B6: l??u t??n v??o database
            $student->setAvatar($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();

            $this->addFlash('Success', "Add student successfully !");
            return $this->redirectToRoute("student_index");
        }

        return $this->render(
            "student/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    } 

    /**
    * @IsGranted("ROLE_ADMIN")
    */
    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function editStudent(Request $request, $id){
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'Student not found !');
            return $this->redirectToRoute('student_index');
        } else { //$author != null
            $form = $this->createForm(StudentType::class, $student);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['avatar']->getData();
                if ($file != null) {
                    $image = $student->getAvatar();
                    $imgName = uniqid();
                    $imgExtension = $image->guessExtension();
                    $imageName = $imgName . "." . $imgExtension;
                    try {
                        $image->move(
                            $this->getParameter('student_avatar'),
                            $imageName
                            //L??u ??: c???n khai b??o tham s??? ???????ng d???n c???a th?? m???c
                            //cho "author_avatar" ??? file config/services.yaml
                        );
                    } catch (FileException $e) {
                        // throwException($e);
                    }
                    $student->setAvatar($imageName);
                }

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($student);
                    $manager->flush();

                    $this->addFlash('Success', "Update student successfully !");
                    return $this->redirectToRoute("student_index");
            }

            return $this->render(
                "student/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }

}
}