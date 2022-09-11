<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\StatChampion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StatChampionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StatChampion::class;
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
