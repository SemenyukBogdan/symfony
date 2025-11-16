<?php

namespace App\Controller;

use App\Entity\Borrowings;
use App\Form\BorrowingsType;
use App\Repository\BorrowingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/borrowings')]
final class BorrowingsController extends AbstractController
{
    #[Route(name: 'app_borrowings_index', methods: ['GET'])]
    public function index(BorrowingsRepository $borrowingsRepository): Response
    {
        return $this->render('borrowings/index.html.twig', [
            'borrowings' => $borrowingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_borrowings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $borrowing = new Borrowings();
        $form = $this->createForm(BorrowingsType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($borrowing);
            $entityManager->flush();

            return $this->redirectToRoute('app_borrowings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('borrowings/new.html.twig', [
            'borrowing' => $borrowing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_borrowings_show', methods: ['GET'])]
    public function show(Borrowings $borrowing): Response
    {
        return $this->render('borrowings/show.html.twig', [
            'borrowing' => $borrowing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_borrowings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Borrowings $borrowing, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BorrowingsType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_borrowings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('borrowings/edit.html.twig', [
            'borrowing' => $borrowing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_borrowings_delete', methods: ['POST'])]
    public function delete(Request $request, Borrowings $borrowing, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borrowing->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($borrowing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_borrowings_index', [], Response::HTTP_SEE_OTHER);
    }
}
