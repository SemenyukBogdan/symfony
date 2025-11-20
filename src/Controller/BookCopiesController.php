<?php

namespace App\Controller;

use App\Entity\BookCopy;
use App\Form\BookCopiesType;
use App\Repository\BookCopiesRepository;
use App\Services\AuthorsService\AuthorsService;
use App\Services\LibrarySevice;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Services\BookCopiesService\BookCopiesService;


#[Route('/book/copies')]
final class BookCopiesController extends AbstractController
{

    private const REQUIRED_FIELDS_FOR_CREATE_PRODUCT = [
        'book_id',
        'inventory_number',
        'shelf_location',
        'status'
    ];
    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private BookCopiesService $bookCopiesService
    ) {}

    #[Route(name: 'app_book_copies_index', methods: ['GET'])]
    public function index(BookCopiesRepository $bookCopiesRepository): Response
    {
        return $this->render('book_copies/index.html.twig', [
            'book_copies' => $bookCopiesRepository->findAll(),
        ]);
    }

//    #[Route('/new', name: 'app_book_copies_new', methods: ['GET', 'POST'])]
//    public function new(Request $request,BookCopiesService $bookCopiesService): Response
//    {
//        $bookCopy = new BookCopy();
//        $form = $this->createForm(BookCopiesType::class, $bookCopy);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $bookCopiesService->createBookCopy(
//                $requestData['name'],
//
//            );
//
//            return $this->redirectToRoute('app_book_copies_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('book_copies/new.html.twig', [
//            'book_copy' => $bookCopy,
//            'form' => $form,
//        ]);
//    }





    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_book_copies_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_PRODUCT);
        $bookCopy = $this->bookCopiesService->createBookCopy(
            $requestData['book_id'],
            $requestData['inventory_number'],
            $requestData['shelf_location'],
            $requestData['status']
        );
        $this->entityManager->flush();
        return new JsonResponse($bookCopy, Response::HTTP_CREATED);
    }
    #[Route('/{id}', name: 'app_book_copies_show', methods: ['GET'])]
    public function show(BookCopy $bookCopy): Response
    {
        return $this->render('book_copies/show.html.twig', [
            'book_copy' => $bookCopy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_copies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookCopy $bookCopy, EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, BookCopy $bookCopy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookCopy->getId(), $request->getPayload()->getString('_token'))) {

            $entityManager->remove($bookCopy);
            $entityManager->flush();        }

        return $this->redirectToRoute('app_book_copies_index', [], Response::HTTP_SEE_OTHER);
    }
}
