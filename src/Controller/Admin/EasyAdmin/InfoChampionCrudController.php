<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\InfoChampion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class InfoChampionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InfoChampion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            NumberField::new('attack'),
            NumberField::new('defense'),
            NumberField::new('magic'),
            NumberField::new('difficulty'),
            DateTimeField::new('createdAt'),
            AssociationField::new('champion'),
        ];
    }
}
