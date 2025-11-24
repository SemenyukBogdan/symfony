<?php

namespace App\Repository;

use App\Entity\Reader;
use App\Entity\Returns;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Returns>
 */
class ReturnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Returns::class);
    }

//    /**
//     * @return Returns[] Returns an array of Returns objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Returns
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
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
        'returns' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllReturnsByFilter(array $data, int $itemsPerPage, int
                                                     $page): array
    {
        $queryBuilder = $this->createQueryBuilder('return');
        if (isset($data['name'])) {
            $queryBuilder->andWhere('returns.name LIKE :name')
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

        $returns_array = array_map(static function (Returns $returns): array {
            return [
                'id'          => $returns->getId(),
                'return_date'          => $returns->getReturnDate(),
                'condition_state'      => $returns->getBookCondition(),
                'fine_amount'           => $returns->getFineAmount(),
            ];

        }, $query->getResult());
        return [
            'returns' => $returns_array,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }


}
