<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    //
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
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $rarity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Quest>
     */
    #[ORM\ManyToMany(targetEntity: Quest::class, mappedBy: 'items')]
    private Collection $quests;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
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

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): static
    {
        $this->rarity = $rarity;

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
}
