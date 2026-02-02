<?php

namespace App\EventListener;

use App\Entity\Reader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postUpdate)]
final class ReaderPostUpdateListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Reader) {
            return;
        }

        $uow = $args->getObjectManager()->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($entity);

        $this->logger->info('Reader updated', [
            'id' => $entity->getId(),
            'changes' => $changes,
        ]);
    }
}
