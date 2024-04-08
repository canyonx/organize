<?php

namespace App\Controller\Admin;

use App\Entity\Signal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SignalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Signal::class;
    }

    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         ->setEntityLabelInPlural('Signalments')
    //         ->setEntityLabelInSingular('Signalment')
    //         ->setSearchFields(['member', 'type', 'number'])
    //         ->setDefaultSort(['id' => 'DESC']);
    // }

    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         ->disable(Action::NEW, Action::EDIT)
    //         ->add(Crud::PAGE_INDEX, Action::DETAIL);
    // }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         TextField::new('type'),
    //         NumberField::new('number'),
    //         AssociationField::new('member', 'signal by'),
    //         TextField::new('message'),
    //     ];
    // }
}
