<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\AnimalRecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRecordRepository::class)]
#[ORM\Table(name: '`animalRecord`')]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get on record for an animal',
            ],
            normalizationContext: ['groups' => ['animalRecord:read']]
        ),
        new GetCollection(
            uriTemplate: '/animal_records/from/{animalId}',
            uriVariables: [
                'animalId' => new Link(fromProperty: 'id', toProperty: 'Animal', fromClass: Animal::class),
            ],
            openapiContext: [
                'summary' => 'Get all records for an animal',
            ],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and (user.isClient() or user.isVeto())',
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update a record for an animal',
            ],
            normalizationContext: ['groups' => ['animalRecord:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()'
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Delete a record for an animal',
            ],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()'
        ),
        new Post(
            openapiContext: [
                'summary' => 'Create a record for an animal',
            ],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()'
        ),
    ]
)]
class AnimalRecord
{
    /**
     * @var int|null The record's ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animalRecord:read'])]
    private ?int $id = null;

    /**
     * @var float|null The animal's weight
     */
    #[ORM\Column]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?float $weight = null;

    /**
     * @var float|null The animal's height
     */
    #[ORM\Column]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?float $height = null;

    /**
     * @var \DateTimeInterface|null The date at which the record was modified last
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Animal|null The animal defined in the record
     */
    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?Animal $Animal = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?string $otherInfos = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['animalRecord:read', 'animalRecord:write'])]
    private ?string $healthInfos = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOtherInfos(): ?string
    {
        return $this->otherInfos;
    }

    public function setOtherInfos(string $otherInfos): self
    {
        $this->otherInfos = $otherInfos;

        return $this;
    }

    public function getHealthInfos(): ?string
    {
        return $this->healthInfos;
    }

    public function setHealthInfos(string $healthInfos): self
    {
        $this->healthInfos = $healthInfos;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->Animal;
    }

    public function setAnimal(?Animal $Animal): self
    {
        $this->Animal = $Animal;

        return $this;
    }
}
