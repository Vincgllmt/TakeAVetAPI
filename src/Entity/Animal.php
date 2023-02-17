<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get one animal',
            ],
            normalizationContext: ['groups' => ['animal:read']],
            security: 'object.owner == user'
        ),
        new Post(
            openapiContext: [
                'summary' => 'Create a new animal',
            ],
            denormalizationContext: ['groups' => ['animal:create']]
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update an animal',
            ],
            denormalizationContext: ['groups' => ['animal:write']],
            security: 'object.owner == user'
        ),
        new Put(
            openapiContext: [
                'summary' => 'Replace an animal',
            ],
            denormalizationContext: ['groups' => ['animal:create', 'animal:write']],
            security: 'object.owner == user'
        ),
    ]
)]
#[UniqueEntity(fields: ['id'], message: 'An animal with this id already exists.')]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $note = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $race = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 50)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $gender = null;

    #[ORM\Column]
    #[Groups(['animal:read'])]
    private ?bool $isDomestic = null;

    #[ORM\ManyToMany(targetEntity: Vaccine::class)]
    private Collection $vaccines;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?CategoryAnimal $CategoryAnimal = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Client $ClientAnimal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPath = null;

    #[ORM\OneToMany(mappedBy: 'Animal', targetEntity: AnimalRecord::class)]
    private Collection $animalRecords;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->vaccines = new ArrayCollection();
        $this->animalRecords = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function isIsDomestic(): ?bool
    {
        return $this->isDomestic;
    }

    public function setIsDomestic(bool $isDomestic): self
    {
        $this->isDomestic = $isDomestic;

        return $this;
    }

    /**
     * @return Collection<int, Vaccine>
     */
    public function getVaccines(): Collection
    {
        return $this->vaccines;
    }

    public function addVaccine(Vaccine $vaccine): self
    {
        if (!$this->vaccines->contains($vaccine)) {
            $this->vaccines->add($vaccine);
        }

        return $this;
    }

    public function removeVaccine(Vaccine $vaccine): self
    {
        $this->vaccines->removeElement($vaccine);

        return $this;
    }

    public function getCategoryAnimal(): ?CategoryAnimal
    {
        return $this->CategoryAnimal;
    }

    public function setCategoryAnimal(?CategoryAnimal $CategoryAnimal): self
    {
        $this->CategoryAnimal = $CategoryAnimal;

        return $this;
    }

    public function getClientAnimal(): ?Client
    {
        return $this->ClientAnimal;
    }

    public function setClientAnimal(?Client $ClientAnimal): self
    {
        $this->ClientAnimal = $ClientAnimal;

        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    public function getDisplayName(): string
    {
        $category = $this->CategoryAnimal?->getName();

        return "$this->name".' '."$category";
    }

    /**
     * @return Collection<int, AnimalRecord>
     */
    public function getAnimalRecords(): Collection
    {
        return $this->animalRecords;
    }

    public function addAnimalRecord(AnimalRecord $animalRecord): self
    {
        if (!$this->animalRecords->contains($animalRecord)) {
            $this->animalRecords->add($animalRecord);
            $animalRecord->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalRecord(AnimalRecord $animalRecord): self
    {
        if ($this->animalRecords->removeElement($animalRecord)) {
            // set the owning side to null (unless already changed)
            if ($animalRecord->getAnimal() === $this) {
                $animalRecord->setAnimal(null);
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
            $appointment->setAnimal($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getAnimal() === $this) {
                $appointment->setAnimal(null);
            }
        }

        return $this;
    }
}
