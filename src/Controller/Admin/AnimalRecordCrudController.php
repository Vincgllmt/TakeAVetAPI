<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\AnimalRecord;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalRecordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnimalRecord::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            NumberField::new('weight'),
            NumberField::new('height'),
            TextField::new('otherInfos'),
            TextField::new('healthInfos'),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
            AssociationField::new('Animal', 'Animal')
                ->setFormType(Animal::class)
                ->formatValue(function (?string $value, AnimalRecord $entity) {
                    return $entity->getAnimal()?->getName();
                })->hideOnForm(),
        ];
    }
}
