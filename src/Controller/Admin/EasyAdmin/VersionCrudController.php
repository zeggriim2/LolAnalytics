<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Version;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VersionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Version::class;
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