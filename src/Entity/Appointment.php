<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\GetAppointmentOnCurrentDayForVetoAction;
use App\Controller\GetAppointmentOnCurrentHourForVetoAction;
use App\Controller\GetMeAppointmentsAction;
use App\Repository\AppointmentRepository;
use App\Validator\NoAppointmentAtTheSameTime;
use App\Validator\NoUnavailabilityOnThisDatetime;
use App\Validator\NoVacationOnThisDate;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get one Appointment',
            ],
            normalizationContext: ['groups' => ['appointment:read', 'typeAppointment:read']]
        ),
        new GetCollection(
            uriTemplate: '/appointments/current/hour',
            controller: GetAppointmentOnCurrentHourForVetoAction::class,
            openapiContext: [
                'summary' => 'Get one appointment if there are any on current hour',
                'description' => 'If there are no appointment on current hour, return null',
            ],
            normalizationContext: ['groups' => ['appointment:read', 'typeAppointment:read', 'user:read', 'animal:read']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()'
        ),
        new GetCollection(
            uriTemplate: '/me/appointments',
            controller: GetMeAppointmentsAction::class,
            openapiContext: [
                'summary' => 'Get all Appointment for current user',
                'parameters' => [
                    [
                        'name' => 'show_validated',
                        'in' => 'query',
                        'description' => 'Show validated appointments',
                        'required' => false,
                        'schema' => [
                            'type' => 'boolean',
                            'format' => 'boolean',
                        ],
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['typeAppointment:read', 'user:read', 'user:phone', 'appointment:read', 'animal:read', 'address:info']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
//        new GetCollection(
//            openapiContext: [
//                'summary' => 'Get all Appointment for a client',
//            ],
//            normalizationContext: ['groups' => ['appointment:read-all']]
//        ),
        new GetCollection(
            uriTemplate: '/appointments/current/day',
            controller: GetAppointmentOnCurrentDayForVetoAction::class,
            openapiContext: [
                'summary' => 'Get all Appointment on current day (including completed)',
            ],
            normalizationContext: ['groups' => ['appointment:read', 'typeAppointment:read', 'user:read', 'animal:read']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()'
        ),
        new Post(
            openapiContext: [
                'summary' => 'Take an appointment (for a client)',
            ],
            normalizationContext: ['groups' => ['appointment:read', 'typeAppointment:read']],
            denormalizationContext: ['groups' => ['appointment:create']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isClient()',
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update an appointment',
            ],
            normalizationContext: ['groups' => ['appointment:read']],
            denormalizationContext: ['groups' => ['appointment:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto() and object.veto === user',
            validate: false,
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Delete an appointment',
            ],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object.client = user or user.isVeto()'
        ),
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
    #[Length(max: 1024)]
    #[Groups(['appointment:read', 'appointment:write', 'appointment:create'])]
    private ?string $note = null;

    #[ORM\Column]
    #[Groups(['appointment:read', 'appointment:write'])]
    private ?bool $isValidated = null;

    #[ORM\Column]
    #[Groups(['appointment:read', 'appointment:update', 'appointment:create'])]
    private ?bool $isUrgent = null;

    #[ORM\Column]
    #[Groups(['appointment:read', 'appointment:update'])]
    private ?bool $isCompleted = null;

    #[ORM\OneToOne(inversedBy: 'appointment', cascade: ['persist', 'remove'])]
    #[Groups(['appointment:read'])]
    private ?Receipt $receipt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: true)]
    #[Groups(['appointment:read', 'appointment:create'])]
    private ?TypeAppointment $type = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read'])]
    public ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: true)]
    #[Groups(['appointment:create', 'appointment:read'])]
    public ?Veto $veto = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'appointment:create'])]
    #[ApiProperty(readableLink: true)]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[Groups(['appointment:create', 'appointment:read'])]
    #[ApiProperty(readableLink: true)]
    private ?Address $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NoAppointmentAtTheSameTime]
    #[NoVacationOnThisDate]
    #[NoUnavailabilityOnThisDatetime]
    #[Groups(['appointment:read', 'appointment:write', 'appointment:create'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\LessThan(propertyPath: 'endHour', message: 'The start hour must be before the end hour')]
    #[Groups(['appointment:read', 'appointment:write', 'appointment:create'])]
    private ?\DateTimeInterface $startHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['appointment:read', 'appointment:write'])]
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
