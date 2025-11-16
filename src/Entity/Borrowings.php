<?php

namespace App\Entity;

use App\Entity\BookCopies;
use App\Entity\Librarians;
use App\Entity\Readers;
use App\Repository\BorrowingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowingsRepository::class)]
#[ORM\Table(name: 'borrowings')]
class Borrowings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: BookCopies::class, inversedBy: 'borrowings')]
    #[ORM\JoinColumn(name: 'book_copy_id', nullable: false)]
    private ?BookCopies $bookCopy = null;

    #[ORM\ManyToOne(targetEntity: Readers::class, inversedBy: 'borrowings')]
    #[ORM\JoinColumn(name: 'reader_id', nullable: false)]
    private ?Readers $reader = null;

    #[ORM\ManyToOne(targetEntity: Librarians::class, inversedBy: 'borrowings')]
    #[ORM\JoinColumn(name: 'librarian_id', nullable: false)]
    private ?Librarians $librarian = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $borrowDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dueDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookCopy(): ?BookCopies
    {
        return $this->bookCopy;
    }

    public function setBookCopy(BookCopies $bookCopy): static
    {
        $this->bookCopy = $bookCopy;

        return $this;
    }

    public function getReader(): ?Readers
    {
        return $this->reader;
    }

    public function setReader(?Readers $reader): static
    {
        $this->reader = $reader;

        return $this;
    }

    public function getLibrarian(): ?Librarians
    {
        return $this->librarian;
    }

    public function setLibrarian(?Librarians $librarian): static
    {
        $this->librarian = $librarian;

        return $this;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeInterface $borrowDate): static
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }
}
