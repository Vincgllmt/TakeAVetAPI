<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Veto;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetAppointmentOnCurrentHourForVetoAction extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(AppointmentRepository $appointmentRepository): ?Appointment
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException('Only vetos can get his appointments');
        }

        return $appointmentRepository->findAppointmentOnHour($user, new \DateTime());
    }
}
