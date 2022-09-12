<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Equipe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EquipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipe::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('name', 'Nom'),
            TextField::new('shortName','Nom court'),
            DateTimeField::new('createdAt', 'Date de création')->hideOnForm(),
        ];
    }
}
