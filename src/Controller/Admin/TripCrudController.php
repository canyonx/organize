<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TripCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('dateAt')
            ->add('title')
            ->add('activity');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['dateAt' => 'ASC'])
            ->setEntityLabelInPlural('Trips')
            ->setEntityLabelInSingular('Trip');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            DateField::new('dateAt')
                ->setFormat("dd MMMM - HH'h'mm"),
            TextField::new('activity'),
            TextField::new('title'),
            BooleanField::new('isAvailable'),
            TextField::new('location'),
            TextEditorField::new('description')
                ->hideOnIndex(),
        ];
    }
}
