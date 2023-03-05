<?php

namespace App\Controller\Admin;

use App\Entity\ThreadReply;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ThreadReplyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ThreadReply::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('description'),
            DateTimeField::new('createdAt'),
            AssociationField::new('thread', 'thread d\'origine'),
        ];
    }
}
