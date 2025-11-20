<?php
namespace App\Services\BookReviewsService;

use App\Entity\Book;
use App\Entity\BookReview;
use App\Entity\Reader;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class BookReviewsService
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
     * @param int $reader_id
     * @param int $rating
     * @param string $comment
     * @return BookReview
     */
    public function createBookReview(
       int $book_id,
       int $reader_id,
       int $rating,
       string $comment,
    ): BookReview {
        $BookReviews = $this->createBookReviewObject($book_id, $reader_id, $rating, $comment);
        $this->requestCheckerService->validateRequestDataByConstraints($BookReviews);
        $this->entityManager->persist($BookReviews);
        return $BookReviews;
    }
    /**
     * @param int $book_id
     * @param int $reader_id
     * @param int $rating
     * @param string $comment
     * @return BookReview
     */
    private function createBookReviewObject(
        int $book_id,
        int $reader_id,
        int $rating,
        string $comment,
    ): BookReview {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $readerRepository = $this->entityManager->getRepository(Reader::class);

        $reader = $bookRepository->find($reader_id);
        $book = $readerRepository->find($book_id);

        $createdAt = new \DateTime();
        $bookReview = new BookReview();
        $bookReview->setBook(book: $book)->setReaderId($reader)->setRating($rating)->setComment($comment)->setCreatedAt($createdAt);

        return $bookReview;
    }
    /**
     * @param BookReview $BookReview
     * @param array $data
     * @return void
     */
    public function updateBookReview(BookReview $BookReview, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($BookReview, $method)) {
                continue;
            }
            $BookReview->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($BookReview);
    }
}