<?php

namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingsType;
use App\Repository\BorrowingsRepository;
use App\Services\BooksService\BooksService;
use App\Services\BorrowingsService\BorrowingsService;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/borrowings')]
final class BorrowingsController extends AbstractController
{

    private const ITEMS_PER_PAGE = 10;
    const REQUIRED_FIELDS_FOR_CREATE_BORROWING=[
        'book_copy_id',
        'reader_id',
        'librarian_id',
        'borrow_date',
        'due_date',
    ];
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BorrowingsService $borrowingsService,
        private RequestCheckerService $requestCheckerService
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_borrowing_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_BORROWING);
        $product = $this->borrowingsService->createBorrowing(
            $requestData['book_copy_id'],
            $requestData['reader_id'],
            $requestData['librarian_id'],
            $requestData['borrow_date'],
            $requestData['due_date']
        );
        $this->entityManager->flush();
        return new JsonResponse($product, Response::HTTP_CREATED);
        }



    #[Route('/', name: 'app_get_borrowing_collection', methods: ['GET'])]
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
            $this->entityManager->getRepository(Borrowing::class)->getAllBorrowingsByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);
    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    #[Route(name: 'app_borrowings_index', methods: ['GET'])]
//    public function index(BorrowingsRepository $borrowingsRepository): Response
//    {
//        return $this->render('borrowings/index.html.twig', [
//            'borrowings' => $borrowingsRepository->findAll(),
//        ]);
//    }

    #[Route('/new', name: 'app_borrowings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingsType::class, $borrowing);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($borrowing);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_borrowings_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('borrowings/new.html.twig', [
            'borrowing' => $borrowing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_borrowings_show', methods: ['GET'])]
    public function show(Borrowing $borrowing): Response
    {
        return $this->render('borrowings/show.html.twig', [
            'borrowing' => $borrowing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_borrowings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Borrowing $borrowing, EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, Borrowing $borrowing, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borrowing->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($borrowing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_borrowings_index', [], Response::HTTP_SEE_OTHER);
    }
}
