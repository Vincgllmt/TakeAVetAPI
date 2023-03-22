<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\VacationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/vacations/from/{agendaId}/',
            uriVariables: [
                'agendaId' => new Link(fromProperty: 'id', toProperty: 'agenda', fromClass: Agenda::class),
            ],
            openapiContext: [
                'summary' => 'Get all vacations of a given agenda.',
                'description' => 'Return all vacations of a given agenda.',
                'responses' => [
                    '200' => [
                        'description' => 'All vacations of a given agenda.',
                    ],
                    '404' => [
                        'description' => 'The agenda does not exist.',
                    ],
                ],
            ],
            paginationEnabled: false,
            normalizationContext: ['groups' => ['vacation:read']],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Get a vacation ressource by its id.',
                'responses' => [
                    '200' => [
                        'description' => 'The vacation has been retrieved.',
                    ],
                    '404' => [
                        'description' => 'The vacation does not exist.',
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['vacation:read']]
        ),
        new Post(
            openapiContext: [
                'summary' => 'Create a new vacation on your agenda (vet only).',
                'responses' => [
                    '201' => [
                        'description' => 'The vacation has been created.',
                    ],
                    '400' => [
                        'description' => 'The vacation is invalid.',
                    ],
                    '401' => [
                        'description' => 'You need to be authenticated as a veto to create a vacation.',
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['vacation:read', 'agenda:read']],
            denormalizationContext: ['groups' => ['vacation:write', 'agenda:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto()',
            securityMessage: 'You need to be a Veto to access this resource.',
            securityPostValidation: 'user.agenda !== null',
            securityPostValidationMessage: 'You don\'t have an agenda yet.',
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Delete a vacation on your agenda (vet only).',
                'responses' => [
                    '204' => [
                        'description' => 'The vacation has been deleted.',
                    ],
                    '401' => [
                        'description' => 'You need to be authenticated as a veto and the owner to delete a vacation.',
                    ],
                ],
            ],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto() and user.agenda === object.agenda',
            securityMessage: 'You need to be a Veto and the owner of the agenda to access this resource.'
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update a vacation on your agenda (vet only).',
                'responses' => [
                    '200' => [
                        'description' => 'The vacation has been updated.',
                    ],
                    '400' => [
                        'description' => 'The vacation is invalid.',
                    ],
                    '401' => [
                        'description' => 'You need to be authenticated as a veto and the owner to update a vacation.',
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['vacation:read', 'agenda:read']],
            denormalizationContext: ['groups' => ['vacation:write', 'agenda:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto() and user.agenda === object.agenda',
            securityMessage: 'You need to be a Veto and the owner of the agenda to access this resource.'
        ),
        new Put(
            openapiContext: [
                'summary' => 'Replace a vacation on your agenda (vet only).',
                'responses' => [
                    '200' => [
                        'description' => 'The vacation has been updated.',
                    ],
                    '400' => [
                        'description' => 'The vacation is invalid.',
                    ],
                    '401' => [
                        'description' => 'You need to be authenticated as a veto and the owner to update a vacation.',
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['vacation:read', 'agenda:read']],
            denormalizationContext: ['groups' => ['vacation:write', 'agenda:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isVeto() and user.agenda === object.agenda',
            securityMessage: 'You need to be a Veto and the owner of the agenda to access this resource.'
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
    public ?Agenda $agenda = null;

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
