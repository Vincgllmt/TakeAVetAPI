<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\UnavailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/unavailabilities/from/{agendaId}/',
            uriVariables: [
                'agendaId' => new Link(fromProperty: 'id', toProperty: 'agenda', fromClass: Agenda::class),
            ],
            openapiContext: [
                'summary' => 'Get all unavailabilities of a given agenda.',
                'description' => 'Return all unavailabilities of a given agenda.',
                'responses' => [
                    '200' => [
                        'description' => 'All unavailabilities of a given agenda.',
                    ],
                    '404' => [
                        'description' => 'The agenda does not exist.',
                    ],
                ],
            ],
            paginationEnabled: false,
            normalizationContext: ['groups' => ['unavailability:read']],
        ),
        new Get(normalizationContext: ['groups' => ['unavailability:read']]),
    ]
)]
#[ORM\Entity(repositoryClass: UnavailabilityRepository::class)]
class Unavailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['unavailability:read'])]
    private ?int $id = null;

//    #[ORM\Column]
//    private ?bool $isRepeated = null;

    #[ORM\ManyToOne(inversedBy: 'unavailabilities')]
    #[Groups(['unavailability:read'])]
    private ?Agenda $agenda = null;

    #[ORM\Column(length: 30)]
    #[Groups(['unavailability:read'])]
    private ?string $lib = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['unavailability:read'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['unavailability:read'])]
    private ?\DateTimeInterface $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

//    public function isIsRepeated(): ?bool
//    {
//        return $this->isRepeated;
//    }
//
//    public function setIsRepeated(bool $isRepeated): self
//    {
//        $this->isRepeated = $isRepeated;
//
//        return $this;
//    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
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
}
