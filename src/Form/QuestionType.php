<?php

namespace App\Form;

use App\Entity\Question;
use App\Enum\LevelEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class)
            ->add('prop1', TextType::class, [
                'label' => 'Bonne réponse'
            ])
            ->add('prop2', TextType::class, [
                'label' => 'Fausse réponse'
            ])
            ->add('prop3', TextType::class, [
                'label' => 'Fausse réponse'
            ])
            ->add('prop4', TextType::class, [
                'label' => 'Fausse réponse'
            ])
            ->add('level', ChoiceType::class, [
                'choices' => LevelEnum::getConstants()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'attr' => [
                'class' => 'w-100'
            ]
        ]);
    }
}
