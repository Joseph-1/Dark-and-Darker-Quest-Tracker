<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\QuestItemRepository;
use App\Repository\QuestRepository;
use App\Service\ItemCountService;
use App\Service\ItemCountTotalService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/item', name: 'item_')]
final class ItemController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(
        ItemRepository $itemRepository,
        Request $request,
        ItemCountTotalService $itemCountTotalService,
        QuestItemRepository $questItemRepository,
    ): Response
    {
        $page = $request->query->getInt('page', 1);
        $items = $itemRepository->paginateItems($page);

        $itemTotals = $itemCountTotalService->totalCount($questItemRepository);

        return $this->render('item/index.html.twig', [
            'items' => $items,
            'itemTotals' => $itemTotals,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Allow to slugify string QuestName when a quest is added
            $slug = $slugger->slug($item->getName());
            $item->setSlug($slug);

            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, ItemRepository $itemRepository): Response
    {
        $item = $itemRepository->findOneBy(['slug' => $slug]);

        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, ItemRepository $itemRepository, Request $request,
                         EntityManagerInterface $entityManager): Response
    {
        $item = $itemRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quest/{questId}/item/{itemId}/increment', name: 'count_increment', methods: ['POST'])]
    public function increment(
        int $questId,
        int $itemId,
        QuestRepository $questRepository,
        ItemRepository $itemRepository,
        Security $security,
        QuestItemRepository $questItemRepository,
        ItemCountService $itemCountService,
    ): Response {
        $quest = $questRepository->find($questId);
        $item = $itemRepository->find($itemId);
        $user = $security->getUser();

        $itemCountService->incrementCount($user, $item, $quest, $questItemRepository)->getCount();

        return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/quest/{questId}/item/{itemId}/decrement', name: 'count_decrement', methods: ['POST'])]
    public function decrement(
        int $questId,
        int $itemId,
        QuestRepository $questRepository,
        ItemRepository $itemRepository,
        Security $security,
        ItemCountService $itemCountService,
    ): Response {
        $quest = $questRepository->find($questId);
        $item = $itemRepository->find($itemId);
        $user = $security->getUser();

        $itemCountService->decrementCount($user, $item, $quest)->getCount();

        return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
    }
}
