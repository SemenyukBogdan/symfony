<?php

namespace App\Controller;

use App\Entity\Librarian;
use App\Form\LibrariansType;
use App\Repository\LibrariansRepository;
use App\Services\GenresService\GenresService;
use App\Services\LibrariansService\LibrariansService;
use App\Services\LibrarySevice;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/librarians')]
final class LibrariansController extends AbstractController
{

    private const ITEMS_PER_PAGE =10;
    private const REQUIRED_FIELDS_FOR_CREATE_LIBRARIAN = [
        'full_name',
        'position',
        'phone',
    ];

    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private LibrariansService  $librariansService,
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_librarian_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_LIBRARIAN);
        $product = $this->librariansService->createLibrarian(
            $requestData['full_name'],
            $requestData['position'],
            $requestData['phone'],
        );
        $this->entityManager->flush();
        return new JsonResponse($product, Response::HTTP_CREATED);
    }


    #[Route('/', name: 'app_get_products_collection', methods: ['GET'])]
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
            $this->entityManager->getRepository(Librarian::class)->getAllLibrariansByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);
    }








    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    #[Route(name: 'app_librarians_index', methods: ['GET'])]
    public function index(LibrariansRepository $librariansRepository): Response
    {
        return $this->render('librarians/index.html.twig', [
            'librarians' => $librariansRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_librarians_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $librarian = new Librarian();
        $form = $this->createForm(LibrariansType::class, $librarian);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($librarian);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_librarians_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('librarians/new.html.twig', [
            'librarian' => $librarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_librarians_show', methods: ['GET'])]
    public function show(Librarian $librarian): Response
    {
        return $this->render('librarians/show.html.twig', [
            'librarian' => $librarian,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_librarians_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Librarian $librarian, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LibrariansType::class, $librarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_librarians_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('librarians/edit.html.twig', [
            'librarian' => $librarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_librarians_delete', methods: ['POST'])]
    public function delete(Request $request, Librarian $librarian, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$librarian->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($librarian);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_librarians_index', [], Response::HTTP_SEE_OTHER);
    }
}
