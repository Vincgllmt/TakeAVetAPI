<?php

namespace App\EntityListener;

use App\Entity\Unavailability;
use App\Entity\Veto;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Unavailability::class
)]
class UnavailabilityListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Unavailability $unavailability): void
    {
        $user = $this->security->getUser();

        if ($user instanceof Veto && null === $user->getAgenda()) {
            $unavailability->setAgenda($user->getAgenda());
        }
    }
}
