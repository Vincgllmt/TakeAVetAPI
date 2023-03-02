<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PostAvatarController extends AbstractController
{
    public function __invoke(User $data): Response
    {
        $avatarPath = $data->getAvatarPath();
        if ($avatarPath) {
            $dir = $this->getParameter('app.upload_avatar_dir');
            $avatar = file_get_contents($dir.'/'.$avatarPath);

            return new Response($avatar, 200, ['Content-Type' => 'image/png']);
        } else {
            return new Response(file_get_contents('media/default-avatar.png'), 200);
        }
    }
}
