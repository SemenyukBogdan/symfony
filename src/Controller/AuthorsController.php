<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorsType;
use App\Repository\AuthorsRepository;
use App\Services\LibrarySevice;
use App\Services\ValidatorService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\RequestCheckerService;
use App\Services\AuthorsService\AuthorsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

#[Route('/authors')]
final class AuthorsController extends AbstractController
{

    private const ITEMS_PER_PAGE = 10;
    private const REQUIRED_FIELDS_FOR_CREATE_PRODUCT = [
        'first_name',
        'second_name',
        'birth_date',
        'country'
    ];

    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private AuthorsService $authorsService,
    ) {}

//    #[Route(name: 'app_authors_index', methods: ['GET'])]
//    public function index(AuthorsRepository $authorsRepository): Response
//    {
//        return $this->render('authors/index.html.twig', [
//            'authors' => $authorsRepository->findAll(),
//        ]);
//    }

//    #[Route('/new', name: 'app_authors_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, AuthorsService $authorsService): Response
//    {
//        $author = new Author();
//        $form = $this->createForm(AuthorsType::class, $author);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $data = $request->request->all('authors');
//            $errors = $authorsService->createAuthor($author);
//            if (!empty($errors)) {
//                return $this->render('authors/new.html.twig', [
//                    'form' => $form->createView(),
//                    'errors' => $errors,
//                ]);
//            }
//            return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('authors/new.html.twig', [
//            'author' => $author,
//            'form' => $form,
//        ]);
//    }

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_products_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $birth_date = new DateTime($requestData['birth_date']);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_PRODUCT);
        $author = $this->authorsService->createAuthor(
           $requestData['first_name'],
           $requestData['second_name'],
           $birth_date,
           $requestData['country']
        );
            $this->entityManager->flush();
            return new JsonResponse($author, Response::HTTP_CREATED);
        }


    #[Route('/',name: 'app_get_authors_collection', methods: ['GET'])]
    public function getAuthorsCollection(Request $request): JsonResponse{

        $requestData = $request->query->all();
        $itemsPerPage = (int)isset($requestData['itemsPerPage'])
            ? $requestData['itemsPerPage']
            : self::ITEMS_PER_PAGE;
        $page = (int)isset($requestData['page'])
            ? $requestData['page']
            : 1;
        $productsData = $this->entityManager->getRepository(Author::class)->getAllAuthorsByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);
    }
















        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/{id}', name: 'app_authors_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('authors/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_authors_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthorsType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('authors/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_authors_delete', methods: ['POST'])]
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
    }
}
