<?php

namespace App\Controller\Admin;

use App\Entity\Travel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TravelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Travel::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
