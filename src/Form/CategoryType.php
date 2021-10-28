<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
            [
                'label' => "Category Name", 
                'required' => true
            ]
            )
            ->add('description',TextType::class,
            [
                'label' => "Description", 
                'required' => true
            ]
            )

            ->add('courseList', EntityType::class,
            [
                'label' => 'Course',
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]
            )
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
