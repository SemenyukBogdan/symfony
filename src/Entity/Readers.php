<?php

namespace App\Entity;

use App\Entity\Borrowings;
use App\Repository\ReadersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReadersRepository::class)]
class Readers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $full_name = null;

    #[ORM\Column(length: 12,unique: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $registration_date = null;

    /**
     * @var Collection<int, Borrowings>
     */
    #[ORM\OneToMany(mappedBy: 'reader', targetEntity: Borrowings::class)]
    private Collection $borrowings;

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTime
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTime $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    /**
     * @return Collection<int, Borrowings>
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }



    public function addBorrowing(Borrowings $borrowing): static
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings->add($borrowing);
            $borrowing->setReader($this); // ВАЖНО: ставим этого читателя
        }

        return $this;
    }

    public function removeBorrowing(Borrowings $borrowing): static
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // если у выдачи до сих пор этот reader – обнуляем
            if ($borrowing->getReader() === $this) {
                $borrowing->setReader(null); // если nullable=false, просто не будешь вызывать remove, и всё
            }
        }

        return $this;
    }

}
