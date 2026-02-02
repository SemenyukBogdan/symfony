<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Publisher>
 */
class PublishersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    //    /**
    //     * @return Publishers[] Returns an array of Publishers objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Publishers
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
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
        'publishers' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllPublishersByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('publisher');
        if (isset($data['name'])) {
            $queryBuilder->andWhere('product.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);




        return [
            'publishers' => $paginator->getQuery()->getResult(),
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }





}
