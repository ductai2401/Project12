<?php

namespace App\Form;
use App\Entity\Course;
use App\Entity\Student;
use Symfony\Component\Form\TextType;
use Symfony\Component\Form\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, 
                [
                    'label' => 'Student Name',
                    'required' => true
                ]
            )
            ->add('age', Integertype::class, [
                'label' => 'Student Age',
                'required' =>true
            ])
            ->add('avatar', Filetype::class, [
                'label' => 'Student Avatar',
                'required' =>true
            ])
            ->add('address', Texttype::class, [
                'label' => 'Student address',
                'required' =>true
                ])
            ->add('phone', Texttype::class, [
                'label' => 'Student phone',
                'required' =>true
            ])
            ->add('course', Entity::class, [
                'label' => 'Course',
                'class' => Course::class,
                'choice_label' => "name", //hien thi course name de chon
                'multiple' => true,
                'expanded' => false   //true: textbox, false: dropdown
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
