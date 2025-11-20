<?php
namespace App\Services\AuthorsService;
use App\Entity\Author;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
class AuthorsService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService
    ) {$this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
    }

    /**
     * @param string $first_name
     * @param string $second_name
     * @param DateTime $birth_date
     * @param string $country
     * @return Author
     */
    public function createAuthor(string $first_name,string $second_name, DateTime $birth_date,string $country
    ): Author {
        $author = $this->createAuthorObject($first_name,$second_name,$birth_date,$country);
        $this->requestCheckerService->validateRequestDataByConstraints($author);
        $this->entityManager->persist($author);
        return $author;
    }
    /**
     * @param string $first_name
     * @param string $second_name
     * @param DateTime $birth_date
     * @param string $country
     * @return Author
     */
    private function createAuthorObject(
        string $first_name,
        string $second_name,
        DateTime $birth_date,
        string $country
    ): Author {
        $author = new Author();
        $author
            ->setFirstName($first_name)
            ->setSecondName($second_name)
            ->setBirthDate($birth_date)
            ->setCountry($country);
        $this->requestCheckerService->validateRequestDataByConstraints($author);
        $this->entityManager->persist($author);

        return $author;
    }
    /**
     * @param Author $author
     * @param array $data
     * @return void
     */
    public function updateAuthor(Author $author, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($author, $method)) {
                continue;
            }
            $author->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($author);
    }
}