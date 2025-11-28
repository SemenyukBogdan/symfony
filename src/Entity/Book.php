<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['book:read:collection']]),
        new Post(
            normalizationContext: ['groups' => ['book:read:item']],
            denormalizationContext: ['groups' => ['book:write']],
        ),        new Get(normalizationContext: ['groups' => ['book:read:item']]),
        new Patch(
            normalizationContext: ['groups' => ['book:read:item']],
            denormalizationContext: ['groups' => ['book:write']],
        ),
        new Delete(),
    ]
)]

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read:collection', 'book:read:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['book:read:collection', 'book:read:item', 'book:write'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read:item', 'book:write'])]
    private ?Author $author_id = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(['book:read:item', 'book:write'])]
    private ?Genre $genre_id = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[Groups(['book:read:item', 'book:write'])]
    private ?Publisher $publisher_id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['book:read:collection', 'book:read:item', 'book:write'])]
    private ?int $year = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['book:read:item', 'book:write'])]
    private ?string $description = null;

    /**
     * @var Collection<int, BookCopy>
     */
    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book_id')]
    #[Groups(['book:read:item'])]
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

    public function getGenreId(): ?Genre
    {
        return $this->genre_id;
    }

    public function setGenreId(?Genre $genre_id): static
    {
        $this->genre_id = $genre_id;

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
