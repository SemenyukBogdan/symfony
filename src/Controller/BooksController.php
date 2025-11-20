<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BooksType;
use App\Repository\BooksRepository;
use App\Services\BookReviewsService\BookReviewsService;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\BooksService\BooksService;

#[Route('/books')]
final class BooksController extends AbstractController
{

    const REQUIRED_FIELDS_FOR_CREATE_BOOK=[
        'title',
        'author_id',
        'genre_id',
        'publisher_id',
        'year',
        'description',
];
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BooksService $booksService,
        private RequestCheckerService $requestCheckerService
    ) {}
    #[Route(name: 'app_books_index', methods: ['GET'])]
    public function index(BooksRepository $booksRepository): Response
    {
        return $this->render('books/index.html.twig', [
            'books' => $booksRepository->findAll(),
        ]);
    }


    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_book_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_BOOK);
        $product = $this->booksService->createBook(
            $requestData['title'],
            $requestData['author_id'],
            $requestData['genre_id'],
            $requestData['publisher_id'],
            $requestData['year'],
            $requestData['description'],
        );
        $this->entityManager->flush();
        return new JsonResponse($product, Response::HTTP_CREATED);
}
    #[Route('/new', name: 'app_books_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($book);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_books_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('books/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_books_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('books/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_books_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_books_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_books_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_books_index', [], Response::HTTP_SEE_OTHER);
    }
}
