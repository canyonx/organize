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

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('dateAt', DateTimeType::class, [
                'date_label' => 'Date',
                'date_widget' => 'single_text',
                'time_label' => 'Heure',
                'time_widget' => 'choice',
                // 'disabled' => $options['edit']

            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 6
                ]
            ])
            ->add('location', HiddenType::class, [
                'label' => 'Ville'
            ])
            ->add('lat', HiddenType::class, [
                'label' => 'Latitude',
                // 'disabled' => true
            ])
            ->add('lng', HiddenType::class, [
                'label' => 'Longitude',
                // 'disabled' => true
            ])
            ->add('activity', EntityType::class, [
                'label' => 'Activité',
                'class' => Activity::class,
                'autocomplete' => true, // Symfony-ux Autocomplete
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
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
