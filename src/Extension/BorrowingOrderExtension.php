<?php

namespace App\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Borrowing;
use Doctrine\ORM\QueryBuilder;

final class BorrowingOrderExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        ?Operation                  $operation = null,
        array                       $context = []
    ): void {
        if ($resourceClass !== Borrowing::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->addOrderBy($rootAlias.'.dueDate', 'ASC');
    }
}
