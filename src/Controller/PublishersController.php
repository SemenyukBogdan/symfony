<?php

namespace App\Controller;

use App\Entity\Publishers;
use App\Form\PublishersType;
use App\Repository\PublishersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/publishers')]
final class PublishersController extends AbstractController
{
    #[Route(name: 'app_publishers_index', methods: ['GET'])]
    public function index(PublishersRepository $publishersRepository): Response
    {
        return $this->render('publishers/index.html.twig', [
            'publishers' => $publishersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publishers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publishers();
        $form = $this->createForm(PublishersType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('app_publishers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publishers/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publishers_show', methods: ['GET'])]
    public function show(Publishers $publisher): Response
    {
        return $this->render('publishers/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publishers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publishers $publisher, EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, Publishers $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publishers_index', [], Response::HTTP_SEE_OTHER);
    }
}
