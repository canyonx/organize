<?php

namespace App\Controller\Admin;

use App\Entity\TripRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TripRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TripRequest::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('trip')
            ->add('member')
            ->add('status');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('TripRequests')
            ->setEntityLabelInSingular('TripRequest');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setDisabled(),
            AssociationField::new('member')
                ->setDisabled(),
            TextField::new('status'),
            TextField::new('trip')
                ->setDisabled(),
            CollectionField::new('messages')
                ->hideOnIndex(),

        ];
    }
}
