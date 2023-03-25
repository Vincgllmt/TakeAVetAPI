<?php

namespace App\Controller;

use App\Entity\Veto;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetAppointmentOnCurrentDayForVetoAction extends AbstractController
{
    public function __invoke(AppointmentRepository $appointmentRepository): array
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException('Only vetos can get his appointments');
        }

        return $appointmentRepository->findAllAppointmentOnDay($user, new \DateTime(), false);
    }
}
