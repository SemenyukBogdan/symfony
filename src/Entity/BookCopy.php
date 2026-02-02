<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Book;
use App\Repository\BookCopiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post(), new Patch(), new Delete()],
    normalizationContext: ['groups' => ['bookCopy:read']],
    denormalizationContext: ['groups' => ['bookCopy:write']],
)]


#[ORM\Entity(repositoryClass: BookCopiesRepository::class)]
class BookCopy
{
    #[ORM\OneToMany(targetEntity: Borrowing::class, mappedBy: 'bookCopy')]
    private Collection $borrowings;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'book_copy_id')]
    #[Groups(['bookCopy:read'])]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book_id = null;

    #[ORM\Column]
    #[Groups(['bookCopy:read', 'bookCopy:write'])]
    private ?int $inventory_number = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['bookCopy:read', 'bookCopy:write'])]
    private ?string $shelf_location = null;

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?Book
    {
        return $this->book_id;
    }

    public function setBookId(?Book $book_id): static
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



    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): static
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings->add($borrowing);
            $borrowing->setBookCopy($this);
        }
        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): static
    {
        $this->borrowings->removeElement($borrowing);
        return $this;
    }

}
