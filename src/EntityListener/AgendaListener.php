<?php

namespace App\EntityListener;

use App\Entity\Agenda;
use App\Entity\Veto;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Agenda::class
)]
#[AsEntityListener(
    event: Events::preRemove,
    entity: Agenda::class
)]
class AgendaListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Agenda $agenda): void
    {
        $user = $this->security->getUser();

        if ($user instanceof Veto) {
            $agenda->setVeto($user);
        }
    }

    public function preRemove(Agenda $agenda): void
    {
        $agenda->setVeto(null);
    }
}
