<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\NewsletterEntry;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(
    event: Events::prePersist,
    entity: NewsletterEntry::class
)]
class NewsletterListener
{
    public function prePersist(NewsletterEntry $newsletterEntry): void
    {
        $newsletterEntry->setCreatedAt(new \DateTimeImmutable());
    }
}
