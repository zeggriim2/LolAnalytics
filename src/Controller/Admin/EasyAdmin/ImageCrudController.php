<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('complete'),
            TextField::new('sprite'),
            TextField::new('groupe'),
            NumberField::new('x'),
            NumberField::new('y'),
            NumberField::new('w'),
            NumberField::new('h'),
            AssociationField::new('champion'),
        ];
    }
}
