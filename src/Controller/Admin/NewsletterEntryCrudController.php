<?php

namespace App\Controller\Admin;

use App\Entity\NewsletterEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewsletterEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsletterEntry::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('email'),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
        ];
    }
}
