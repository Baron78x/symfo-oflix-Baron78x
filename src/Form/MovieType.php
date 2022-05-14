<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add( 'releaseDate', DateType::class, [
                //'years' => range(date('Y'), date('Y') - 100),
                // De la date courante jusqu'à la date du premier film
                'years' => range(date('Y') + 5, 1895),
            ])
            ->add('duration')
            ->add('poster', UrlType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Film' => 'Film',
                    'Série' => 'Série',
                ],
                'expanded' => true,
            ])
            ->add('summary', TextareaType::class)
            ->add('synopsis')
            // ->add('rating')
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'help' => 'Sélectionner au moins un genre.',
                // @link https://symfony.com/doc/current/reference/forms/types/entity.html#using-a-custom-query-for-the-entities
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                },
            ])
        ;
    }

    /**
     * Similaire au constructeur mais pour les options du form
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            // On peut mettre cette option ici ou dans le template
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
