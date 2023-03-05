<?php

namespace App\Controller\Admin;

use App\Entity\Agenda;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class AgendaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Agenda::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('unavailabilities', 'Indisponibiltés'),
            AssociationField::new('vacations', 'Vacances'),
            AssociationField::new('veto', 'Vétérinaire'),
        ];
    }
}
