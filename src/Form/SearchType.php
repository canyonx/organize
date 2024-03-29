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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        for ($i = 1; $i < 6; $i++) {
            $val = $i * 10;
            $choices[$val . ' km'] = $val;
        }

        $builder
            // ->add('dateAt', DateType::class, [
            //     'widget' => 'single_text',
            //     'required' => false,
            //     'placeholder' => (new \DateTime('today'))->format('d/m/Y'),
            //     'empty_data' => (new \DateTime('today'))->format('Y-m-d'),
            //     'attr' => [
            //         'class' => 'js-datepicker',
            //         'min' => date('d/m/Y')
            //     ]
            // ])
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
            ->add('activity', EntityType::class, [
                'label' => false,
                // 'label' => 'Activité',
                'attr' => [
                    'placeholder' => 'Activité'
                ],
                'class' => Activity::class,
                'choice_label' => function (Activity $activity): string {
                    return ucfirst($activity->getName());
                },
                // 'multiple' => true,
                'autocomplete' => true, // Symfony-ux Autocomplete
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'required' => false
            ])
            ->add('isFriend', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Utilisateurs suivis',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
