<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
* @IsGranted("ROLE_USER")
*/
class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_index')]
    public function index(): Response
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();
        return $this->render(
            'teacher/index.html.twig',[
                'teachers' => $teachers
            ]
            );
    }
    
    #[Route('/teacher/detail/{id}', name: 'teacher_detail')]
    public function teacherDetail($id){
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('Error', 'Teacher not found !');
            return $this->redirectToRoute('teacher_index');
        } else {
            return $this->render(
                'teacher/detail.html.twig',
                [
                    'teacher' => $teacher
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/delete/{id}', name: 'teacher_delete')] 
    public function deleteTeacher($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('Error', 'Teacher not found !');
        } else { //$teacher != null
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($teacher);
            $manager->flush();
            $this->addFlash('Success', 'Teacher has been deleted !');
        }
        return $this->redirectToRoute('teacher_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/edit/{id}', name: 'teacher_edit')]
    public function editTeacher(Request $request, $id){
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('Error', 'Teacher not found');
            return $this->redirectToRoute('teacher_index');
        } else { //book != null
            $form = $this->createForm(TeacherType::class, $teacher);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //code x??? l?? ???nh upload
                //B1: l???y d??? li???u ???nh t??? form 
                $file = $form['avatar']->getData();
                //B2: check xem file ???nh upload c?? null kh??ng
                if ($file != null) {
                    //B3: l???y ???nh t??? file upload
                    $image = $teacher->getAvatar();
                    //B4: t???o t??n m???i cho ???nh => t??n file ???nh l?? duy nh???t
                    $imgName = uniqid(); //unique ID
                    //B5: l???y ra ph???n ??u??i (extension) c???a ???nh
                    $imgExtension = $image->guessExtension();
                    //B6: g???p t??n m???i + ??u??i t???o th??nh t??n file ???nh ho??n thi???n
                    $imageName = $imgName . "." . $imgExtension;
                    //B7: di chuy???n file ???nh upload v??o th?? m???c ch??? ?????nh
                    try {
                        $image->move(
                            $this->getParameter('teacher_avatar'),
                            $imageName
                            //L??u ??: c???n khai b??o tham s??? ???????ng d???n c???a th?? m???c
                            //cho "book_cover" ??? file config/services.yaml
                        );
                    } catch (FileException $e) {
                        throwException($e);
                    }
                    //B8: l??u t??n v??o database
                    $teacher->setAvatar($imageName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($teacher);
                $manager->flush();

                $this->addFlash('Success', "Edit teacher successfully !");
                return $this->redirectToRoute("teacher_index");
            }

            return $this->render(
                "teacher/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }

    
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/add', name: 'teacher_add')]
    public function addTeacher(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //code x??? l?? ???nh upload
            //B1: l???y ???nh t??? file upload
            $image = $teacher->getAvatar();
            //B2: t???o t??n m???i cho ???nh => t??n file ???nh l?? duy nh???t
            $imgName = uniqid(); //unique ID
            //B3: l???y ra ph???n ??u??i (extension) c???a ???nh
            $imgExtension = $image ->guessExtension();
            //B4: g???p t??n m???i + ??u??i t???o th??nh t??n file ???nh ho??n thi???n
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuy???n file ???nh upload v??o th?? m???c ch??? ?????nh
            try {
                $image->move(
                    $this->getParameter('teacher_avatar'),
                    $imageName
                    //L??u ??: c???n khai b??o tham s??? ???????ng d???n c???a th?? m???c
                    //cho "author_avatar" ??? file config/services.yaml
                );
            } catch (FileException $e) {
                //throwException($e);
            }
            //B6: l??u t??n v??o database
            $teacher->setAvatar($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();

            $this->addFlash('Success', "Add teacher successfully !");
            return $this->redirectToRoute("teacher_index");
        }

        return $this->render(
            "teacher/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

}

