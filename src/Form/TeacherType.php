<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'name',
                'required' => true,
            ]
            )
            ->add('age', IntegerType::class,
            [
                'label' => 'age',
                'required' => true,
            ]
            )
            ->add('avatar', FileType::class,
            [
                'label' => "Teacher Avatar",
                'data_class' => null,
                'required' => is_null($builder->getData()->getAvatar()),
                'required' => false
            ]
            )
            ->add('address', TextType::class,
            [
                'label' => 'address',
                'required' => true,
            ]
            )
            ->add('phone', TextType::class,
            [
                'label' => 'phone',
                'required' => true,
            ]
            )
            ->add('course', EntityType::class,
            [
                'label' => 'Course List',
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
            'data_class' => Teacher::class,
        ]);
    }
}
