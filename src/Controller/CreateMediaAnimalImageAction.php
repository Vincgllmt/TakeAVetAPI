<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\MediaObject;
use App\Repository\AnimalRepository;
use App\Repository\MediaObjectRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
final class CreateMediaAnimalImageAction extends AbstractController
{
    private Imagine $imagine;

    public function __construct(Security $security, SluggerInterface $slugger)
    {
        $this->imagine = new Imagine();
    }

    public function __invoke(Request $request, Animal $animal, MediaObjectRepository $mediaObjectRepository, AnimalRepository $animalRepository): MediaObject
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (UPLOAD_ERR_OK != $uploadedFile->getError()) {
            throw new UploadException("File upload error: {$uploadedFile->getError()} ({$uploadedFile->getErrorMessage()})");
        }

        $tempFilePath = $uploadedFile->getRealPath();

        // resize at fixed size of 1920x1080
        $image = $this->imagine->open($tempFilePath)
            ->thumbnail(new Box(1920, 1080), ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $image->save($tempFilePath.'.webp', [
            'format' => 'webp',
        ]);

        $transformedAvatarFile = new UploadedFile($tempFilePath.'.webp', $uploadedFile->getClientOriginalName(), 'image/webp', $uploadedFile->getError(), true);

        $mediaObject = new MediaObject();
        $mediaObject->file = $transformedAvatarFile;

        $mediaObjectRepository->save($mediaObject, true);
        $animal->addImage($mediaObject);

        return $mediaObject;
    }
}
