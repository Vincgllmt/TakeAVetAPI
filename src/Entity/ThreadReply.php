<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\ThreadReplyRepository;
use App\Validator\IsAuthenticatedUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThreadReplyRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            openapiContext: [
                'summary' => 'Get one reply to a thread',
            ],
            normalizationContext: ['groups' => ['threadReply:read']]
        ),
        new GetCollection(
            uriTemplate: '/thread_replies/from/{threadId}',
            uriVariables: [
                'threadId' => new Link(fromProperty: 'id', toProperty: 'thread', fromClass: Thread::class),
            ],
            openapiContext: ['summary' => 'Get all replies from a thread'],
        ),
        new Delete(
            openapiContext: ['summary' => 'delete your reply in a thread (admin can delete any reply)'],
            security: "is_granted('IS_AUTHENTICATED_FULLY') and (object.user === user or is_granted('ROLE_ADMIN'))"
        ),
         new Post(
             openapiContext: ['summary' => 'Create a new reply'],
             normalizationContext: ['groups' => ['threadReply:read']],
             denormalizationContext: ['groups' => ['threadReply:create']],
             security: "is_granted('IS_AUTHENTICATED_FULLY')"
         ),
    ],
)]
class ThreadReply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('threadReply:read')]
    private ?int $id = null;

    #[ORM\Column(length: 1024)]
    #[Groups(['threadReply:read', 'threadReply:create'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('threadReply:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'author')]
    #[ORM\JoinColumn(nullable: false)]
    #[IsAuthenticatedUser]
    public ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'replies')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('threadReply:create')]
    private ?Thread $thread = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }
}
