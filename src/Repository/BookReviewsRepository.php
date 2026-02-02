<?php

namespace App\Repository;

use App\Entity\BookReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<BookReview>
 */
class BookReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookReview::class);
    }

    //    /**
    //     * @return BookReviews[] Returns an array of BookReviews objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BookReviews
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return mixed
     */
    #[ArrayShape([
        'products' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllBookReviewsByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('review');

        if (!empty($data['min_rating'])) {
            $queryBuilder
                ->andWhere('review.rating >= :minRating')
                ->setParameter('minRating', (float)$data['min_rating']);
        }

        if (!empty($data['max_rating'])) {
            $queryBuilder
                ->andWhere('review.rating <= :maxRating')
                ->setParameter('maxRating', (float)$data['max_rating']);
        }


        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);
        return [
            'products' => $paginator->getQuery()->getResult(),
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }
}
