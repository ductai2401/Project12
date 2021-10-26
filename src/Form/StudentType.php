<?php

namespace App\Form;
use App\Entity\Course;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


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
            ->add('age', IntegerType::class, [
                'label' => 'Student Age',
                'required' =>true
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'data_class' => null,
                'required' => is_null($builder->getData()->getAvatar())

            ])
            ->add('address', Texttype::class, [
                'label' => 'Student address',
                'required' =>true
                ])
            ->add('phone', Texttype::class, [
                'label' => 'Student phone',
                'required' =>true
            ])
            ->add('course', EntityType::class, [
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
