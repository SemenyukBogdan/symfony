<?php

namespace App\Controller;

use App\Entity\Librarians;
use App\Form\LibrariansType;
use App\Repository\LibrariansRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/librarians')]
final class LibrariansController extends AbstractController
{
    #[Route(name: 'app_librarians_index', methods: ['GET'])]
    public function index(LibrariansRepository $librariansRepository): Response
    {
        return $this->render('librarians/index.html.twig', [
            'librarians' => $librariansRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_librarians_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $librarian = new Librarians();
        $form = $this->createForm(LibrariansType::class, $librarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($librarian);
            $entityManager->flush();

            return $this->redirectToRoute('app_librarians_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('librarians/new.html.twig', [
            'librarian' => $librarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_librarians_show', methods: ['GET'])]
    public function show(Librarians $librarian): Response
    {
        return $this->render('librarians/show.html.twig', [
            'librarian' => $librarian,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_librarians_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Librarians $librarian, EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, Librarians $librarian, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$librarian->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($librarian);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_librarians_index', [], Response::HTTP_SEE_OTHER);
    }
}
