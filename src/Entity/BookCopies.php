<?php

namespace App\Entity;

use App\Entity\Books;
use App\Repository\BookCopiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookCopiesRepository::class)]
class BookCopies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'book_copy_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?books $book_id = null;

    #[ORM\Column]
    private ?int $inventory_number = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shelf_location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?books
    {
        return $this->book_id;
    }

    public function setBookId(?books $book_id): static
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getInventoryNumber(): ?int
    {
        return $this->inventory_number;
    }

    public function setInventoryNumber(int $inventory_number): static
    {
        $this->inventory_number = $inventory_number;

        return $this;
    }

    public function getShelfLocation(): ?string
    {
        return $this->shelf_location;
    }

    public function setShelfLocation(string $shelf_location): static
    {
        $this->shelf_location = $shelf_location;

        return $this;
    }
}
