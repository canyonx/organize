<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(),
                    new Length(5, 50),
                    new Regex('/^\w+/')
                ]
            ])
            ->add('dateAt', DateTimeType::class, [
                'label' => 'Date et heure',
                'constraints' => [
                    new GreaterThanOrEqual('now'),
                    new LessThanOrEqual('now + 7 day')
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 6
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(5, 300),
                    new Regex('/^\w+/')
                ]
            ])
            ->add('location', HiddenType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(),
                    new Length(5, 100),
                    new Regex('/^\w+/')
                ]
            ])
            ->add('lat', HiddenType::class, [
                'label' => 'Latitude',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lng', HiddenType::class, [
                'label' => 'Longitude',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('activity', EntityType::class, [
                'label' => 'Activité',
                'class' => Activity::class,
                'autocomplete' => true, // Symfony-ux Autocomplete
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'edit' => false
        ]);
    }
}
