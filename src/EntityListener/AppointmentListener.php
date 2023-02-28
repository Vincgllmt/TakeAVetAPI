<?php

namespace App\EntityListener;

use App\Entity\Agenda;
use App\Entity\Appointment;
use App\Entity\Client;
use App\Entity\Veto;
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
        ob_start();
        var_dump($this->security->getUser());
        ob_flush();

        if ($this->security->getUser() instanceof Client) {
            // need to check if the user is a client
            $appointment->setClient($this->security->getUser());
        }

        // calculate the endHour
        $appointment->setEndHour((clone $appointment->getStartHour())->modify("+{$appointment->getType()->getDuration()} minutes"));

        $appointment->setIsValidated(false);
        $appointment->setIsCompleted(false);

        // TODO: check if the vet is available in the agenda, and the validity of the appointment, for now, we just set it to the value.
    }
}
