<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    //    /**
    //     * @return Genres[] Returns an array of Genres objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Genres
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
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
    ])] public function getAllGenresByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('genre');
        if (isset($data['name'])) {
            $queryBuilder->andWhere('genre.name LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }
        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);



        $genre = $paginator->getQuery()->getResult();

        $genreArray = array_map(static function (Genre $genre): array {
            return [
                'id'          => $genre->getId(),
                'name'          => $genre->getName(),
            ];
        }, $genre);

        return [
            'genres' => $genreArray,
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems
        ];
    }
}
