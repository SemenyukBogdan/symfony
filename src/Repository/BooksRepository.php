<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Books[] Returns an array of Books objects
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

    //    public function findOneBySomeField($value): ?Books
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    //// to do Переменувати entity в infoOfBooks




    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return mixed
     */
    #[ArrayShape([
        'books' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])] public function getAllBooksByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('book');
        if (isset($data['title'])) {
            $queryBuilder->andWhere('book.title LIKE :title')
                ->setParameter('title', '%' . $data['title'] . '%');
        }
        $paginator = new Paginator ($queryBuilder);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        $paginator
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);





        $books = $paginator->getQuery()->getResult();

        $booksArray = array_map(static function (book $book): array {
            return [
                'id'          => $book->getId(),
                'getTitle'  => $book->getTitle(),
                'getDescription'     => $book->getDescription(),
                'year'     => $book->getYear(),
            ];
        }, $books);

        return [
            'books'        => $booksArray,
            'totalPageCount' => $pagesCount,
            'totalItems'     => $totalItems,
        ];

    }
}
