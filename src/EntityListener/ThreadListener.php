<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Thread::class
)]
class ThreadListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Thread $thread): void
    {
        if (null === $thread->getAuthor()) {
            $thread->setAuthor($this->security->getUser());
        }
        $thread->setCreatedAt(new \DateTimeImmutable());
        $thread->setResolved(false);
    }
}
