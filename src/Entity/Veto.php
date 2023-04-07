<?php

namespace App\Entity;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\VetoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(SearchFilter::class, properties: ['lastName' => 'partial', 'firstName' => 'partial'])]
#[ApiResource(
    operations: [
        new GetCollection(
            order: ['lastName' => 'ASC', 'firstName' => 'ASC'],
            normalizationContext: ['skip_null_values' => false, 'groups' => ['user:read', 'user:read-me', 'veto:read']],
        ),
        new Get(
            normalizationContext: ['groups' => ['user:read', 'user:read-me', 'veto:read']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY") and object == user',
            securityMessage: 'You can only access your own account.',
        ),
    ],
)]
#[ORM\Entity(repositoryClass: VetoRepository::class)]
class Veto extends User
{
    #[ORM\OneToMany(mappedBy: 'veto', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[Groups('veto:read')]
    #[ORM\OneToOne(inversedBy: 'veto', cascade: ['persist', 'remove'])]
    public ?Agenda $agenda = null;

    #[Groups('veto:read')]
    #[ApiProperty(readableLink: true)]
    #[ORM\ManyToMany(targetEntity: TypeAnimal::class, inversedBy: 'vetos')]
    private Collection $accepting;

    public function __construct()
    {
        parent::__construct();
        $this->appointments = new ArrayCollection();
        $this->accepting = new ArrayCollection();
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
            $appointment->setVeto($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getVeto() === $this) {
                $appointment->setVeto(null);
            }
        }

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

    public function getDisplayName(): string
    {
        return sprintf("Dr %s $this->firstName", strtoupper($this->lastName));
    }

    /**
     * @return Collection<int, TypeAnimal>
     */
    public function getAccepting(): Collection
    {
        return $this->accepting;
    }

    public function addAccepting(TypeAnimal $accepting): self
    {
        if (!$this->accepting->contains($accepting)) {
            $this->accepting->add($accepting);
        }

        return $this;
    }

    public function removeAccepting(TypeAnimal $accepting): self
    {
        $this->accepting->removeElement($accepting);

        return $this;
    }
}
