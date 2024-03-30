<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class SearchType extends AbstractType
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        for ($i = 1; $i < 6; $i++) {
            $val = $i * 10;
            $choices[$val . ' km'] = $val;
        }

        $builder
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
            ->add('isFriend', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Utilisateurs suivis',
                'required' => false
            ]);

        // If use search form from search
        if ($options['page'] == 'search') {
            $builder
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
                ]);
        }

        // If use search form from map
        if ($options['page'] == 'map') {
            $builder
                ->add('dateAt', DateType::class, [
                    'label' => false,
                    'widget' => 'single_text',
                    'required' => false,
                    'placeholder' => (new \DateTime('today'))->format('d/m/Y'),
                    'empty_data' => (new \DateTime('today'))->format('Y-m-d'),
                    'attr' => [
                        'class' => 'js-datepicker',
                        'min' => date('d/m/Y')
                    ],
                    'constraints' => [
                        new GreaterThanOrEqual(new \DateTimeImmutable('today')),
                        new LessThanOrEqual(new \DateTimeImmutable('today + ' . $this->parameterBag->get('app_planning_week') . ' day')),
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'page' => 'search',
        ]);
    }
}
