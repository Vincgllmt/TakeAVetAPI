<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Veto;
use App\Repository\AnimalRepository;
use App\Repository\MediaObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetImagesFromAnimalController extends AbstractController
{
    public function __invoke(int $animalId, AnimalRepository $animalRepository, MediaObjectRepository $mediaObjectRepository): array
    {
        $user = $this->getUser();

        if (!($user instanceof Client) && !($user instanceof Veto)) {
            throw $this->createAccessDeniedException('Only clients and vetos can access to animals images.');
        }

        $animal = $animalRepository->find($animalId);
        if (null === $animal || ($user instanceof Client && $user !== $animal->getOwner())) {
            throw $this->createAccessDeniedException('Your are not allowed to access to this animal.');
        }

        return $animal->getImages()->getValues();
    }
}
