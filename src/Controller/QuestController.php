<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Entity\Quest;
use App\Form\QuestType;
use App\Repository\MerchantRepository;
use App\Repository\QuestRepository;
use App\Repository\UserItemQuestCountRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/quest', name: 'quest_')]
final class QuestController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(
        QuestRepository $questRepository,
        UserItemQuestCountRepository $countRepository,
        MerchantRepository $merchantRepository,
        Security $security,
        Request $request,
    ): Response
    {
        // Retrieve user currently connected with Security service
        $user = $security->getUser();
        $quests = $questRepository->findAll();

        // For each Quest, retrieve the count link to associated Item
        $counts = [];

        // Run through Quest
        foreach ($quests as $quest) {
            // then through each associated Item
            foreach ($quest->getQuestItems() as $questItem) {
                $item = $questItem->getItem();
                $key = $quest->getId() . '_' . $item->getId();

                $counts[$key] = $countRepository->findOneBy([
                    'user' => $user,
                    'quest' => $quest,
                    'item' => $item,
                ]);
            }
        }

        $page = $request->query->getInt('page', 1);
        $quests = $questRepository->paginateQuests($page);


        return $this->render('quest/index.html.twig', [
            'quests' => $quests,
            'counts' => $counts,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $quest = new Quest();
        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Allow to slugify string QuestName when a quest is added
            $slug = $slugger->slug($quest->getName());
            $quest->setSlug($slug);

            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, QuestRepository $questRepository): Response
    {
        // Allow to find the Quest by slug instead of id
        $quest = $questRepository->findOneBy(['slug' => $slug]);

        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, QuestRepository $questRepository, Request $request,
                         EntityManagerInterface $entityManager): Response
    {
        // Allow to find the Quest by slug instead of id
        $quest = $questRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/edit.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Quest $quest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
    }
}
