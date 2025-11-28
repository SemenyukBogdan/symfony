<?php

namespace App\Action;

use App\Entity\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class NormalizeReaderPhoneAction
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(Reader $data): JsonResponse
    {
        $digits = preg_replace('/\D+/', '', (string)$data->getPhone());
        $data->setPhone($digits);

        $this->em->flush();
        return new JsonResponse([
            'id' => $data->getId(),
            'phone' => $data->getPhone(),
        ]);
    }
}
