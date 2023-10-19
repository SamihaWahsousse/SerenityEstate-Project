<?php

namespace App\Controller\Admin;

use App\Entity\CityRegion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CityRegionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CityRegion::class;
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
