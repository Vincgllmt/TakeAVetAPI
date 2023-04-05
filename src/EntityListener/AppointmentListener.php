<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Appointment;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Appointment::class
)]
class AppointmentListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Appointment $appointment): void
    {
        $user = $this->security->getUser();
        if ($user instanceof Client) {
            $appointment->setClient($user);
        }
        $appointment->setIsValidated(false);
        $appointment->setIsCompleted(false);
        $appointment->setEndHour($appointment->getStartHour()->add(new \DateInterval("PT{$appointment->getType()->getDuration()}M")));
    }
}
