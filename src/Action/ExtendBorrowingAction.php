<?php

namespace App\Action;

use App\Entity\Borrowing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ExtendBorrowingAction
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(Borrowing $data, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true);
        $days = (int)($payload['days'] ?? 0);

        if ($days <= 0) {
            throw new BadRequestHttpException('days must be > 0');
        }

        $due = $data->getDueDate();
        if ($due === null) {
            throw new BadRequestHttpException('dueDate is null');
        }

        $data->setDueDate((clone $due)->modify("+{$days} days"));

        $this->em->flush();
        return new JsonResponse([
            'id' => $data->getId(),
            'dueDate' => $data->getDueDate()?->format('Y-m-d'),
            'addedDays' => $days,
        ]);
    }
}
