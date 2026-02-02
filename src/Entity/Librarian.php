<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Borrowing;
use App\Repository\LibrariansRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post(), new Patch(), new Delete()],
    normalizationContext: ['groups' => ['librarian:read']],
    denormalizationContext: ['groups' => ['librarian:write']],
)]
#[ORM\Entity(repositoryClass: LibrariansRepository::class)]
class Librarian implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['librarian:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['librarian:read','librarian:write'])]
    private ?string $full_name = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['librarian:read','librarian:write'])]
    private ?string $position = null;

    #[ORM\Column(length: 12,unique: true)]
    #[Groups(['librarian:read','librarian:write'])]
    private ?string $phone = null;


    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['librarian:read','librarian:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['librarian:read','librarian:write'])]
    private ?string $password = null;


    /**
     * @var Collection<int, Borrowing>
     */
    #[ORM\OneToMany(targetEntity: Borrowing::class, mappedBy: 'librarian')]
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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

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

    /**
     * @return Collection<int, Borrowing>
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): static
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings->add($borrowing);
            $borrowing->setLibrarianId($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): static
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getLibrarianId() === $this) {
                $borrowing->setLibrarianId(null);
            }
        }

        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getRoles(): array
    {
        return ['ROLE_LIBRARIAN'];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->getEmail();
    }
}
