<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Map;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MapCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Map::class;
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