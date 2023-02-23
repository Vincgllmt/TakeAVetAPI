<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\ThreadReply;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: ThreadReply::class
)]
class ThreadReplyListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(ThreadReply $threadReply): void
    {
        $threadReply->setCreatedAt(new \DateTimeImmutable());

        $user = $this->security->getUser();
        if (null !== $user) {
            /* @var User $user */
            $threadReply->setUser($user);
        }
    }
}
