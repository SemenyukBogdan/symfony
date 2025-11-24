<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Authors[] Returns an array of Authors objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Authors
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
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
    'authors' => "mixed",
    'totalPageCount' => "float",
    'totalItems' => "int"
    ])]
    public function getAllAuthorsByFilter(array $data, int $itemsPerPage, int $page): array{


        $queryBuilder = $this->createQueryBuilder('author');
        $expr = $queryBuilder->expr();
        if (isset($data['name'])) {
            $queryBuilder->andWhere($expr->orX(
                $expr->like('author.first_name', ':name'),
                $expr->like('author.second_name', ':name'),
            ))->setParameter('name', '%' . $data['name'] . '%');
        }
        if (isset($data['country'])) {
            $queryBuilder->andWhere($expr->like('author.country', ':country'))
                ->setParameter('country', '%'. $data['country'] . '%');
        }

        $paginator = new Paginator($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems/$itemsPerPage);

        $paginator->getQuery()
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);

        $authors = $paginator->getQuery()->getResult();
        $authorsArray = array_map(static function (Author $author): array {
            return [
                'id'          => $author->getId(),
                'first_name'  => $author->getFirstName(),
                'second_name' => $author->getSecondName(),
                'country'     => $author->getCountry(),
            ];
        }, $authors);

        return [
            'authors' => $authorsArray,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];

    }

}
