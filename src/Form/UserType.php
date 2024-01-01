<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Setting;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthAt')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('phone')
            ->add('avatar')
            ->add('about')
            ->add('activities', EntityType::class, [
                'class' => Activity::class,
                // 'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
