<?php

namespace App\Entity;

use App\Repository\QuestItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestItemRepository::class)]
class QuestItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\ManyToOne(inversedBy: 'questItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column]
    private ?int $requiredCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): static
    {
        $this->quest = $quest;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getRequiredCount(): ?int
    {
        return $this->requiredCount;
    }

    public function setRequiredCount(int $requiredCount): static
    {
        $this->requiredCount = $requiredCount;

        return $this;
    }
}
