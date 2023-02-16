<?php

namespace App\Entity;

use App\Repository\ReceiptRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceiptRepository::class)]
class Receipt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $receiptAt = null;

    #[ORM\Column]
    private ?float $vat = null;

    #[ORM\OneToOne(mappedBy: 'receipt', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getReceiptAt(): ?\DateTimeInterface
    {
        return $this->receiptAt;
    }

    public function setReceiptAt(\DateTimeInterface $receiptAt): self
    {
        $this->receiptAt = $receiptAt;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): self
    {
        // unset the owning side of the relation if necessary
        if (null === $appointment && null !== $this->appointment) {
            $this->appointment->setReceipt(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $appointment && $appointment->getReceipt() !== $this) {
            $appointment->setReceipt($this);
        }

        $this->appointment = $appointment;

        return $this;
    }
}
