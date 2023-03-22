<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateAddressForClient extends AbstractController
{
    public function __invoke(Address $data): Address
    {
        $user = $this->getUser();

        if (!$user instanceof Client) {
            throw $this->createAccessDeniedException('Only clients can create addresses.');
        }
        $data->setClient($user);

        return $data;
    }
}
