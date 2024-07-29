<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    /**
     * @var Collection<int, UserCardStatus>
     */
    #[ORM\OneToMany(targetEntity: UserCardStatus::class, mappedBy: 'user')]
    private Collection $userCardStatuses;

    /**
     * @var Collection<int, FolderStatus>
     */
    #[ORM\OneToMany(targetEntity: FolderStatus::class, mappedBy: 'user')]
    private Collection $folderStatuses;

    public function __construct()
    {
        $this->userCardStatuses = new ArrayCollection();
        $this->folderStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection<int, UserCardStatus>
     */
    public function getUserCardStatuses(): Collection
    {
        return $this->userCardStatuses;
    }

    public function addUserCardStatus(UserCardStatus $userCardStatus): static
    {
        if (!$this->userCardStatuses->contains($userCardStatus)) {
            $this->userCardStatuses->add($userCardStatus);
            $userCardStatus->setUser($this);
        }

        return $this;
    }

    public function removeUserCardStatus(UserCardStatus $userCardStatus): static
    {
        if ($this->userCardStatuses->removeElement($userCardStatus)) {
            // set the owning side to null (unless already changed)
            if ($userCardStatus->getUser() === $this) {
                $userCardStatus->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FolderStatus>
     */
    public function getFolderStatuses(): Collection
    {
        return $this->folderStatuses;
    }

    public function addFolderStatus(FolderStatus $folderStatus): static
    {
        if (!$this->folderStatuses->contains($folderStatus)) {
            $this->folderStatuses->add($folderStatus);
            $folderStatus->setUser($this);
        }

        return $this;
    }

    public function removeFolderStatus(FolderStatus $folderStatus): static
    {
        if ($this->folderStatuses->removeElement($folderStatus)) {
            // set the owning side to null (unless already changed)
            if ($folderStatus->getUser() === $this) {
                $folderStatus->setUser(null);
            }
        }

        return $this;
    }
}
