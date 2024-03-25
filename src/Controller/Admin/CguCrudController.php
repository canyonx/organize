<?php

namespace App\Controller\Admin;

use App\Entity\Cgu;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CguCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cgu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('number'),
            TextField::new('title'),
            TextEditorField::new('content'),
        ];
    }
}
