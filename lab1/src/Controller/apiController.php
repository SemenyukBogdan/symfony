<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class apiController extends AbstractController
{


    #[Route('/test', name: 'app_test')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/records', name: 'create_record', methods: ['POST'])]
    public function createRecord(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(),true);
        if(!is_array($payload)){
            return $this->json(['error' => 'Invalid JSON'],400);
        }

        return $this->json($payload,201);
    }

    #[Route('/records/{id}', name: 'read_record', methods: ['GET'])]
    public function readRecord(Request $request): JsonResponse
    {
        $exapmle_record = 'example';

        return $this->json(['text' => $exapmle_record],202);
    }

    #[Route('/records/{id}', name: 'delete_record', methods: ['DELETE'])]
    public function deleteRecord(Request $request): JsonResponse
    {
        return $this->json(null, 204);
    }

    #[Route('/records/{id}', name: '', methods: ['PATCH'])]
    public function updateRecord(int $id, Request $request): JsonResponse
    {
        $patch = $request->getContent() === '' ? [] : $request->toArray();
        return $this->json(['id' => $id, 'updated' => $patch], 200);
    }

}
