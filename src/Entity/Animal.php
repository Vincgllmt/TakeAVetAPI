<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
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
        ),
        new GetCollection(
            openapiContext: [
                'summary' => 'Get all animals',
            ]
        ),
        new Post(
            openapiContext: [
                'summary' => 'Create a new animal',
            ],
            denormalizationContext: ['groups' => ['animal:create']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update an animal',
            ],
            denormalizationContext: ['groups' => ['animal:write']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object.owner == user'
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

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $note = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $specificRace = null;

    #[ORM\Column(length: 50)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['animal:read', 'animal:create', 'animal:write'])]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column]
    private ?bool $inFarm = null;

    #[ORM\Column]
    private ?bool $isGroup = null;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: AnimalRecord::class)]
    private Collection $records;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?TypeAnimal $type = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    public ?Client $owner = null;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Vaccine::class)]
    private Collection $vaccines;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: MediaObject::class)]
    private Collection $images;

    public function __construct()
    {
        $this->records = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->vaccines = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getSpecificRace(): ?string
    {
        return $this->specificRace;
    }

    public function setSpecificRace(?string $specificRace): self
    {
        $this->specificRace = $specificRace;

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

    public function getType(): ?TypeAnimal
    {
        return $this->type;
    }

    public function setType(?TypeAnimal $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?Client
    {
        return $this->owner;
    }

    public function setOwner(?Client $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDisplayName(): string
    {
        $category = $this->type?->getName();

        return "$this->name".' '."$category";
    }

    /**
     * @return Collection<int, AnimalRecord>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addAnimalRecord(AnimalRecord $animalRecord): self
    {
        if (!$this->records->contains($animalRecord)) {
            $this->records->add($animalRecord);
            $animalRecord->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalRecord(AnimalRecord $animalRecord): self
    {
        if ($this->records->removeElement($animalRecord)) {
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

    public function isInFarm(): ?bool
    {
        return $this->inFarm;
    }

    public function setInFarm(bool $inFarm): self
    {
        $this->inFarm = $inFarm;

        return $this;
    }

    public function isIsGroup(): ?bool
    {
        return $this->isGroup;
    }

    public function setIsGroup(bool $isGroup): self
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function addRecord(AnimalRecord $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setAnimal($this);
        }

        return $this;
    }

    public function removeRecord(AnimalRecord $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getAnimal() === $this) {
                $record->setAnimal(null);
            }
        }

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
            $vaccine->setAnimal($this);
        }

        return $this;
    }

    public function removeVaccine(Vaccine $vaccine): self
    {
        if ($this->vaccines->removeElement($vaccine)) {
            // set the owning side to null (unless already changed)
            if ($vaccine->getAnimal() === $this) {
                $vaccine->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(MediaObject $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(MediaObject $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }
}
