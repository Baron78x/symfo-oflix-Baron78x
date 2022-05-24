<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Membre' => 'ROLE_USER',
                    'Manager' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Choix multiple
                'multiple' => false,
                // Des boutons radios
                'expanded' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // Le user (qui est l'entité mappée sur le form) se trouve là
                $user = $event->getData();
                // Le form, pour continuer de travailler avec (car par accès aux variables en dehors de la fonction anonyme)
                $form = $event->getForm();

                // Add or Edit ?
                // Un nouveau User n'a pas d'id !
                if ($user->getId() === null) {
                    // Add (new)
                    $form->add('password', PasswordType::class, [
                        'constraints' => [
                            new NotBlank(),
                            new Regex("/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*['_', '-', '|', '%', '&', '*', '=', '@', '$']).{8,}$/")
                        ],
                        'help' => 'Au moins 8 caractères,
                            au moins une minuscule
                            au moins une majuscule
                            au moins un chiffre
                            au moins un caractère spécial parmi _, -, |, %, &, *, =, @, $'
                    ]);
                } else {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        // Pour l'edit
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé'
                        ],
                        // @link https://symfony.com/doc/current/reference/forms/types/text.html#mapped
                        // Ce champ de ne sera pas mappé sur l'entité automatiquement
                        // la propriété $password de $user ne sera pas modifiée par le traitement du form
                        'mapped' => false,
                    ]);
                }
            });

        // Ajout d'un Data Transformer
        // pour convertir la chaine choisie en un tableau
        // qui contient cette chaine et vice-versa
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            // De l'Entité vers le Form (affiche form)
            function ($rolesAsArray) {
                // transform the array to a string
                return implode(', ', $rolesAsArray);
            },
            // Du Form vers l'Entité (traite form)
            function ($rolesAsString) {
                // transform the string back to an array
                return explode(', ', $rolesAsString);
            }
        ));

        // On pourrait utiliser un DataTransformer pour ne gérer
        // qu'un seul rôle dans le form, et le convertir en tableau
        // pour qu'il soit compatible avec la propriété $roles = []

        // <3 JB
        // @link https://symfony.com/doc/5.4/form/data_transformers.html

        // ->add('roles', ChoiceType::class, [
        //     'label' => 'Rôles',
        //     'choices' => [
        //         'Membre' => 'ROLE_USER',
        //         'Manager' => 'ROLE_MANAGER',
        //         'Administrateur' => 'ROLE_ADMIN',
        //     ],
        //     // Choix multiple
        //     'multiple' => true,
        //     // Des checkboxes
        //     'expanded' => true,
        //     'help' => 'Un seul choix possible.'
        // ])

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
