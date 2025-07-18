<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\QuestItemRepository;
use App\Service\ItemCountTotalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugController extends AbstractController
{
    #[Route('/debug/item-total/', name: 'debug_item_total')]
    public function debugItemTotal(
        QuestItemRepository $questItemRepository,
        ItemCountTotalService $itemCountTotalService
    ): Response {

        $itemCountTotalService->totalCount($questItemRepository);

        return new Response('Debug terminé');
    }
}

