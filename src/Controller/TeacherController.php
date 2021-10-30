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
                //code xử lý ảnh upload
                //B1: lấy dữ liệu ảnh từ form 
                $file = $form['avatar']->getData();
                //B2: check xem file ảnh upload có null không
                if ($file != null) {
                    //B3: lấy ảnh từ file upload
                    $image = $teacher->getAvatar();
                    //B4: tạo tên mới cho ảnh => tên file ảnh là duy nhất
                    $imgName = uniqid(); //unique ID
                    //B5: lấy ra phần đuôi (extension) của ảnh
                    $imgExtension = $image->guessExtension();
                    //B6: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
                    $imageName = $imgName . "." . $imgExtension;
                    //B7: di chuyển file ảnh upload vào thư mục chỉ định
                    try {
                        $image->move(
                            $this->getParameter('teacher_avatar'),
                            $imageName
                            //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                            //cho "book_cover" ở file config/services.yaml
                        );
                    } catch (FileException $e) {
                        throwException($e);
                    }
                    //B8: lưu tên vào database
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
            //code xử lý ảnh upload
            //B1: lấy ảnh từ file upload
            $image = $teacher->getAvatar();
            //B2: tạo tên mới cho ảnh => tên file ảnh là duy nhất
            $imgName = uniqid(); //unique ID
            //B3: lấy ra phần đuôi (extension) của ảnh
            $imgExtension = $image ->guessExtension();
            //B4: gộp tên mới + đuôi tạo thành tên file ảnh hoàn thiện
            $imageName = $imgName . "." . $imgExtension;
            //B5: di chuyển file ảnh upload vào thư mục chỉ định
            try {
                $image->move(
                    $this->getParameter('teacher_avatar'),
                    $imageName
                    //Lưu ý: cần khai báo tham số đường dẫn của thư mục
                    //cho "author_avatar" ở file config/services.yaml
                );
            } catch (FileException $e) {
                //throwException($e);
            }
            //B6: lưu tên vào database
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

