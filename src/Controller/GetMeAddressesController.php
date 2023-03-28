<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeAddressesController extends AbstractController
{
    public function __invoke(AddressRepository $addressRepository): array
    {
        $user = $this->getUser();

        if (!($user instanceof Client)) {
            throw $this->createAccessDeniedException('Only clients can access their addresses.');
        }

        return $addressRepository->findBy(['client' => $user]);
    }
}
