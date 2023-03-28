<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TypeAnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['typeAnimal:read']]),
        new GetCollection(
            normalizationContext: ['groups' => ['typeAnimal:read']],
        ),
    ]
)]
#[ORM\Entity(repositoryClass: TypeAnimalRepository::class)]
class TypeAnimal
{
    #[Groups(['typeAnimal:read', 'animal:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['typeAnimal:read', 'veto:read', 'animal:read'])]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Animal::class)]
    private Collection $animals;

    #[Groups(['typeAnimal:read', 'veto:read', 'animal:read'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $icon = null;

    #[ORM\ManyToMany(targetEntity: Veto::class, mappedBy: 'accepting')]
    private Collection $vetos;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->vetos = new ArrayCollection();
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
            $animal->setType($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getType() === $this) {
                $animal->setType(null);
            }
        }

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<int, Veto>
     */
    public function getVetos(): Collection
    {
        return $this->vetos;
    }

    public function addVeto(Veto $veto): self
    {
        if (!$this->vetos->contains($veto)) {
            $this->vetos->add($veto);
            $veto->addAccepting($this);
        }

        return $this;
    }

    public function removeVeto(Veto $veto): self
    {
        if ($this->vetos->removeElement($veto)) {
            $veto->removeAccepting($this);
        }

        return $this;
    }
}
