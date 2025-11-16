<?php

namespace App\Controller;

use App\Entity\Returns;
use App\Form\ReturnsType;
use App\Repository\ReturnsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/returns')]
final class ReturnsController extends AbstractController
{
    #[Route(name: 'app_returns_index', methods: ['GET'])]
    public function index(ReturnsRepository $returnsRepository): Response
    {
        return $this->render('returns/index.html.twig', [
            'returns' => $returnsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_returns_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $return = new Returns();
        $form = $this->createForm(ReturnsType::class, $return);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($return);
            $entityManager->flush();

            return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('returns/new.html.twig', [
            'return' => $return,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_returns_show', methods: ['GET'])]
    public function show(Returns $return): Response
    {
        return $this->render('returns/show.html.twig', [
            'return' => $return,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_returns_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Returns $return, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReturnsType::class, $return);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('returns/edit.html.twig', [
            'return' => $return,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_returns_delete', methods: ['POST'])]
    public function delete(Request $request, Returns $return, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$return->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($return);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
    }
}
