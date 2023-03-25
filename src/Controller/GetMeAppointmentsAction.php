<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeAppointmentsAction extends AbstractController
{
    public function __invoke(AppointmentRepository $appointmentRepository): array
    {
        $user = $this->getUser();

        if (!($user instanceof Client)) {
            throw $this->createAccessDeniedException('Only clients can get his appointments');
        }

        return $appointmentRepository->findBy(['client' => $user]);
    }
}
