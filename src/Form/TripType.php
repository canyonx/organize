<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('dateAt', DateTimeType::class, [
                'label' => 'Date et heure'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('location', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('lat', NumberType::class, [
                'label' => 'Latitude',
                // 'disabled' => true
            ])
            ->add('lng', NumberType::class, [
                'label' => 'Longitude',
                // 'disabled' => true
            ])
            ->add('activity', EntityType::class, [
                'label' => 'Activité',
                'class' => Activity::class,
                'autocomplete' => true, // Symfony-ux Autocomplete
                // 'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
