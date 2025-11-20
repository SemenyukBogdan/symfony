<?php
namespace App\Services\BookCopiesService;
use App\Entity\Book;
use App\Entity\BookCopy;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class BookCopiesService
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
     * @param int $book_id
     * @param int $inventory_number
     * @param int $shelf_location
     * @param string $status
     * @return BookCopy
     */
    public function createBookCopy(
        int $book_id,
        int $inventory_number,
        int $shelf_location,
        string $status
    ): BookCopy {
        $bookCopy = $this->createBookCopyObject($book_id,$inventory_number,$shelf_location,$status);
        $this->requestCheckerService->validateRequestDataByConstraints($bookCopy);
        $this->entityManager->persist($bookCopy);
        return $bookCopy;
    }
    /**
     * @param int $book_id
     * @param int $inventory_number
     * @param int $shelf_location
     * @param string $status
     * @return BookCopy
     */
    private function createBookCopyObject(
        int $book_id,
        int $inventory_number,
        int $shelf_location,
        string $status
    ): BookCopy {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $book = $bookRepository->find($book_id);

        $bookCopy = new BookCopy();
        $bookCopy->setBookId($book)->setInventoryNumber($inventory_number)->setShelfLocation($shelf_location);
        return $bookCopy;
    }
    /**
     * @param BookCopy $BookCopy
     * @param array $data
     * @return void
     */
    public function updateBookCopy(BookCopy $BookCopy, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($BookCopy, $method)) {
                continue;
            }
            $BookCopy->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($BookCopy);
    }
}