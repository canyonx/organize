<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('pseudo', TextType::class, [
            //     'disabled' => true
            // ])
            ->add('birthAt', DateType::class, [
                'label' => 'Date de naissance'
            ])
            ->add('city', HiddenType::class, [
                'label' => false,
            ])
            ->add('lat', HiddenType::class, [
                'label' => false,
            ])
            ->add('lng', HiddenType::class, [
                'label' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'La taille du fichier ne doit pas dépasser 300 ko',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG ou GIF',
                    ]),
                ],
            ])
            ->add('about', TextareaType::class, [
                'label' => 'Présentation',
                'required' => false,
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('activities', EntityType::class, [
                'label' => 'Activités',
                'class' => Activity::class,
                'choice_label' => function (Activity $activity): string {
                    return ucfirst($activity->getName());
                },
                'multiple' => true,
                'autocomplete' => true, // Symfony-ux Autocomplete
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
            ])
            ->add('facebook', UrlType::class, [
                'label' => 'Facebook',
                'required' => false,
            ])
            ->add('instagram', UrlType::class, [
                'label' => 'Instagram',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
