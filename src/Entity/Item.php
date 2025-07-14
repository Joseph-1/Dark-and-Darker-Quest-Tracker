<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[UniqueEntity('name')]
#[Vich\Uploadable]
class Item
{
    public const RARITIES = [
        'Any' => 'any',
        'Junk' => 'junk',
        'Poor' => 'poor',
        'Common' => 'common',
        'Uncommon' => 'uncommon',
        'Rare' => 'rare',
        'Epic' => 'epic',
        'Legendary' => 'legendary',
        'Unique' => 'unique',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'You must provide a Item name')]
    #[Assert\Length(
        min: 5,
        max: 30,
        minMessage: 'Item name must be at least {{ limit }} characters',
        maxMessage: 'Item name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Item picture name is too long',
    )]
    private ?string $picture = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $slug = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'picture')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, UserItemQuestCount>
     */
    #[ORM\OneToMany(targetEntity: UserItemQuestCount::class, mappedBy: 'item')]
    private Collection $userItemQuestCounts;

    /**
     * @var Collection<int, QuestItem>
     */
    #[ORM\OneToMany(targetEntity: QuestItem::class, mappedBy: 'item')]
    private Collection $questItems;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): static
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
            $quest->addItem($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            $quest->removeItem($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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
            $userItemQuestCount->setItem($this);
        }

        return $this;
    }

    public function removeUserItemQuestCount(UserItemQuestCount $userItemQuestCount): static
    {
        if ($this->userItemQuestCounts->removeElement($userItemQuestCount)) {
            // set the owning side to null (unless already changed)
            if ($userItemQuestCount->getItem() === $this) {
                $userItemQuestCount->setItem(null);
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
            $questItem->setItem($this);
        }

        return $this;
    }

    public function removeQuestItem(QuestItem $questItem): static
    {
        if ($this->questItems->removeElement($questItem)) {
            // set the owning side to null (unless already changed)
            if ($questItem->getItem() === $this) {
                $questItem->setItem(null);
            }
        }

        return $this;
    }
}
