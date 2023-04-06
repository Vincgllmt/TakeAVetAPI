<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgendaUpcomingAppointmentController extends AbstractController
{
    private AppointmentRepository $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function __invoke(array $data): array
    {
        $veto = $data[0]->getVeto();

        return $this->appointmentRepository->findBy(['veto' => $veto, 'isCompleted' => false, 'isValidated' => true]);
    }
}
