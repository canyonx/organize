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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'disabled' => true
            ])
            ->add('birthAt', DateType::class, [])
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
