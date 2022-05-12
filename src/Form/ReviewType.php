<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('content', TextareaType::class)
            ->add('rating', ChoiceType::class, [
                'choices'   => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                ]
            ])
            ->add('reactions', ChoiceType::class, [
                'choices'   => [
                    'smile' => 'smile',
                    'cry' => 'cry',
                    'think' => 'think',
                    'sleep' => 'sleep',
                    'dream' => 'dream'
                ],
                'multiple' => true,
                'expanded' => true,
                
            ])
            ->add('watchedAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'choice',
                'help' => 'Date à laquelle vous avez regarder ce film.',
                'label' => 'Date de visionnage',
            ])
            // ->add('movie', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
