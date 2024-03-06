<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'currentPassword',
                PasswordType::class,
                [
                    'constraints' => [
                        new UserPassword([
                            'message' => 'Ne correspond pas a votre mot de passe actuel'
                        ])
                    ],
                    'label' => 'Mot de passe actuel',
                    'attr' => [
                        'placeholder' => '******'
                    ]
                ]
            )
            ->add(
                'newPassword',
                PasswordType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Choisisez un mot de passe !'
                        ]),
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Aller, on trouve un mot de passe un peu plus long !'
                        ])
                    ],
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'placeholder' => '******'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
