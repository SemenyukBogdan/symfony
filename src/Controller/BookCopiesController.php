<?php

namespace App\Controller;

use App\Entity\BookCopies;
use App\Form\BookCopiesType;
use App\Repository\BookCopiesRepository;
use App\Services\LibrarySevice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//use App\Service\BookCopiesService;

#[Route('/book/copies')]
final class BookCopiesController extends AbstractController
{
    #[Route(name: 'app_book_copies_index', methods: ['GET'])]
    public function index(BookCopiesRepository $bookCopiesRepository): Response
    {
        return $this->render('book_copies/index.html.twig', [
            'book_copies' => $bookCopiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_copies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LibrarySevice $librarySevice): Response
    {
        $bookCopy = new BookCopies();
        $form = $this->createForm(BookCopiesType::class, $bookCopy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $librarySevice->createBookCopy($bookCopy);

            return $this->redirectToRoute('app_book_copies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_copies/new.html.twig', [
            'book_copy' => $bookCopy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_copies_show', methods: ['GET'])]
    public function show(BookCopies $bookCopy): Response
    {
        return $this->render('book_copies/show.html.twig', [
            'book_copy' => $bookCopy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_copies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookCopies $bookCopy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookCopiesType::class, $entityManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_copies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_copies/edit.html.twig', [
            'book_copy' => $bookCopy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_copies_delete', methods: ['POST'])]
    public function delete(Request $request, BookCopies $bookCopy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookCopy->getId(), $request->getPayload()->getString('_token'))) {

            $entityManager->remove($bookCopy);
            $entityManager->flush();        }

        return $this->redirectToRoute('app_book_copies_index', [], Response::HTTP_SEE_OTHER);
    }
}
