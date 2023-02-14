<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discriminator', type: 'string')]
#[DiscriminatorMap(['veto' => Veto::class, 'client' => Client::class])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse e-mail.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null the id of this user
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'user:read-me'])]
    protected ?int $id = null;

    /**
     * @var string|null the email of this user
     */
    #[ORM\Column(length: 180, unique: true)]
    #[Email]
    #[Groups(['user:create', 'user:read-me'])]
    protected ?string $email = null;

    #[ORM\Column]
    protected array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    #[Length(min: 6, minMessage: 'Your password should be at least 6 characters long')]
    #[Groups(['user:create'])]
    protected ?string $password = null;

    /**
     * @var string|null the last name of this user
     */
    #[ORM\Column(length: 50)]
    #[Groups(['user:create', 'user:read-me'])]
    protected ?string $lastName = null;

    /**
     * @var string|null the first name of this user
     */
    #[ORM\Column(length: 50)]
    #[Groups(['user:create', 'user:read-me'])]
    protected ?string $firstName = null;

    /**
     * @var string|null the phone number of this user
     */
    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['user:read-me'])]
    protected ?string $tel = null;

    /**
     * @var Collection threads created by this user
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Thread::class, orphanRemoval: true)]
    protected Collection $threads;

    /**
     * @var Collection messages created by this user
     */
    #[ORM\OneToMany(mappedBy: 'User', targetEntity: ThreadMessage::class)]
    protected Collection $author;

    /**
     * @var string|null the path to the profile picture of this user
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicPath = null;

    public function __construct()
    {
        $this->threads = new ArrayCollection();
        $this->author = new ArrayCollection();
    }

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Thread>
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread): self
    {
        if (!$this->threads->contains($thread)) {
            $this->threads->add($thread);
            $thread->setAuthor($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread): self
    {
        if ($this->threads->removeElement($thread)) {
            // set the owning side to null (unless already changed)
            if ($thread->getAuthor() === $this) {
                $thread->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ThreadMessage>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(ThreadMessage $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->setUser($this);
        }

        return $this;
    }

    public function removeAuthor(ThreadMessage $author): self
    {
        if ($this->author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getUser() === $this) {
                $author->setUser(null);
            }
        }

        return $this;
    }

    public function isVeto(): bool
    {
        return $this instanceof Veto;
    }

    public function isClient(): bool
    {
        return $this instanceof Client;
    }

    public function getProfilePicPath(): ?string
    {
        return $this->profilePicPath;
    }

    public function setProfilePicPath(?string $profilePicPath): self
    {
        $this->profilePicPath = $profilePicPath;

        return $this;
    }
}
