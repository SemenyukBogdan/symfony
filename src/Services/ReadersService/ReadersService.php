<?php
namespace App\Services\ReadersService;
use App\Entity\Reader;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class ReadersService
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
     * @param string $phone
     * @param string $email
     * @return Reader
     */
    public function createReader(
        string $full_name,
        string $phone,
        string $email,
    ): Reader {
        $reader = $this->createReaderObject($full_name, $phone, $email);
        $this->requestCheckerService->validateRequestDataByConstraints($reader);
        $this->entityManager->persist($reader);
        return $reader;
    }

    /**
     * @param string $full_name
     * @param string $phone
     * @param string $email
     * @return Reader
     */
    private function createReaderObject(
        string $full_name,
        string $phone,
        string $email,
    ): Reader {
        $reader = new Reader();
        $registration_date = new \DateTime();
        $reader
            ->setFullName($full_name)
            ->setPhone($phone)
            ->setEmail($email)
            ->setRegistrationDate($registration_date);
        return $reader;
    }
    /**
     * @param Reader $reader
     * @param array $data
     * @return void
     */
    public function updateReader(Reader $reader, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($reader, $method)) {
                continue;
            }
            $reader->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($reader);
    }
}