<?php

namespace App\Entity;

use App\Entity\Book;
use App\Repository\AuthorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['author:read']],
    denormalizationContext: ['groups' => ['author:write']],
)]

#[ORM\Entity(repositoryClass: AuthorsRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['author:read'])]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['author:read', 'author:write'])]
    #[Assert\NotBlank]
    private ?string $first_name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['author:read', 'author:write'])]
    #[Assert\NotBlank]
    private ?string $second_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['author:read', 'author:write'])]
    #[Assert\NotNull]
    private ?\DateTime $birth_date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['author:read', 'author:write'])]
    #[Assert\NotBlank]
    private ?string $country = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'author_id')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->second_name;
    }

    public function setSecondName(string $second_name): static
    {
        $this->second_name = $second_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTime $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthorId($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthorId() === $this) {
                $book->setAuthorId(null);
            }
        }

        return $this;
    }

}
