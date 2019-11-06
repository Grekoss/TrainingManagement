<?php

namespace App\Form;

use App\Entity\Mentor;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mentor', EntityType::class, [
                'class' => 'App\Entity\User',
                'label' => 'Choice the mentor',
                'query_builder' => function (UserRepository $user) {
                    return $user->createQueryBuilder('u')
                        ->where('u.role = :teacher')
                        ->orWhere('u.role = :store')
                        ->setParameter('teacher', 'ROLE_TEACHER')
                        ->setParameter('store', 'ROLE_STORE')
                        ->orderBy('u.firstName', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mentor::class,
            'attr' => [
                'class' => 'w-50 m-auto'
            ]
        ]);
    }
}
