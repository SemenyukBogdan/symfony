<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ReturnsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Borrowing;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post(), new Patch(), new Delete()],
    normalizationContext: ['groups' => ['returns:read']],
    denormalizationContext: ['groups' => ['returns:write']],
)]
#[ORM\Entity(repositoryClass: ReturnsRepository::class)]
class Returns
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['returns:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups(['returns:read','returns:write'])]
    private ?Borrowing $borrowing = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    #[Groups(['returns:read','returns:write'])]
    private ?\DateTimeInterface $return_date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['returns:read','returns:write'])]
    private ?string $condition_state = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[Groups(['returns:read','returns:write'])]
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
