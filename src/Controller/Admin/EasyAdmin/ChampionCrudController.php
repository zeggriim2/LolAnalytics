<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Champion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ChampionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Champion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('idName'),
            NumberField::new('key'),
            TextField::new('Name'),
            TextField::new('Name'),
            DateTimeField::new('createdAt'),
            AssociationField::new('version'),
            AssociationField::new('statChampion'),
        ];
    }
}
