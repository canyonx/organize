<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Users')
            ->setEntityLabelInSingular('User')
            ->setSearchFields(['pseudo', 'email', 'city', 'birthAt'])
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Tab global data
            FormField::addColumn(6),
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('email'),
            TextField::new('pseudo')
                ->hideOnIndex(),
            TextField::new('slug'),
            TextField::new('city'),
            DateField::new('createdAt')
                ->hideOnIndex()
                ->hideOnForm(),
            DateField::new('lastConnAt')
                ->hideOnIndex()
                ->hideOnForm(),

            // Tab pesonnal data
            FormField::addColumn(6),
            DateField::new('birthAt'),
            TextEditorField::new('about')
                ->hideOnIndex(),
            CollectionField::new('activities')
                ->hideOnIndex(),
            BooleanField::new('isVerified'),
            NumberField::new('lat')
                ->hideOnIndex(),
            NumberField::new('lng')
                ->hideOnIndex(),

            ImageField::new('avatar')
                ->setBasePath('images/uploads/')
                ->setUploadDir('public/images/uploads/')
                ->setUploadedFileNamePattern('bg-[randomhash].[extension]')
                ->setRequired(false),
            UrlField::new('facebook')
                ->hideOnIndex(),
            UrlField::new('instagram')
                ->hideOnIndex(),

        ];
    }
}
