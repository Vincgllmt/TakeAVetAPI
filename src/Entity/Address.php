<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\CreateAddressForClient;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: '`address`')]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['address:read']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and (object.client === user or user.isVeto())',
        ),
        new Put(
            normalizationContext: ['groups' => ['address:read']],
            denormalizationContext: ['groups' => ['address:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object.client === user',
        ),
        new Patch(
            normalizationContext: ['groups' => ['address:read']],
            denormalizationContext: ['groups' => ['address:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object.client === user',
        ),
        new Delete(
            denormalizationContext: ['groups' => ['address:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object.client === user',
        ),
        new Post(
            controller: CreateAddressForClient::class,
            openapiContext: [
                'summary' => 'Create a new address for the current client.',
                'description' => 'Create a new address for the current client.',
                'responses' => [
                    '201' => [
                        'description' => 'The newly created address.',
                    ],
                    '400' => [
                        'description' => 'The address is invalid.',
                    ],
                    '401' => [
                        'description' => 'The user is not authenticated or not a client.',
                    ],
                ],
            ],
            normalizationContext: ['groups' => ['address:read']],
            denormalizationContext: ['groups' => ['address:create']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and user.isClient()',
        ),
    ],
)]
class Address
{
    /**
     * @var int|null The address' ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['address:read', 'address:info'])]
    private ?int $id = null;

    /**
     * @var string|null The address' name
     */
    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['address:read', 'address:write', 'address:create'])]
    #[Length(max: 50, maxMessage: 'Le nom de l\'adresse ne peut pas dépasser 50 caractères.')]
    private ?string $name = null;

    /**
     * @var string|null The entire address
     */
    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:write', 'address:create', 'address:info'])]
    #[Length(max: 255, maxMessage: 'L\'adresse ne peut pas dépasser 255 caractères.')]
    private ?string $ad = null;

    /**
     * @var string|null The postal code
     */
    #[ORM\Column(length: 5)]
    #[Groups(['address:read', 'address:write', 'address:create', 'address:info'])]
    #[Length(max: 5, maxMessage: 'Le code postal ne peut pas dépasser 5 caractères.')]
    #[Regex('/[A-Z0-9]{5}/', message: 'Le code postal doit être composé de 5 chiffres ou lettres majuscules.')]
    private ?string $pc = null;

    /**
     * @var string|null The city of the address
     */
    #[ORM\Column(length: 50)]
    #[Groups(['address:read', 'address:write', 'address:create', 'address:info'])]
    #[Length(max: 50, maxMessage: 'Le nom de la ville ne peut pas dépasser 50 caractères.')]
    private ?string $city = null;

    /**
     * @var Client|null The client using this address
     */
    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[Groups(['address:read', 'address:write'])]
    public ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Appointment::class)]
    #[Ignore]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    #[Ignore]
    public function getDisplayName(): string
    {
        return "$this->ad, $this->city, $this->pc ($this->name)";
    }

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

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setLocation($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getLocation() === $this) {
                $appointment->setLocation(null);
            }
        }

        return $this;
    }
}
