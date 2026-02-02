<?php
namespace App\Services\LibrariansService;
use App\Entity\Librarian;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class LibrariansService
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
     * @param string $full_name
     * @param string $position
     * @param string $phone
     * @return Librarian
     */
    public function createLibrarian(
        string $full_name,
        string $position,
        string $phone
    ): Librarian {
        $librarians = $this->createLibrarianObject($full_name, $position, $phone);
        $this->requestCheckerService->validateRequestDataByConstraints($librarians);
        $this->entityManager->persist($librarians);
        return $librarians;
    }

    /**
     * @param string $full_name
     * @param string $position
     * @param string $phone
     * @return Librarian
     */
    private function createLibrarianObject(
        string $full_name,
        string $position,
        string $phone
    ): Librarian {
        $librarians = new Librarian();
        $librarians
            ->setFullName($full_name)
            ->setPosition($position)
            ->setPhone($phone);
        return $librarians;
    }
    /**
     * @param Librarian $librarian
     * @param array $data
     * @return void
     */
    public function updateLibrarian(Librarian $librarian, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($librarian, $method)) {
                continue;
            }
            $librarian->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($librarian);
    }
}