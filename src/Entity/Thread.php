<?php

namespace App\Entity;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[UniqueEntity(fields: ['subject'], message: 'Il y a déjà un sujet avec ce titre.')]
#[UniqueEntity(fields: ['id'], message: 'Il y a déjà un sujet avec cet identifiant.')]
#[ApiFilter(SearchFilter::class, properties: ['author' => 'exact', 'subject' => 'partial', 'description' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['resolved'])]
#[ApiResource(
    operations: [
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
        new Post(
            openapiContext: [
                'summary' => 'Create a thread',
            ],
            normalizationContext: ['groups' => ['user:read', 'thread:read-all', 'thread:read']],
            denormalizationContext: ['groups' => ['thread:create']],
        ),
        new Patch(
            openapiContext: [
                'summary' => 'Update a thread',
            ],
            normalizationContext: ['groups' => ['user:read', 'thread:read-all', 'thread:read']],
            denormalizationContext: ['groups' => ['thread:write']],
        ),
        new Put(
            openapiContext: [
                'summary' => 'Replace a thread',
            ],
            normalizationContext: ['groups' => ['user:read', 'thread:read-all', 'thread:read']],
            denormalizationContext: ['groups' => ['thread:create', 'thread:write']],
        ),
        new Delete(
            openapiContext: [
                'summary' => 'Delete a thread (admin only)',
            ],
            security: 'is_granted("ROLE_ADMIN")',
        ),
    ],
    order: ['createdAt' => 'DESC']
)]
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
    #[Groups(['thread:read', 'thread:create', 'thread:write'])]
    private ?string $subject = null;

    /**
     * @var \DateTimeImmutable|null the date of creation of this thread
     */
    #[ORM\Column]
    #[Groups(['thread:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var string|null the description of this thread (explanation)
     */
    #[ORM\Column(length: 1024)]
    #[Groups(['thread:read', 'thread:create', 'thread:write'])]
    private ?string $description = null;

    /**
     * @var User|null the author of this thread
     */
    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['thread:read'])]
    private ?User $author = null;

    /**
     * @var Collection<int, ThreadReply> the replies to this thread
     */
    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: ThreadReply::class)]
    #[Groups(['thread:read-all'])]
    private Collection $replies;

    /**
     * @var bool|null true if the thread is resolved, false if it is not
     */
    #[ORM\Column]
    #[Groups(['thread:read', 'thread:write'])]
    private ?bool $resolved = null;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Collection<int, ThreadReply>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(ThreadReply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setThread($this);
        }

        return $this;
    }

    public function removeReply(ThreadReply $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getThread() === $this) {
                $reply->setThread(null);
            }
        }

        return $this;
    }

    #[Groups(['thread:read'])]
    public function getReplyCount(): int
    {
        return $this->replies->count();
    }
}
