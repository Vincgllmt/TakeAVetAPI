<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\AppointmentRepository;
use App\Validator\IsVet;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
      new Post(
          uriTemplate: '/appointments/make',
          normalizationContext: ['groups' => ['appointment:read']],
          denormalizationContext: ['groups' => ['appointment:create']],
          security : 'is_granted("IS_AUTHENTICATED_FULLY") and user.isClient()',
      ),
    ],
    normalizationContext: ['groups' => ['appointment:read']],
    openapiContext: [
        'summary' => "Create an appointment for a client in a vet's agenda in a specific date and time for a specific animal",
    ]
)]
#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['appointment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?string $note = null;

    #[ORM\Column]
    #[Groups(['appointment:read'])]
    private ?bool $isValidated = null;

    #[ORM\Column]
    #[Groups(['appointment:read', 'appointment:create'])]
    #[ApiProperty(default: false)]
    private ?bool $isUrgent = null;

    #[ORM\Column]
    #[Groups(['appointment:read', 'appointment:write'])]
    #[IsVet]
    #[ApiProperty(default: false)]
    private ?bool $isCompleted = null;

    #[ORM\OneToOne(inversedBy: 'appointment', cascade: ['persist', 'remove'])]
    #[IsVet]
    #[ApiProperty(default: null)]
    private ?Receipt $receipt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:create'])]
    #[ApiProperty(readableLink: true)]
    private ?TypeAppointment $type = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[Groups(['appointment:read'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?Veto $veto = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[Groups(['appointment:read', 'appointment:create'])]
    #[ApiProperty(readableLink: true)]
    private ?Address $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?\DateTimeInterface $startHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['appointment:read'])]
    private ?\DateTimeInterface $endHour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }

    public function isIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getType(): ?TypeAppointment
    {
        return $this->type;
    }

    public function setType(?TypeAppointment $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getVeto(): ?Veto
    {
        return $this->veto;
    }

    public function setVeto(?Veto $veto): self
    {
        $this->veto = $veto;

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

    public function isIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(\DateTimeInterface $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(\DateTimeInterface $endHour): self
    {
        $this->endHour = $endHour;

        return $this;
    }
}
