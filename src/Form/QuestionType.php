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
                'label' => 'Proposition 1 - Bonne réponse - Champ obligatoire',
            ])
            ->add('prop2', TextType::class, [
                'label' => 'Proposition 2 - Fausse réponse - Champ obligatoire',
            ])
            ->add('prop3', TextType::class, [
                'label' => 'Proposition 3 - Fausse réponse'
            ])
            ->add('prop4', TextType::class, [
                'label' => 'Proposition 4 - Fausse réponse'
            ])
            ->add('prop5', TextType::class, [
                'label' => 'Proposition 5 - Fausse réponse'
            ])
            ->add('prop6', TextType::class, [
                'label' => 'Proposition 6 - Fausse réponse'
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
                'class' => 'w-100',
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
