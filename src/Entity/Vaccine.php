<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\VaccineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VaccineRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get one vaccine',
            ],
            normalizationContext: ['groups' => ['vaccine:read']]),
        new GetCollection(openapiContext: ['summary' => 'Get all vaccine']),
        new Post(
            openapiContext: [
                'summary' => 'create a vaccine'
            ],
            normalizationContext: ['groups' => ['vaccine:create']]
        ),
        new Delete(
            openapiContext: [
                'summary' => 'delete a vaccine'
            ]
        ),
        new Put(
            openapiContext: ['summary' => 'replace a vaccine'],
            normalizationContext: ['groups' => ['vaccine:replace']]
        ),
        new Patch(
            openapiContext: ['summary' => 'Update a vaccine'],
            normalizationContext: ['groups' => ['vaccine:update']]
        ),
    ]
)]
class Vaccine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $next = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last = null;

    #[ORM\ManyToOne(inversedBy: 'vaccines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNext(): ?\DateTimeInterface
    {
        return $this->next;
    }

    public function setNext(?\DateTimeInterface $next): self
    {
        $this->next = $next;

        return $this;
    }

    public function getLast(): ?\DateTimeInterface
    {
        return $this->last;
    }

    public function setLast(?\DateTimeInterface $last): self
    {
        $this->last = $last;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }
}
