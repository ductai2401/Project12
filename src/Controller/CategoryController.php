<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render(
            'category/index.html.twig',[
                'categories' => $categories
            ]
            );
    }

    #[Route('/category/detail/{id}', name: 'category_detail')]
    public function categoryDetail($id){
        $category = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($category == null) {
            $this->addFlash('Error', 'Course not found !');
            return $this->redirectToRoute('category_index');
        } else { //$category != null
            return $this->render(
                'category/detail.html.twig',
                [
                    'category' => $category
                ]
            );
        }
    }

    #[Route('/category/delete/{id}', name: 'category_delete')] 
    public function deleteCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($category == null) {
            $this->addFlash('Error', 'Category not found !');
        } else { //$category != null
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('Success', 'Category has been deleted !');
        }
        return $this->redirectToRoute('category_index');
    }


}
