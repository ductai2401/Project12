<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_index')]
    public function index()
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
        } else { //$teacher != null
            return $this->render(
                'teacher/detail.html.twig',
                [
                    'teacher' => $teacher
                ]
            );
        }
    }

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

    // #[Route('/teacher/add/{id}', name: 'teacher_add')]
    // public function addAuthor(Request $request)
    // {
    //     $author = new Teacher();
    //     $form = $this->createForm(AuthorType::class, $author);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         //code xử lý ảnh upload
    //         //B1: lấy ảnh từ file upload
    //         $image = $author->getAvatar();
    //         //B2: tạo tên mới cho ảnh => tên file ảnh là duy nhất
    //         $imgName = uniqid(); //unique ID
    //         //B3: lấy ra phần đuôi (extension) của ảnh
    //         $imgExtension = $image->guessExtension();
    //         //B4: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
    //         $imageName = $imgName . "." . $imgExtension;
    //         //B5: di chuyển file ảnh upload vào thư mục chỉ định
    //         try {
    //             $image->move(
    //                 $this->getParameter('author_avatar'),
    //                 $imageName
    //                 //Lưu ý: cần khai báo tham số đường dẫn của thư mục
    //                 //cho "author_avatar" ở file config/services.yaml
    //             );
    //         } catch (FileException $e) {
    //             //throwException($e);
    //         }
    //         //B6: lưu tên vào database
    //         $author->setAvatar($imageName);

    //         $manager = $this->getDoctrine()->getManager();
    //         $manager->persist($author);
    //         $manager->flush();

    //         $this->addFlash('Success', "Add author successfully !");
    //         return $this->redirectToRoute("author_index");
    //     }

    //     return $this->render(
    //         "author/add.html.twig",
    //         [
    //             'form' => $form->createView()
    //         ]
    //     );
    // }



}