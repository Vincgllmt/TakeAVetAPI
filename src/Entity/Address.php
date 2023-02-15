<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: '`address`')]
#[ApiResource(
    operations: [
        new Get(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['get_address']]
)]
#[Get]
#[Patch(
    normalizationContext: ['groups' => ['set_address']],
    security: 'object.owner == user'
)]
#[Put(
    normalizationContext: ['groups' => ['set_address']],
    security: 'object.owner == user'
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_address', 'set_address'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['get_address', 'set_address'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_address', 'set_address'])]
    private ?string $ad = null;

    #[ORM\Column(length: 5)]
    #[Groups(['get_address', 'set_address'])]
    private ?string $pc = null;

    #[ORM\Column(length: 50)]
    #[Groups(['get_address', 'set_address'])]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[Groups(['get_address', 'set_address'])]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAd(): ?string
    {
        return $this->ad;
    }

    public function setAd(string $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getPc(): ?string
    {
        return $this->pc;
    }

    public function setPc(string $pc): self
    {
        $this->pc = $pc;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getDisplayName(): string
    {
        return "$this->ad, $this->city, $this->pc ($this->name)";
    }
}
