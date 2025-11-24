<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenresType;
use App\Repository\GenresRepository;
use App\Services\AuthorsService\AuthorsService;
use App\Services\GenresService\GenresService;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/genres')]
final class GenresController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const REQUIRED_FIELDS_FOR_CREATE_GENRE = [
        'name',
    ];

    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private GenresService $genresService,
    ) {}


    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_products_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_GENRE);
        $genre = $this->genresService->createGenre($requestData['name']);
        $this->entityManager->flush();
        return new JsonResponse($genre, Response::HTTP_CREATED);
    }


    #[Route('/', name: 'app_get_genres_collection', methods: ['GET'])]
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
            $this->entityManager->getRepository(Genre::class)->getAllGenresByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);
    }








    //////////////////////////////////////////////////////////////////////

    #[Route(name: 'app_genres_index', methods: ['GET'])]
    public function index(GenresRepository $genresRepository): Response
    {
        return $this->render('genres/index.html.twig', [
            'genres' => $genresRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_genres_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenresType::class, $genre);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($genre);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_genres_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('genres/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genres_show', methods: ['GET'])]
    public function show(Genre $genre): Response
    {
        return $this->render('genres/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_genres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GenresType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_genres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('genres/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genres_delete', methods: ['POST'])]
    public function delete(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_genres_index', [], Response::HTTP_SEE_OTHER);
    }
}
