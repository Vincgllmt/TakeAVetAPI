<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\User;
use App\Repository\MediaObjectRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
final class UploadAvatarAction extends AbstractController
{
    private Imagine $imagine;
    private Security $security;

    public function __construct(Security $security, SluggerInterface $slugger)
    {
        $this->imagine = new Imagine();
        $this->security = $security;
    }

    public function __invoke(Request $request, MediaObjectRepository $mediaObjectRepository): User
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (UPLOAD_ERR_OK != $uploadedFile->getError()) {
            throw new \RuntimeException("File upload error: {$uploadedFile->getError()} ({$uploadedFile->getErrorMessage()})");
        }

        $tempFilePath = $uploadedFile->getRealPath();

        // resize at fixed size of 512x512.
        $image = $this->imagine->open($tempFilePath)
            ->thumbnail(new Box(512, 512), ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $image->save($tempFilePath.'.webp', [
            'format' => 'webp',
        ]);

        // create a new MediaObject
        $mediaObject = new MediaObject();
        $mediaObject->file = new UploadedFile($tempFilePath.'.webp', $uploadedFile->getClientOriginalName(), 'image/webp', $uploadedFile->getError(), true);

        // set the avatar for the current user
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $user->setAvatar($mediaObject);
        }

        // ugly hack to clean up the media object from upload.
        $mediaObject->file = null;

        return $user;
    }
}
