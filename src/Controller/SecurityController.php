<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(path: '/api/login', name: 'app_api_login', methods: ['POST'])]
    public function login(): Response
    {
//        return $this->json([
//            'user' => $this->security->getUser()?->getId(),
//        ]);
        return $this->redirectToRoute('_api_/me_get_collection');
    }

    #[Route(path: '/login', name: 'app_login_web', methods: ['GET'])]
    public function login_web(): Response
    {
        return $this->render('security/login.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
