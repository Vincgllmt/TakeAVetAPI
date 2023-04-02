<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\MediaObject;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\StorageInterface;

class DeleteMediaAnimalImageAction extends AbstractController
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(Animal $animal, MediaObject $imageId, AnimalRepository $animalRepository): Response
    {
        $this->storage->remove($imageId, new PropertyMapping('file', 'contentUrl'));
        $animal->removeImage($imageId);
        $animalRepository->save($animal, true);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
