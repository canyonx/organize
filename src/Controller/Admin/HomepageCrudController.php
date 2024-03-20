<?php

namespace App\Controller\Admin;

use App\Entity\Homepage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomepageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Homepage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            ImageField::new('background')
                ->setBasePath('images/')
                ->setUploadDir('public/images/')
                ->setUploadedFileNamePattern('bg-[randomhash].[extension]')
                ->setRequired(false),
        ];
    }
}
