<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\BookCopy;
use App\Entity\Librarian;
use App\Entity\Reader;
use App\Repository\BorrowingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post(), new Patch(), new Delete()],
    normalizationContext: ['groups' => ['borrowing:read']],
    denormalizationContext: ['groups' => ['borrowing:write']],
)]

#[ORM\Entity(repositoryClass: BorrowingsRepository::class)]
#[ORM\Table(name: 'borrowings')]
class Borrowing
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['borrowing:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: BookCopy::class, inversedBy: 'borrowings')]
    #[ORM\JoinColumn(name: 'book_copy_id', nullable: false)]
    #[Groups(['borrowing:read','borrowing:write'])]
    private ?BookCopy $bookCopy = null;

    #[ORM\ManyToOne(targetEntity: Reader::class, inversedBy: 'borrowings')]
    #[ORM\JoinColumn(name: 'reader_id', nullable: false)]
    #[Groups(['borrowing:read','borrowing:write'])]
    private ?Reader $reader = null;

    #[ORM\ManyToOne(targetEntity: Librarian::class, inversedBy: 'borrowings')]
    #[Groups(['borrowing:read','borrowing:write'])]
    #[ORM\JoinColumn(name: 'librarian_id', nullable: false)]
    private ?Librarian $librarian = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['borrowing:read','borrowing:write'])]
    #[Assert\NotNull]
    private ?\DateTimeInterface $borrowDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['borrowing:read','borrowing:write'])]
    #[Assert\NotNull]
    private ?\DateTimeInterface $dueDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookCopy(): ?BookCopy
    {
        return $this->bookCopy;
    }

    public function setBookCopy(BookCopy $bookCopy): static
    {
        $this->bookCopy = $bookCopy;

        return $this;
    }

    public function getReader(): ?Reader
    {
        return $this->reader;
    }

    public function setReader(?Reader $reader): static
    {
        $this->reader = $reader;

        return $this;
    }

    public function getLibrarian(): ?Librarian
    {
        return $this->librarian;
    }

    public function setLibrarian(?Librarian $librarian): static
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
