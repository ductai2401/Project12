<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Category;
use App\Entity\Teacher;
use App\Entity\Student;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Course Name',
                'required' => true,
            ]
            )
            ->add('startDate', DateType::class,
            [
                'label' => 'Course Start Date',
                'required' => false,
                'widget' => 'single_text',
            ]
            )
            ->add('endDate', DateType::class,
            [
                'label' => 'Course End Date',
                'required' => false,
                'widget' => 'single_text',
            ]
            )
            // ->add('teacherList', EntityType::class,
            // [
            //     'label' => 'Teacher List',
            //     'class' => Teacher::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => false,
            // ]
            // )
            // ->add('studentList', EntityType::class,
            // [
            //     'label' => 'Student List',
            //     'class' => Student::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => false,
            // ]
            // )
            ->add('category', EntityType::class,
            [
                'label' => 'Major',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
