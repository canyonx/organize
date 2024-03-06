<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isNewTripRequest', CheckboxType::class, [
                'label' => 'Nouvelle demande de participation',
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'required' => false,
            ])
            ->add('isNewMessage', CheckboxType::class, [
                'label' => 'Nouveau message',
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'required' => false,
            ])
            ->add('isTripRequestStatusChange', CheckboxType::class, [
                'label' => 'Changement de status pour mes demandes',
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'required' => false,
            ])
            ->add('isFriendNewTrip', CheckboxType::class, [
                'label' => 'Nouvelle sortie crée par un ami',
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
