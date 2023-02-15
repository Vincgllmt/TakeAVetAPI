<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\AnimalRecordRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalRecordRepository::class)]
#[ORM\Table(name: '`animalRecord`')]
#[ApiResource(
    operations: [
        new Get(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['get_animalRecord']]
)]
#[Get]
#[Patch(
    normalizationContext: ['groups' => ['set_animalRecord']],
    security: 'object.owner == user'
)]
#[Put(
    normalizationContext: ['groups' => ['set_animalRecord']],
    security: 'object.owner == user'
)]
class AnimalRecord
{
    /**
     * @var int|null The record's ID
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?int $id = null;

    /**
     * @var float|null The animal's weight
     */
    #[ORM\Column]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?float $weight = null;

    /**
     * @var float|null The animal's height
     */
    #[ORM\Column]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?float $height = null;

    /**
     * @var string|null Other information concerning the animal
     */
    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?string $otherInfos = null;

    /**
     * @var string|null Health information concerning the animal
     */
    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?string $healthInfos = null;

    /**
     * @var DateTimeInterface|null The date at which the record was modified last
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?DateTimeInterface $dateRecord = null;

    /**
     * @var Animal|null The Animal defined in the record
     */
    #[ORM\ManyToOne(inversedBy: 'animalRecords')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['get_animalRecord', 'set_animalRecord'])]
    private ?Animal $Animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getOtherInfos(): ?string
    {
        return $this->otherInfos;
    }

    public function setOtherInfos(?string $otherInfos): self
    {
        $this->otherInfos = $otherInfos;

        return $this;
    }

    public function getHealthInfos(): ?string
    {
        return $this->healthInfos;
    }

    public function setHealthInfos(?string $healthInfos): self
    {
        $this->healthInfos = $healthInfos;

        return $this;
    }

    public function getDateRecord(): ?DateTimeInterface
    {
        return $this->dateRecord;
    }

    public function setDateRecord(DateTimeInterface $dateRecord): self
    {
        $this->dateRecord = $dateRecord;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->Animal;
    }

    public function setAnimal(?Animal $Animal): self
    {
        $this->Animal = $Animal;

        return $this;
    }
}
