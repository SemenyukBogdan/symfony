<?php

namespace App\Repository;

use App\Entity\Librarian;
use App\Entity\Reader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Reader>
 */
class ReadersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reader::class);
    }

    //    /**
    //     * @return Readers[] Returns an array of Readers objects
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

    //    public function findOneBySomeField($value): ?Readers
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
        'readers' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllReadersByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('product');
        if (isset($data['name'])) {
            $queryBuilder->andWhere('product.full_name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }
        if (isset($data['email'])) {
            $queryBuilder->andWhere('product.email LIKE :email')
                ->setParameter('email', '%' . $data['email'] . '%');
        }





        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);




        $query = $queryBuilder->getQuery();

        $readers_array = array_map(static function (Reader $reader): array {
            return [
                'id'          => $reader->getId(),
                'full_name'          => $reader->getFullName(),
                'email'          => $reader->getEmail(),
                'phone'          => $reader->getPhone(),
            ];

        }, $query->getResult());
        return [
            'readers' => $readers_array,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }

}
