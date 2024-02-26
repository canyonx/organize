<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Setting;
use App\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                // 'label' => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
