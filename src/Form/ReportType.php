<?php

namespace App\Form;

use App\Entity\Report;
use App\Entity\User;
use App\Enum\ShiftEnum;
use App\Enum\ZoneEnum;
use App\Repository\UserRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Select a date'
            ])
            ->add('rushOf', ChoiceType::class, [
                'label' => 'Select the rush',
                'choices' => ShiftEnum::getConstants()
            ])
            ->add('startAt', TimeType::class, [
                'label' => 'Start',
                'attr' => [
                    'class' => 'd-inline-flex'
    ]
            ])
            ->add('stopAt', TimeType::class, [
                'label' => 'End',
                'attr' => [
                    'class' => 'd-inline-flex'
                ]
            ])
            ->add('zone', ChoiceType::class, [
                'choices' => ZoneEnum::getConstants()
            ])
            ->add('position', TextType::class, [
                'label' => 'Position'
            ])
            ->add('isResponsible', CheckboxType::class, [
                'label' => 'Zone responsible'
            ])
            ->add('goals', CKEditorType::class, [
                'config_name' => 'goal',
                'label' => 'Goals',
            ])
            ->add('studentComments', CKEditorType::class, [
                'config_name' => 'base_config',
                'label' => 'Your comment',
            ])
            ->add('feelRush', RangeType::class, [
                'label' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 5
    ]
            ])
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                return $userRepository->userForManageRush();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
