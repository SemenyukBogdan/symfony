<?php

namespace App\Controller;

use App\Entity\BookReviews;
use App\Form\BookReviewsType;
use App\Repository\BookReviewsRepository;
use App\Service\BookReviewsService;
use App\Service\LibrarySevice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book/reviews')]
final class BookReviewsController extends AbstractController
{
    #[Route(name: 'app_book_reviews_index', methods: ['GET'])]
    public function index(BookReviewsRepository $bookReviewsRepository): Response
    {
        return $this->render('book_reviews/index.html.twig', [
            'book_reviews' => $bookReviewsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_reviews_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LibrarySevice $libraryService): Response
    {
        $bookReview = new BookReviews();
        $form = $this->createForm(BookReviewsType::class, $bookReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $libraryService->createBookReview($bookReview);

            return $this->redirectToRoute('app_book_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_reviews/new.html.twig', [
            'book_review' => $bookReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_reviews_show', methods: ['GET'])]
    public function show(BookReviews $bookReview): Response
    {
        return $this->render('book_reviews/show.html.twig', [
            'book_review' => $bookReview,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_reviews_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookReviews $bookReview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookReviewsType::class, $bookReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_reviews_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_reviews/edit.html.twig', [
            'book_review' => $bookReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_reviews_delete', methods: ['POST'])]
    public function delete(Request $request, BookReviews $bookReview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookReview->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bookReview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_reviews_index', [], Response::HTTP_SEE_OTHER);
    }
}
