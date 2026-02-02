<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Reader;
use App\Repository\BookReviewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post(), new Patch(), new Delete()],
    normalizationContext: ['groups' => ['bookReview:read']],
    denormalizationContext: ['groups' => ['bookReview:write']],
)]

#[ORM\Entity(repositoryClass: BookReviewsRepository::class)]
class BookReview
{


    #[Groups(['bookReview:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[Groups(['bookReview:read', 'bookReview:write' ])]

    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bookReview:read', 'bookReview:write' ])]

    private ?Reader $reader_id = null;

    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 5)]
    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['bookReview:read', 'bookReview:write' ])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['bookReview:read', 'bookReview:write' ])]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getReaderId(): ?Reader
    {
        return $this->reader_id;
    }

    public function setReaderId(?Reader $reader_id): static
    {
        $this->reader_id = $reader_id;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
