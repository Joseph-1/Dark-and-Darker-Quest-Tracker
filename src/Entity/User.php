<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity('nickname')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLES = [
        'ROLE_USER' => 'User',
        'ROLE_CONTRIBUTOR' => 'Contributor',
        'ROLE_ADMIN' => 'Admin',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'You must enter an email')]
    #[Assert\Email(message: 'You must enter a valid email')]
    #[Assert\Length(
        max: 180,
        maxMessage: 'Your email {{ value }} do not exceed {{ limit }} characters'
    )]
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
    #[Assert\Length(
        min: 6,
        max: 4096, // Symfony's limit to dodge DoS attack
        minMessage: 'Your password should be at least {{ limit }} characters',
    )]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'You must provide a nickname')]
    #[Assert\Length(
        min: 5,
        max: 30,
        minMessage: 'Your nickname must be at least {{ limit }} characters',
        maxMessage: 'Your nickname cannot be longer than {{ limit }} characters'
    )]
    private ?string $nickname = null;

    /**
     * @var Collection<int, UserItemQuestCount>
     */
    #[ORM\OneToMany(targetEntity: UserItemQuestCount::class, mappedBy: 'user')]
    private Collection $userItemQuestCounts;

    public function __construct()
    {
        $this->userItemQuestCounts = new ArrayCollection();
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
    public function getPassword(): ?string
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

    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection<int, UserItemQuestCount>
     */
    public function getUserItemQuestCounts(): Collection
    {
        return $this->userItemQuestCounts;
    }

    public function addUserItemQuestCount(UserItemQuestCount $userItemQuestCount): static
    {
        if (!$this->userItemQuestCounts->contains($userItemQuestCount)) {
            $this->userItemQuestCounts->add($userItemQuestCount);
            $userItemQuestCount->setUser($this);
        }

        return $this;
    }

    public function removeUserItemQuestCount(UserItemQuestCount $userItemQuestCount): static
    {
        if ($this->userItemQuestCounts->removeElement($userItemQuestCount)) {
            // set the owning side to null (unless already changed)
            if ($userItemQuestCount->getUser() === $this) {
                $userItemQuestCount->setUser(null);
            }
        }

        return $this;
    }
}
