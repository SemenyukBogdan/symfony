<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author_id = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Genre $пgenre_id = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Publisher $publisher_id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $year = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, BookCopy>
     */
    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book_id')]
    private Collection $book_copy_id;

    public function __construct()
    {
        $this->book_copy_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthorId(): ?Author
    {
        return $this->author_id;
    }

    public function setAuthorId(?Author $author_id): static
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getпgenreId(): ?Genre
    {
        return $this->пgenre_id;
    }

    public function setпgenreId(?Genre $пgenre_id): static
    {
        $this->пgenre_id = $пgenre_id;

        return $this;
    }

    public function getPublisherId(): ?Publisher
    {
        return $this->publisher_id;
    }

    public function setPublisherId(?Publisher $publisher_id): static
    {
        $this->publisher_id = $publisher_id;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, BookCopy>
     */
    public function getBookCopyId(): Collection
    {
        return $this->book_copy_id;
    }

    public function addBookCopyId(BookCopy $bookCopyId): static
    {
        if (!$this->book_copy_id->contains($bookCopyId)) {
            $this->book_copy_id->add($bookCopyId);
            $bookCopyId->setBookId($this);
        }

        return $this;
    }

    public function removeBookCopyId(BookCopy $bookCopyId): static
    {
        if ($this->book_copy_id->removeElement($bookCopyId)) {
            // set the owning side to null (unless already changed)
            if ($bookCopyId->getBookId() === $this) {
                $bookCopyId->setBookId(null);
            }
        }

        return $this;
    }
}
