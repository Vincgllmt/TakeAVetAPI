<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\MediaObject;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(
    event: Events::prePersist,
    entity: MediaObject::class
)]
class MediaObjectListener
{
    public function prePersist(MediaObject $mediaObject): void
    {
        $mediaObject->setCreatedAt(new \DateTimeImmutable());
    }
}
