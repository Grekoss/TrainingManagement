<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Enum\CategoryEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('file', FileType::class, [
                'data_class' => null,
                'required' => false
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Select the category',
                'choices' => CategoryEnum::getConstants()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'mt-3 m-auto w-75'
            ]
        ]);
    }
}
