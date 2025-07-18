<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\QuestItem;
use App\Repository\QuestItemRepository;

class ItemCountTotalService
{

    public function totalCount(
        QuestItemRepository $questItemRepository,
    )
    {
        // on récupère tout les Items de Quêtes
        $questItem = $questItemRepository->findAll();

        foreach ($questItem as $questItem) {
            dd($questItem);
            // on récupère leur id
            $itemId = $questItem->getItem()->getId();
            // on récupère leur requiredCount
            $requiredCount = $questItem->getRequiredCount();

            if () {

            }
        }
        // Récupérer les requiredCount
        $requiredCount = $questItem->getRequiredCount();
        // Additionner les requiredCount

        return $totalItemcount;
    }
}
