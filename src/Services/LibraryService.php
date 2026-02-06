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

    public function createAuthor(Author $author): void
    {
        $this->entityManager->persist($author);
        $this->entityManager->flush();
    }
 public function createBookCopy(BookCopy $bookCopies): void
    {
        $this->entityManager->persist($bookCopies);
        $this->entityManager->flush();
    }

    public function createBookReview(BookReview $bookReview): void
    {
        $this->entityManager->persist($bookReview);
        $this->entityManager->flush();
    }

    public function createReader(Reader $reader): void
    {
        $this->entityManager->persist($reader);
        $this->entityManager->flush();
    }

    public function createBook(Book $book): void
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function createBorrowing(Borrowing $borrowing): void
    {
        $this->entityManager->persist($borrowing);
        $this->entityManager->flush();
    }

    public function createGenres(Genre $genres): void
    {
        $this->entityManager->persist($genres);
        $this->entityManager->flush();
    }
    public function createLibrarian(Librarian $librarian): void{
        $this->entityManager->persist($librarian);
        $this->entityManager->flush();
    }
    public function createPublisher(Publisher $publisher): void{
        $this->entityManager->persist($publisher);
        $this->entityManager->flush();
    }
    public function createReturn(Returns $return): void{
        $this->entityManager->persist($return);
        $this->entityManager->flush();
    }

}
