<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgendaRepository::class)]
class Agenda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'agenda', targetEntity: Unavailability::class, cascade: ['persist', 'remove'])]
    private Collection $unavailabilities;

    #[ORM\OneToMany(mappedBy: 'agenda', targetEntity: Vacation::class, cascade: ['persist', 'remove'])]
    private Collection $vacations;

//    #[ORM\OneToMany(mappedBy: 'agenda', targetEntity: AgendaDay::class, cascade: ['persist', 'remove'])]
//    private Collection $days;

    #[ORM\OneToOne(mappedBy: 'agenda')]
    private ?Veto $veto = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endHour = null;

    public function __construct()
    {
        $this->unavailabilities = new ArrayCollection();
        $this->vacations = new ArrayCollection();
    }
//
//    /**
//     * @throws NonUniqueResultException
//     */
//    public function canTakeAt(\DateTime $dateTime, AgendaDayRepository $agendaDayRepository, TypeAppointment $appointmentType): bool
//    {
//        // to convert this $dateTime to a number from 1 to 7.
//        $dayNumber = self::getDayNumberFromDateTime($dateTime);
//
//        // null: Vet is not working this day or $dateTime is not valid, not null: OK
//        $agendaDay = $agendaDayRepository->findAndCheckAt($dayNumber, $this, $dateTime, $appointmentType->getDuration());
//
//        // the appointment can be taken on this day at this time for this vet (with no verification on the already existing appointments)
//        return null !== $agendaDay;
//    }
//
//    /**
//     * For php sunday is 0, so this convert from 1 for Monday to 7 for sunday.
//     *
//     * @param \DateTime $dateTime the startDatetime
//     */
//    private static function getDayNumberFromDateTime(\DateTime $dateTime): int
//    {
//        $dayNumber = (int) $dateTime->format('w');
//
//        return 0 == $dayNumber ? 7 : $dayNumber;
//    }

public function getId(): ?int
{
    return $this->id;
}

public function getStartHour(): ?\DateTimeInterface
{
    return $this->startHour;
}

public function setStartHour(\DateTimeInterface $startHour): self
{
    $this->startHour = $startHour;

    return $this;
}

public function getEndHour(): ?\DateTimeInterface
{
    return $this->endHour;
}

public function setEndHour(\DateTimeInterface $endHour): self
{
    $this->endHour = $endHour;

    return $this;
}

/**
 * @return Collection<int, Unavailability>
 */
public function getUnavailabilities(): Collection
{
    return $this->unavailabilities;
}

public function addUnavailability(Unavailability $unavailability): self
{
    if (!$this->unavailabilities->contains($unavailability)) {
        $this->unavailabilities->add($unavailability);
        $unavailability->setAgenda($this);
    }

    return $this;
}

public function removeUnavailability(Unavailability $unavailability): self
{
    if ($this->unavailabilities->removeElement($unavailability)) {
        // set the owning side to null (unless already changed)
        if ($unavailability->getAgenda() === $this) {
            $unavailability->setAgenda(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, Vacation>
 */
public function getVacations(): Collection
{
    return $this->vacations;
}

public function addVacation(Vacation $vacation): self
{
    if (!$this->vacations->contains($vacation)) {
        $this->vacations->add($vacation);
        $vacation->setAgenda($this);
    }

    return $this;
}

public function removeVacation(Vacation $vacation): self
{
    if ($this->vacations->removeElement($vacation)) {
        // set the owning side to null (unless already changed)
        if ($vacation->getAgenda() === $this) {
            $vacation->setAgenda(null);
        }
    }

    return $this;
}

public function getVeto(): ?Veto
{
    return $this->veto;
}

public function setVeto(?Veto $veto): self
{
    // unset the owning side of the relation if necessary
    if (null === $veto && null !== $this->veto) {
        $this->veto->setAgenda(null);
    }

    // set the owning side of the relation if necessary
    if (null !== $veto && $veto->getAgenda() !== $this) {
        $veto->setAgenda($this);
    }

    $this->veto = $veto;

    return $this;
}
}
