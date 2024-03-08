<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('city', TextType::class, [
                'label' => false,
                // 'attr' => [
                //     'inert' => true,
                //     'style' => 'background-color:#e9ecef'
                // ],
            ])
            ->add('lat', NumberType::class, [
                'label' => false,
                // 'attr' => [
                //     'inert' => true,
                //     'style' => 'background-color:#e9ecef'
                // ],
            ])
            ->add('lng', NumberType::class, [
                'label' => false,
                // 'attr' => [
                //     'inert' => true,
                //     'style' => 'background-color:#e9ecef'
                // ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false
            ])
            ->add('about', TextareaType::class, [
                'label' => 'Présentation',
                'required' => false
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
