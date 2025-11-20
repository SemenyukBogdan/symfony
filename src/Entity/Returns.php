<?php

namespace App\Entity;

use App\Repository\ReturnsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Borrowing;
#[ORM\Entity(repositoryClass: ReturnsRepository::class)]
class Returns
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Borrowing $borrowing = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $return_date = null;

    #[ORM\Column(length: 255)]
    private ?string $condition_state = null;

    #[ORM\Column(type: 'float')]
    private ?float $fine_amount = null;

    public function getBorrowing(): ?Borrowing
    {
        return $this->borrowing;
    }

    public function setBorrowing(?Borrowing $borrowing): static
    {
        $this->borrowing = $borrowing;
        return $this;
    }

    public function getBookCondition(): ?string
    {
        return $this->condition_state;
    }

    public function setBookCondition(string $condition_state): static
    {
        $this->condition_state = $condition_state;
        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(\DateTimeInterface $return_date): static
    {
        $this->return_date = $return_date;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFineAmount(): ?float
    {
        return $this->fine_amount;
    }

    public function setFineAmount(float $fine_amount): static
    {
        $this->fine_amount = $fine_amount;
        return $this;
    }

}
