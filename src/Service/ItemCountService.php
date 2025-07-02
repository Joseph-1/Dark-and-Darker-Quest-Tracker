<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Quest;
use App\Entity\User;
use App\Entity\UserItemQuestCount;
use App\Repository\UserItemQuestCountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ItemCountService
{
    private EntityManagerInterface $em;
    private UserItemQuestCountRepository $repository;

    public function __construct(EntityManagerInterface $em, UserItemQuestCountRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function incrementCount(
        User $user,
        Item $item,
        Quest $quest,
    ): UserItemQuestCount
    {
        // Look for an existing entry
        // Allow to do :  $this->repository->findOneBy([]); because of our constructor line 18
        $userItemQuestCount = $this->repository->findOneBy([
            'user' => $user,
            'item' => $item,
            'quest' => $quest,
        ]);

        // If value is null, we set it to 0 and increment it by +1,
        // else if she's >= 0, we increment it by +1
        if ($userItemQuestCount === null) {
            $userItemQuestCount = new UserItemQuestCount();

            // Assign the relations and init counter to 0
            $userItemQuestCount->setUser($user);
            $userItemQuestCount->setItem($item);
            $userItemQuestCount->setQuest($quest);
            $userItemQuestCount->setCount(0);

            // Then increment by +1
            $userItemQuestCount->setCount($userItemQuestCount->getCount() + 1);

            // Allow to do this line 53 and 54 because of our constructor
            $this->em->persist($userItemQuestCount);
            $this->em->flush();
        }

        // Increment value if she already exist
        else {
            // Retrieve the value and we edit it by incrementing it
            $userItemQuestCount->setCount($userItemQuestCount->getCount() + 1);

            $this->em->flush();
        }
        return $userItemQuestCount;
    }

    public function decrementCount(
        User $user,
        Item $item,
        Quest $quest,
    ): UserItemQuestCount
    {
        // Look for an existing entry
        $userItemQuestCount = $this->repository->findOneBy([
            'user' => $user,
            'item' => $item,
            'quest' => $quest,
        ]);

        if ($userItemQuestCount === null) {
            // If the entry doesn't exist, we create with count=0, no decrement (negative not allowed)
            $userItemQuestCount = new UserItemQuestCount();

            $userItemQuestCount->setUser($user);
            $userItemQuestCount->setItem($item);
            $userItemQuestCount->setQuest($quest);
            $userItemQuestCount->setCount(0);

            $this->em->persist($userItemQuestCount);
            $this->em->flush();
        }

        // Decrement the value if she already exist
        else {
            if ($userItemQuestCount->getCount() > 0) {
                $userItemQuestCount->setCount($userItemQuestCount->getCount() - 1);
                $this->em->flush();
            }
        }
        return $userItemQuestCount;
    }

}
