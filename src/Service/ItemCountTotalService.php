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
        // Retrieve all Items Quest
        $questItems = $questItemRepository->findAll();

        // Table to store totalItemCount for each itemId
        $totalItemCounts = [];

        foreach ($questItems as $questItem) {
            // Retrieve itemId
            $itemId = $questItem->getItem()->getId();
            // Retrieve requiredCount
            $requiredCount = $questItem->getRequiredCount();

            // If already exist, we summing them
            if (array_key_exists($itemId, $totalItemCounts)) {
                $totalItemCounts[$itemId] += $requiredCount;
            } else {
                // Else, we add we add it with his initial count
                $totalItemCounts[$itemId] = $requiredCount;
            }
        }
        /*
        dd($totalItemCounts);
         *
         */

        return $totalItemCounts; // à utiliser plus tard
    }

}
