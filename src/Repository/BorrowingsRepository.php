<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Borrowing>
 */
class BorrowingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    //    /**
    //     * @return Borrowings[] Returns an array of Borrowings objects
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

    //    public function findOneBySomeField($value): ?Borrowings
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
        'Borrowings' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllBorrowingsByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('borrowing');

        $queryBuilder->leftJoin('borrowing.reader', 'reader');

        if (isset($data['name'])) {
            $queryBuilder->andWhere('borrowing.full_name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }



        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);

        $query = $queryBuilder->getQuery();

        $borrowingsArray = array_map(static function (Borrowing $borrowing): array {
            return [
                'id'          => $borrowing->getId(),
                'full_name'    => $borrowing->getReader()->getFullName(),
                'borrow_date' => $borrowing->getBorrowDate(),
                'phone'        => $borrowing->getReader()->getPhone(),
            ];
        }, $query->getResult());
        return [
            'Borrowings' => $borrowingsArray,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }
}
