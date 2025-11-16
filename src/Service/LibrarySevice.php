<?php

namespace App\Service;

use App\Entity\Authors;
use App\Entity\Readers;
use App\Entity\BookCopies;
use App\Entity\Borrowings;
use App\Entity\Books;
use App\Entity\Genres;
use App\Entity\Librarians;
use App\Entity\BookReviews;
use App\Entity\Publishers;
use App\Entity\Returns;
use Doctrine\ORM\EntityManagerInterface;


class LibrarySevice
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAuthor(Authors $author): void
    {
        $this->entityManager->persist($author);
        $this->entityManager->flush();
    }
 public function createBookCopy(BookCopies $bookCopies): void
    {
        $this->entityManager->persist($bookCopies);
        $this->entityManager->flush();
    }

    public function createBookReview(BookReviews $bookReview): void
    {
        $this->entityManager->persist($bookReview);
        $this->entityManager->flush();
    }

    public function createReader(Readers $reader): void
    {
        $this->entityManager->persist($reader);
        $this->entityManager->flush();
    }

    public function createBook(Books $book): void
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function createBorrowing(Borrowings $borrowing): void
    {
        $this->entityManager->persist($borrowing);
        $this->entityManager->flush();
    }

    public function createGenres(Genres $genres): void
    {
        $this->entityManager->persist($genres);
        $this->entityManager->flush();
    }
    public function createLibrarian(Librarians $librarian): void{
        $this->entityManager->persist($librarian);
        $this->entityManager->flush();
    }
    public function createPublisher(Publishers $publisher): void{
        $this->entityManager->persist($publisher);
        $this->entityManager->flush();
    }
    public function createReturn(Returns $return): void{
        $this->entityManager->persist($return);
        $this->entityManager->flush();
    }

}