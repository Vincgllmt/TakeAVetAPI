<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Vacation;
use App\Entity\Veto;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Vacation::class
)]
class VacationListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Vacation $vacation): void
    {
        $user = $this->security->getUser();
        /* @var Veto $user */

        if (!$user instanceof Veto) {
            throw new \LogicException('You need to be a Veto to create a vacation.');
        }

        $vacation->setAgenda($user->getAgenda());
    }
}
