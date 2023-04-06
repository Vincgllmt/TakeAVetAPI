<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Veto;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GetMeAppointmentsAction extends AbstractController
{
    public function __invoke(Request $request, AppointmentRepository $appointmentRepository): array
    {
        $show_validated = $request->query->get('show_validated', false);

        $user = $this->getUser();

        if (!($user instanceof Client) && !($user instanceof Veto)) {
            throw $this->createAccessDeniedException('Only a vet or a client can access his appointments');
        }

        if ($user instanceof Client) {
            return $appointmentRepository->findBy(['client' => $user]);
        } else {
            return $appointmentRepository->findBy(['veto' => $user, 'isValidated' => true === $show_validated]);
        }
    }
}
