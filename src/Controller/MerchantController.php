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
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $merchant = new Merchant();
        $form = $this->createForm(MerchantType::class, $merchant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Allow to slugify string MerchantName when a merchant is added
            $slug = $slugger->slug($merchant->getName());
            $merchant->setSlug($slug);

            $entityManager->persist($merchant);
            $entityManager->flush();

            return $this->redirectToRoute('merchant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('merchant/new.html.twig', [
            'merchant' => $merchant,
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, MerchantRepository $merchantRepository): Response
    {
        // Allow to find the Merchant by slug instead of id
        $merchant = $merchantRepository->findOneBy(['slug' => $slug]);

        return $this->render('merchant/show.html.twig', [
            'merchant' => $merchant,
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, MerchantRepository $merchantRepository, Request $request,
                         EntityManagerInterface $entityManager): Response
    {
        // Allow to find the Merchant by slug instead of id
        $merchant = $merchantRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(MerchantType::class, $merchant);
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

    #[Route('/quests/merchant/{slug}', name: 'quests_by_merchant')]
    public function questsByMerchant(MerchantRepository $merchantRepository, string $slug): Response
    {
        $merchant = $merchantRepository->findOneBy(['slug' => $slug]);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $quests = $merchant->getQuests();

        return $this->render('quest/by_merchant.html.twig', [
            'merchant' => $merchant,
            'quests' => $quests,
        ]);
    }
}
