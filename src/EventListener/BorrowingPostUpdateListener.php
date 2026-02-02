<?php

namespace App\EventListener;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postUpdate)]
final class BorrowingPostUpdateListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Borrowing) {
            return;
        }

        $uow = $args->getObjectManager()->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($entity);

        $this->logger->info('Borrowing updated', [
            'id' => $entity->getId(),
            'changes' => $changes,
        ]);
    }
}
