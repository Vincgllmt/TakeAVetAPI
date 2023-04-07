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
        
        if ($appointment->isIsValidated() === null) {
            $appointment->setIsValidated(false);
        }
        if ($appointment->isIsCompleted() === null) {
            $appointment->setIsCompleted(false);
        }
        
        if (null === $appointment->getEndHour()) {
            $appointment->setEndHour((clone $appointment->getStartHour())->add(new \DateInterval("PT{$appointment->getType()->getDuration()}M")));
        }
    }
}
