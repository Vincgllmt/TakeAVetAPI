<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\User;
use App\Repository\MediaObjectRepository;
use App\Repository\UserRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
final class CreateMediaAvatarAction extends AbstractController
{
    private Imagine $imagine;
    private Security $security;

    public function __construct(Security $security, SluggerInterface $slugger)
    {
        $this->imagine = new Imagine();
        $this->security = $security;
    }

    public function __invoke(Request $request, MediaObjectRepository $mediaObjectRepository, UserRepository $userRepository): MediaObject
    {
        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException('Invalid user');
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (UPLOAD_ERR_OK != $uploadedFile->getError()) {
            throw new UploadException("File upload error: {$uploadedFile->getError()} ({$uploadedFile->getErrorMessage()})");
        }

        $tempFilePath = $uploadedFile->getRealPath();

        // resize at fixed size of 512x512.
        $image = $this->imagine->open($tempFilePath)
            ->thumbnail(new Box(512, 512), ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $image->save($tempFilePath.'.webp', [
            'format' => 'webp',
        ]);

        $transformedAvatarFile = new UploadedFile($tempFilePath.'.webp', $uploadedFile->getClientOriginalName(), 'image/webp', $uploadedFile->getError(), true);

        $mediaObject = new MediaObject();
        $mediaObject->file = $transformedAvatarFile;

        // save the media object and avoid $file serialization on the avatar setter
        $mediaObjectRepository->save($mediaObject, true);
        $userRepository->updateAvatar($user, $mediaObject);

        return $mediaObject;
    }
}
