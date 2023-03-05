<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\TypeAnimal;
use App\Repository\TypeAnimalRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
            TextField::new('note'),
            TextField::new('specificRace'),
            DateField::new('birthday'),
            TextField::new('gender'),
            BooleanField::new('inFarm', 'Est-il dans une ferme ?'),
            BooleanField::new('isGroup', 'S\'agit-il d\'un groupe d\'animaux ?'),
            AssociationField::new('type', 'Type d\'animal')
                ->setFormType(TypeAnimal::class)
                ->formatValue(function (?string $value, Animal $entity) {
                    return $entity->getType()?->getName();
                })
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('query_builder', function (TypeAnimalRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                })->hideOnForm(),
        ];
    }
}
