<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\GetMeAddressesController;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: 'email', message: 'Il y a déjà un compte avec cette adresse e-mail.')]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/me/addresses',
        controller: GetMeAddressesController::class,
        openapiContext: [
            'summary' => 'Get the addresses of the current client.',
            'description' => 'Get the addresses of the current client.',
            'responses' => [
                '200' => [
                    'description' => 'The addresses of the current client.',
                ],
                '401' => [
                    'description' => 'The user is not authenticated or not a client.',
                ],
            ],
        ],
        normalizationContext: ['groups' => ['address:read']],
        security: 'is_granted("IS_AUTHENTICATED_FULLY")'
    ),
    new Post(
        uriTemplate: '/register',
        openapiContext: [
            'summary' => 'Register a new client on the service.',
            'description' => 'Create a new account with a password and an email address and return the newly registered client.',
            'responses' => [
                '201' => [
                    'description' => 'The newly registered client.',
                ],
                '400' => [
                    'description' => 'The email address is already used by another account.',
                ],
            ],
        ],
        normalizationContext: ['skip_null_values' => false, 'groups' => ['user:read-me']],
        denormalizationContext: ['groups' => ['user:create']],
    ),
])]
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    /**
     * @var bool|null if the client is a husbandry or not
     */
    #[ORM\Column]
    #[Groups(['user:read-me', 'user:read', 'user:create'])]
    private ?bool $isHusbandry = null;

    /**
     * @var Collection the animals owned by the client
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Animal::class, cascade: ['remove'])]
    private Collection $animals;

    /**
     * @var Collection the adresses of the client
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Address::class)]
    private Collection $adresses;

    /**
     * @var Collection the appointments of the client
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        parent::__construct();
        $this->animals = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->isHusbandry = false;
    }

    public function isIsHusbandry(): ?bool
    {
        return $this->isHusbandry;
    }

    public function setIsHusbandry(bool $isHusbandry): self
    {
        $this->isHusbandry = $isHusbandry;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setOwner($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getOwner() === $this) {
                $animal->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Address $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setClient($this);
        }

        return $this;
    }

    public function removeAdress(Address $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getClient() === $this) {
                $adress->setClient(null);
            }
        }

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
            $appointment->setClient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getClient() === $this) {
                $appointment->setClient(null);
            }
        }

        return $this;
    }
}
