<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\StatChampion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StatChampionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StatChampion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            NumberField::new('hp')->setLabel('Point de vie'),
            NumberField::new('mp'),
            NumberField::new('mpperlevel'),
            NumberField::new('movespeed'),
            NumberField::new('armor'),
            NumberField::new('armorperlevel'),
            NumberField::new('spellblock'),
            NumberField::new('spellblockperlevel'),
            NumberField::new('attackrange'),
            NumberField::new('hpregen'),
            NumberField::new('hpregenperlevel'),
            NumberField::new('mpregen'),
            NumberField::new('mpregenperlevel'),
            NumberField::new('crit'),
            NumberField::new('critperlevel'),
            NumberField::new('attackdamage'),
            NumberField::new('attackdamageperlevel'),
            NumberField::new('attackspeedperlevel'),
            NumberField::new('attackspeed'),
            AssociationField::new('champion'),
        ];
    }
}
