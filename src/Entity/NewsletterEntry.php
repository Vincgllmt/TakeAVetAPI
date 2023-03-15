<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\NewsletterEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\Email;

#[ApiResource(
    operations: [
        new Post('/newsletter',
            normalizationContext: ['groups' => ['newsletter:read']],
            denormalizationContext: ['groups' => ['newsletter:create']]),
    ])]
#[ORM\Entity(repositoryClass: NewsletterEntryRepository::class)]
class NewsletterEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('newsletter:read')]
    private ?int $id = null;

    #[Groups('newsletter:create')]
    #[Email]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
