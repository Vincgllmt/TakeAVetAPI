<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeAnimalsController extends AbstractController
{
    public function __invoke(AnimalRepository $animalRepository): array
    {
        $user = $this->getUser();

        if (!($user instanceof Client)) {
            throw $this->createAccessDeniedException('Only clients can access their animals.');
        }

        return $animalRepository->findBy(['owner' => $user]);
    }
}
