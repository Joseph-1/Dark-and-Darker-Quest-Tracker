<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Form\MerchantType;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/merchant', name: 'merchant_')]
final class MerchantController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MerchantRepository $merchantRepository): Response
    {
        return $this->render('merchant/index.html.twig', [
            'merchants' => $merchantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $merchant = new Merchant();
        $form = $this->createForm(MerchantType::class, $merchant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merchant);
            $entityManager->flush();

            return $this->redirectToRoute('merchant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('merchant/new.html.twig', [
            'merchant' => $merchant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Merchant $merchant): Response
    {
        return $this->render('merchant/show.html.twig', [
            'merchant' => $merchant,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Merchant $merchant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MerchantForm::class, $merchant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('merchant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('merchant/edit.html.twig', [
            'merchant' => $merchant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Merchant $merchant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$merchant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($merchant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('merchant_index', [], Response::HTTP_SEE_OTHER);
    }
}
