<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Form\QuestForm;
use App\Repository\QuestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quest', name: 'quest_')]
final class QuestController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(QuestRepository $questRepository): Response
    {
        return $this->render('quest/index.html.twig', [
            'quests' => $questRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quest = new Quest();
        $form = $this->createForm(QuestForm::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Quest $quest): Response
    {
        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quest $quest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestForm::class, $quest);
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
