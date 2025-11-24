<?php

namespace App\Controller;

use App\Entity\Returns;
use App\Form\ReturnsType;
use App\Repository\ReturnsRepository;
use App\Services\RequestCheckerService;
use App\Services\ReturnsService\ReturnsService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/returns')]
final class ReturnsController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const REQUIRED_FIELDS_FOR_CREATE_RETURN = [
        'borrow_id',
        'return_date',
        'fine_amount',
        'condition',
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReturnsService $returnsService,
        private RequestCheckerService $requestCheckerService
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_post_return_item', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->requestCheckerService->check($requestData,self::REQUIRED_FIELDS_FOR_CREATE_RETURN);
        $product = $this->returnsService->createReturn(
            $requestData['borrow_id'],
            $requestData['return_date'],
            $requestData['condition'],
            $requestData['fine_amount']
        );
        $this->entityManager->flush();
        return new JsonResponse($product, Response::HTTP_CREATED);
        }



    #[Route('/', name: 'app_get_Returns_collection', methods: ['GET'])]
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
            $this->entityManager->getRepository(Returns::class)->getAllReturnsByFilter($requestData, $itemsPerPage, $page);
        return new JsonResponse($productsData);
        }




    //////////////////////////////////////////////////////////////////////////////////////////

//    #[Route(name: 'app_returns_index', methods: ['GET'])]
//    public function index(ReturnsRepository $returnsRepository): Response
//    {
//        return $this->render('returns/index.html.twig', [
//            'returns' => $returnsRepository->findAll(),
//        ]);
//    }

    #[Route('/new', name: 'app_returns_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $return = new Returns();
        $form = $this->createForm(ReturnsType::class, $return);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($return);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
//        }

        return $this->render('returns/new.html.twig', [
            'return' => $return,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_returns_show', methods: ['GET'])]
    public function show(Returns $return): Response
    {
        return $this->render('returns/show.html.twig', [
            'return' => $return,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_returns_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Returns $return, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReturnsType::class, $return);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('returns/edit.html.twig', [
            'return' => $return,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_returns_delete', methods: ['POST'])]
    public function delete(Request $request, Returns $return, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$return->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($return);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_returns_index', [], Response::HTTP_SEE_OTHER);
    }
}
