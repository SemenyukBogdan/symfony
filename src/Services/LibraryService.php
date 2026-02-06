<?php

namespace App\Services;

use App\Entity\Author;
use App\Entity\BookCopy;
use App\Entity\BookReview;
use App\Entity\Book;
use App\Entity\Borrowing;
use App\Entity\Genre;
use App\Entity\Librarian;
use App\Entity\Publisher;
use App\Entity\Reader;
use App\Entity\Returns;
use Doctrine\ORM\EntityManagerInterface;


class LibraryService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function persistAndFlush(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function createAuthor(Author $author): void
    {
        $this->persistAndFlush($author);
    }
 public function createBookCopy(BookCopy $bookCopies): void
    {
        $this->persistAndFlush($bookCopies);
    }

    public function createBookReview(BookReview $bookReview): void
    {
        $this->persistAndFlush($bookReview);
    }

    public function createReader(Reader $reader): void
    {
        $this->persistAndFlush($reader);
    }

    public function createBook(Book $book): void
    {
        $this->persistAndFlush($book);
    }

    public function createBorrowing(Borrowing $borrowing): void
    {
        $this->persistAndFlush($borrowing);
    }

    public function createGenres(Genre $genres): void
    {
        $this->persistAndFlush($genres);
    }
    public function createLibrarian(Librarian $librarian): void{
        $this->persistAndFlush($librarian);
    }
    public function createPublisher(Publisher $publisher): void{
        $this->persistAndFlush($publisher);
    }
    public function createReturn(Returns $return): void{
        $this->persistAndFlush($return);
    }

}
