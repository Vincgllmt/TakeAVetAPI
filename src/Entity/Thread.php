<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[UniqueEntity(fields: ['lib'], message: 'Il y a déjà un sujet avec ce titre.')]
#[UniqueEntity(fields: ['id'], message: 'Il y a déjà un sujet avec cet identifiant.')]
#[ApiResource(operations: [
    new GetCollection(
        openapiContext: [
            'summary' => 'Get all threads',
        ],
        normalizationContext: ['groups' => ['thread:read', 'user:read']],
    ),
    new Get(
        openapiContext: [
            'summary' => 'Get a thread',
        ],
        normalizationContext: ['groups' => ['user:read', 'thread:read-all', 'thread:read']],
    ),
])]
class Thread
{
    /**
     * @var int|null the id of this thread
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['thread:read'])]
    private ?int $id = null;

    /**
     * @var string|null the title of this thread (question)
     */
    #[ORM\Column(length: 255)]
    #[Groups(['thread:read'])]
    private ?string $lib = null;

    /**
     * @var \DateTimeImmutable|null the date of creation of this thread
     */
    #[ORM\Column]
    #[Groups(['thread:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var string|null the message of this thread (explanation)
     */
    #[ORM\Column(length: 1024)]
    #[Groups(['thread:read'])]
    private ?string $message = null;

    /**
     * @var User|null the author of this thread
     */
    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['thread:read'])]
    private ?User $author = null;

    /**
     * @var Collection<int, ThreadMessage> the replies to this thread
     */
    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: ThreadMessage::class)]
    #[Groups(['thread:read-all'])]
    private Collection $replies;

    /**
     * @var bool|null true if the thread is resolved, false if it is not.
     */
    #[ORM\Column]
    #[Groups(['thread:read'])]
    private ?bool $resolved = null;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLib(): ?string
    {
        return $this->lib;
    }

    public function setLib(string $lib): self
    {
        $this->lib = $lib;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, ThreadMessage>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(ThreadMessage $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setThread($this);
        }

        return $this;
    }

    public function removeReply(ThreadMessage $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getThread() === $this) {
                $reply->setThread(null);
            }
        }

        return $this;
    }

    public function isResolved(): ?bool
    {
        return $this->resolved;
    }

    public function setResolved(bool $resolved): self
    {
        $this->resolved = $resolved;

        return $this;
    }
}
