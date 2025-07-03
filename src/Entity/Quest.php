<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestRepository::class)]
#[UniqueEntity('name')]
class Quest
{
    public const MAPS = [
        'Any' => 'any',
        'Goblin Caves' => 'goblin caves',
        'Ruins' => 'ruins',
        'Crypts' => 'crypts',
        'Ice Cavern' => 'ice cavern',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'You must provide a Quest name')]
    #[Assert\Length(
        min: 5,
        max: 30,
        minMessage: 'Quest name must be at least {{ limit }} characters long',
        maxMessage: 'Quest name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'You must provide a map to the Quest')]
    private ?string $map = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'You must provide a map to the Quest')]
    #[Assert\Length(max: 500,
    maxMessage: 'Quest name cannot be longer than {{ limit }} characters',
    )]
    private ?string $objective = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Merchant $merchant = null;

    /**
     * @var Collection<int, UserItemQuestCount>
     */
    #[ORM\OneToMany(targetEntity: UserItemQuestCount::class, mappedBy: 'quest')]
    private Collection $userItemQuestCounts;

    /**
     * @var Collection<int, QuestItem>
     */
    #[ORM\OneToMany(targetEntity: QuestItem::class, mappedBy: 'quest')]
    private Collection $questItems;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->userItemQuestCounts = new ArrayCollection();
        $this->questItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(string $map): static
    {
        $this->map = $map;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): static
    {
        $this->objective = $objective;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): static
    {
        $this->merchant = $merchant;

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
            $userItemQuestCount->setQuest($this);
        }

        return $this;
    }

    public function removeUserItemQuestCount(UserItemQuestCount $userItemQuestCount): static
    {
        if ($this->userItemQuestCounts->removeElement($userItemQuestCount)) {
            // set the owning side to null (unless already changed)
            if ($userItemQuestCount->getQuest() === $this) {
                $userItemQuestCount->setQuest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestItem>
     */
    public function getQuestItems(): Collection
    {
        return $this->questItems;
    }

    public function addQuestItem(QuestItem $questItem): static
    {
        if (!$this->questItems->contains($questItem)) {
            $this->questItems->add($questItem);
            $questItem->setQuest($this);
        }

        return $this;
    }

    public function removeQuestItem(QuestItem $questItem): static
    {
        if ($this->questItems->removeElement($questItem)) {
            // set the owning side to null (unless already changed)
            if ($questItem->getQuest() === $this) {
                $questItem->setQuest(null);
            }
        }

        return $this;
    }
}
