<?php
namespace App\Services\BorrowingsService;
use App\Entity\BookCopy;
use App\Entity\Borrowing;
use App\Entity\Librarian;
use App\Entity\Reader;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class BorrowingsService
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
     * @param string $name
     * @param string $description
     * @param string $price
     * @param string $slug
     * @return Borrowing
     */
    public function createBorrowing(
        int $bookCopy_id,
        int $reader_id,
        int $librarian_id,
        string $borrow_date,
        string $due_date
    ): Borrowing {
        $borrowings = $this->createBorrowingObject($bookCopy_id, $reader_id, $librarian_id, $borrow_date, $due_date);
        $this->requestCheckerService->validateRequestDataByConstraints($borrowings);
        $this->entityManager->persist($borrowings);
        return $borrowings;
    }

    /**
     * @param int $bookCopy_id
     * @param int $reader_id
     * @param int $librarian_id
     * @param \DateTime $borrow_date
     * @param \DateTime $due_date
     * @return Borrowing
     */
    private function createBorrowingObject(
        int $bookCopy_id,
        int $reader_id,
        int $librarian_id,
        string $borrow_date,
        string $due_date
    ): Borrowing {
        $borrowings = new Borrowing();
        $bookCopy = $this->entityManager->getRepository(BookCopy::class)->find($bookCopy_id);
        $reader = $this->entityManager->getRepository(Reader::class)->find($reader_id);
        $librarian = $this->entityManager->getRepository(Librarian::class)->find($librarian_id);
        $borrow_date = new \DateTime($borrow_date);
        $due_date = new \DateTime($due_date);
        $borrowings
            ->setBookCopy($bookCopy)
            ->setReader($reader)
            ->setLibrarian($librarian)
            ->setBorrowDate($borrow_date)
            ->setDueDate($due_date);
        return $borrowings;
    }
    /**
     * @param Borrowing $borrowings
     * @param array $data
     * @return void
     */
    public function updateBorrowing(Borrowing $borrowings, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($borrowings, $method)) {
                continue;
            }
            $borrowings->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($borrowings);
    }
}