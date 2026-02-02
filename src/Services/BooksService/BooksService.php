<?php
namespace App\Services\BooksService;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Publisher;
use App\Entity\Reader;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class BooksService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService
    ) {$this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
    }

    /**
     * @param string $title
     * @param string $author_id
     * @param string $genre_id
     * @param string $publisher_id
     * @param int $year
     * @param string $description
     * @return Book
     */
    public function createBook(
        string $title,
        string $author_id,
        string $genre_id,
        string $publisher_id,
        int $year,
        string $description
    ): Book {
        $Books = $this->createBookObject($title,$author_id,$genre_id,$publisher_id,$description,$year);
        $this->requestCheckerService->validateRequestDataByConstraints($Books);
        $this->entityManager->persist($Books);
        return $Books;
    }

    /**
     * @param string $title
     * @param string $author_id
     * @param string $genre_id
     * @param string $publisher_id
     * @param int $year
     * @param string $description
     * @return Book
     */
    private function createBookObject(
        string $title,
        string $author_id,
        string $genre_id,
        string $publisher_id,
        string $description,
        int $year
    ): Book {
        $authorRepository = $this->entityManager->getRepository(Author::class);
        $genreRepository = $this->entityManager->getRepository(Genre::class);
        $publisherRepository = $this->entityManager->getRepository(Publisher::class);

        $author = $authorRepository->find($author_id);
        $genre = $genreRepository->find($genre_id);
        $publisher = $publisherRepository->find($publisher_id);

        $Books = new Book();
        $Books
            ->setTitle($title)
        ->setAuthorId($author)
        ->setGenreId($genre)
        ->setPublisherId($publisher)
        ->setYear($year)
        ->setDescription($description);
        return $Books;
    }
    /**
     * @param Book $Books
     * @param array $data
     * @return void
     */
    public function updateBook(Book $Books, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($Books, $method)) {
                continue;
            }
            $Books->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($Books);
    }
}