<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublishersType;
use App\Repository\PublishersRepository;
use App\Services\PublishersService\PublishersService;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/publishers')]
final class PublishersController extends AbstractController{

    private const ITEMS_PER_PAGE = 10;

    private const REQUIRED_FIELDS_FOR_CREATE_PUBLISHER = [
        'name',
        'country',
        'founded_year',
    ];


    /**
     * @throws Exception
     */
    public function __construct(
        private RequestCheckerService $requestCheckerService,
        private EntityManagerInterface $entityManager,
        private PublishersService $publishersService,
    ) {}
    #[Route('/', name: 'app_post_products_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_PUBLISHER);
        $publisher = $this->publishersService->createPublishers(
            $requestData['name'],
            $requestData['country'],
            $requestData['founded_year']
        );
        $this->entityManager->flush();
        return new JsonResponse($publisher, Response::HTTP_CREATED);
        }


    #[Route('/', name: 'app_get_publisher_collection', methods: ['GET'])]
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
            $this->entityManager->getRepository(Publisher::class)->getAllPublishersByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);

    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    #[Route(name: 'app_publishers_index', methods: ['GET'])]
//    public function index(PublishersRepository $publishersRepository): Response
//    {
//        return $this->render('publishers/index.html.twig', [
//            'publishers' => $publishersRepository->findAll(),
//        ]);
//    }

    #[Route('/new', name: 'app_publishers_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublishersType::class, $publisher);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($publisher);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_publishers_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('publishers/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publishers_show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('publishers/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publishers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublishersType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publishers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publishers/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publishers_delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publishers_index', [], Response::HTTP_SEE_OTHER);
    }
}
