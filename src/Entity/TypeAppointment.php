<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TypeAppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['typeAppointment:read']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['typeAppointment:read']],
        ),
    ]
)]
#[ORM\Entity(repositoryClass: TypeAppointmentRepository::class)]
class TypeAppointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['typeAppointment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['typeAppointment:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['typeAppointment:read'])]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    #[Groups(['typeAppointment:read'])]
    private ?string $description = null;

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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
