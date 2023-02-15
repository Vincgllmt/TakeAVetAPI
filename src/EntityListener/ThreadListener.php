<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Thread::class
)]
class ThreadListener
{
    public function prePersist(Thread $thread): void
    {
        $thread->setCreatedAt(new \DateTimeImmutable());
        $thread->setResolved(false);
    }
}
