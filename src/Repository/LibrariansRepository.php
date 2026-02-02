<?php

namespace App\Repository;

use App\Entity\Borrowing;
use App\Entity\Librarian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Librarian>
 */
class LibrariansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Librarian::class);
    }

    //    /**
    //     * @return Librarians[] Returns an array of Librarians objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Librarians
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
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
        'librarians' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllLibrariansByFilter(array $data, int $itemsPerPage, int
                                                     $page): array
    {
        $queryBuilder = $this->createQueryBuilder('librarians');
        if (isset($data['name'])) {
            $queryBuilder->andWhere('librarians.full_name LIKE :name')
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

        $librarianArray = array_map(static function (Librarian $librarian): array {
            return [
                'id'          => $librarian->getId(),
                'full_name'          => $librarian->getFullName(),
                'phone'          => $librarian->getPhone(),
                'position'          => $librarian->getPosition(),
            ];

        }, $query->getResult());
        return [
            'librarians' => $librarianArray,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }


}
