<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeController extends AbstractController
{
    public function __invoke(): User
    {
        $user = $this->getUser();

        /* @var User $user */
        return null == $user
            ? throw $this->createNotFoundException() : $user;
    }
}
