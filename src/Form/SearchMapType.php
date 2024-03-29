<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SearchMapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        for ($i = 1; $i < 6; $i++) {
            $val = $i * 10;
            $choices[$val . ' km'] = $val;
        }

        $builder
            ->add('dateAt', DateType::class, [
                'label' => false,
                // 'label' => 'Date',
                'widget' => 'single_text',
                'required' => true,
                'placeholder' => (new \DateTime('today'))->format('d/m/Y'),
                'empty_data' => (new \DateTime('today'))->format('Y-m-d'),
                'attr' => [
                    'class' => 'js-datepicker',
                    'min' => date('d/m/Y')
                ]
            ])
            ->add('location', HiddenType::class, [
                'label' => false
            ])
            ->add('lat', HiddenType::class, [
                'label' => false
            ])
            ->add('lng', HiddenType::class, [
                'label' => false
            ])
            ->add('distance', ChoiceType::class, [
                'label' => false,
                'mapped' => false,
                // 'required' => false,
                'choices' => $choices,
                // 'empty_data' => null
            ])
            // ->add('activity', EntityType::class, [
            //     'class' => Activity::class,
            //     'choice_label' => 'id',
            // ])
            ->add('isFriend', HiddenType::class, [
                'mapped' => false,
                'label' => 'Utilisateurs suivis',
                'required' => false,
                'empty_data' => false
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
