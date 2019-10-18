<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('tags', EntityType::class, [
                'label' => 'Categories',
                'class' => 'App\Entity\Tag',
                'query_builder' => function(TagRepository $repository) {
                    return $repository->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');

                },
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'attr' => [
                'class' => 'w-100',
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
