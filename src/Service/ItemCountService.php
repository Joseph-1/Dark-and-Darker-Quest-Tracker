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
        // Cherche une entrée existante
        // Allow to do :  $this->repository->findOneBy([]); because of our constructor line 18
        $userItemQuestCount = $this->repository->findOneBy([
            'user' => $user,
            'item' => $item,
            'quest' => $quest,
        ]);

        // Si la valeur est null on la met par défaut à 0 et oon l'incrémente de +1,
        // sinon si elle est >= 0 on l'incrémente de +1
        if ($userItemQuestCount === null) {
            // On créer un nouvelle objet
            $userItemQuestCount = new UserItemQuestCount();

            // On assigne les relations et on initialise le compteur à 0
            $userItemQuestCount->setUser($user);
            $userItemQuestCount->setItem($item);
            $userItemQuestCount->setQuest($quest);
            $userItemQuestCount->setCount(0);

            // Puis on incrémente de 1
            $userItemQuestCount->setCount($userItemQuestCount->getCount() + 1);

            // Allow to do this line 54 and 55 because of our constructor
            $this->em->persist($userItemQuestCount);
            $this->em->flush();
        }

        // Incrémenter la valeur si elle existe déjà
        else {
            // On récupère la valeur et on l'edit en l'incrémentant
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
        // Cherche une entrée existante
        $userItemQuestCount = $this->repository->findOneBy([
            'user' => $user,
            'item' => $item,
            'quest' => $quest,
        ]);

        if ($userItemQuestCount === null) {
            // Si l'entrée n'existe pas, on crée avec count=0, pas de décrémentation (pas de négatif)
            $userItemQuestCount = new UserItemQuestCount();

            $userItemQuestCount->setUser($user);
            $userItemQuestCount->setItem($item);
            $userItemQuestCount->setQuest($quest);
            $userItemQuestCount->setCount(0);

            $this->em->persist($userItemQuestCount);
            $this->em->flush();
        }

        // Décrémente la valeur si elle existe déjà
        else {
            if ($userItemQuestCount->getCount() > 0) {
                $userItemQuestCount->setCount($userItemQuestCount->getCount() - 1);
                $this->em->flush();
            }
        }
        return $userItemQuestCount;
    }

}
