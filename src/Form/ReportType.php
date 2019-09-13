<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateAt')
            ->add('rushOf')
            ->add('startAt')
            ->add('stopAt')
            ->add('zone')
            ->add('position')
            ->add('isResponsible')
            ->add('goals')
            ->add('studentComments')
            ->add('feelRush')
            ->add('isSeen')
            ->add('manager')
            ->add('student')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
