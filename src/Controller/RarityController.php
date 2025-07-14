<?php

namespace App\Controller;

use App\Entity\Rarity;
use App\Form\RarityForm;
use App\Repository\RarityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rarity', name: 'rarity_')]
final class RarityController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(RarityRepository $rarityRepository): Response
    {
        return $this->render('rarity/index.html.twig', [
            'rarities' => $rarityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rarity = new Rarity();
        $form = $this->createForm(RarityForm::class, $rarity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rarity);
            $entityManager->flush();

            return $this->redirectToRoute('rarity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rarity/new.html.twig', [
            'rarity' => $rarity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Rarity $rarity): Response
    {
        return $this->render('rarity/show.html.twig', [
            'rarity' => $rarity,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rarity $rarity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RarityForm::class, $rarity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rarity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rarity/edit.html.twig', [
            'rarity' => $rarity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Rarity $rarity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rarity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rarity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rarity_index', [], Response::HTTP_SEE_OTHER);
    }
}
