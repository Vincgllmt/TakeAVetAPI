<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\VacationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['vacation:read']]),
        new Post(
            openapiContext: [
                'summary' => 'Create a new vacation on your agenda (vet only).',
                'responses' => [
                    '201' => 'The vacation has been created.',
                    '400' => 'The vacation is invalid.',
                    '401' => 'You need to be authenticated as veto to create a vacation.',
                ],
            ],
            normalizationContext: ['groups' => ['vacation:read', 'agenda:read']],
            denormalizationContext: ['groups' => ['vacation:write', 'agenda:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()',
            securityMessage: 'You need to be a Veto to access this resource.',
            securityPostValidation: 'user.agenda !== null',
            securityPostValidationMessage: 'You don\'t have an agenda yet.',
        ),
    ]
)]
#[ORM\Entity(repositoryClass: VacationRepository::class)]
class Vacation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vacation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vacation:read', 'vacation:write'])]
    #[NotBlank(message: 'The lib field is required.')]
    private ?string $lib = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['vacation:read', 'vacation:write'])]
    #[LessThan(propertyPath: 'endDate', message: 'The start date must be before the end date.')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['vacation:read', 'vacation:write'])]
    #[GreaterThan(propertyPath: 'startDate', message: 'The end date must be after the start date.')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    #[Groups(['vacation:read'])]
    #[ApiProperty(readableLink: false)]
    private ?Agenda $agenda = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLib(): ?string
    {
        return $this->lib;
    }

    public function setLib(string $lib): self
    {
        $this->lib = $lib;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }
}
