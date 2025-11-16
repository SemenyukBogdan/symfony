<?php

namespace App\Controller;

use App\Entity\Readers;
use App\Form\ReadersType;
use App\Repository\ReadersRepository;
use App\Service\LibrarySevice;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/readers')]
final class ReadersController extends AbstractController
{
    #[Route(name: 'app_readers_index', methods: ['GET'])]
    public function index(ReadersRepository $readersRepository): Response
    {
        return $this->render('readers/index.html.twig', [
            'readers' => $readersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_readers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LibrarySevice $libraryService, ValidatorService $validatorService): Response
    {
        $reader = new Readers();
        $form = $this->createForm(ReadersType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errors = $validatorService->validateReader($reader);
            if (empty($errors)) {
                $libraryService->createReader($reader);
            }
            else{
                return $this->render('readers/new.html.twig', $errors); // Вивод ошибк
            }

            return $this->redirectToRoute('app_readers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('readers/new.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_readers_show', methods: ['GET'])]
    public function show(Readers $reader): Response
    {
        return $this->render('readers/show.html.twig', [
            'reader' => $reader,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_readers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Readers $reader, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReadersType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_readers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('readers/edit.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_readers_delete', methods: ['POST'])]
    public function delete(Request $request, Readers $reader, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reader->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reader);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_readers_index', [], Response::HTTP_SEE_OTHER);
    }
}
