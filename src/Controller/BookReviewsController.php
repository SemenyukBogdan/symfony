<?php

namespace App\Controller;

use App\Entity\BookReview;
use App\Form\BookReviewsType;
use App\Repository\BookReviewsRepository;
use App\Services\BookReviewsService\BookReviewsService;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book/reviews')]
final class BookReviewsController extends AbstractController
{

    private const ITEMS_PER_PAGE = 10;
    private const REQUIRED_FIELDS_FOR_CREATE_BOOK_REVIEW = [
        'book_id',
        'reader_id',
        'rating',
        'comment',
    ];
    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private BookReviewsService $bookReviewsService,
    ) {}
    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_products_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_BOOK_REVIEW);
        $bookReview = $this->bookReviewsService->createBookReview(
            $requestData['book_id'],
            $requestData['reader_id'],
            $requestData['rating'],
            $requestData['comment'],
        );
        $this->entityManager->flush();
        return new JsonResponse($bookReview, Response::HTTP_CREATED);
}

    #[Route('/', name: 'app_get_book_reviews_collection', methods: ['GET'])]
    public function getCollection(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = (int)isset($requestData['itemsPerPage'])
            ? $requestData['itemsPerPage']
            : self::ITEMS_PER_PAGE;
        $page = (int)isset($requestData['page'])
            ? $requestData['page']
            : 1;
        $productsData =
            $this->entityManager->getRepository(BookReview::class)->getAllBookReviewsByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($productsData);
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    #[Route(name: 'app_book_reviews_index', methods: ['GET'])]
//    public function index(BookReviewsRepository $bookReviewsRepository): Response
//    {
//        return $this->render('book_reviews/index.html.twig', [
//            'book_reviews' => $bookReviewsRepository->findAll(),
//        ]);
//    }

//    #[Route('/new', name: 'app_book_reviews_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, LibrarySevice $libraryService): Response
//    {
//        $bookReview = new BookReview();
//        $form = $this->createForm(BookReviewsType::class, $bookReview);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $libraryService->createBookReview($bookReview);
//
//            return $this->redirectToRoute('app_book_reviews_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('book_reviews/new.html.twig', [
//            'book_review' => $bookReview,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_book_reviews_show', methods: ['GET'])]
    public function show(BookReview $bookReview): Response
    {
        return $this->render('book_reviews/show.html.twig', [
            'book_review' => $bookReview,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_reviews_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookReview $bookReview, EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, BookReview $bookReview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookReview->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bookReview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_reviews_index', [], Response::HTTP_SEE_OTHER);
    }
}
