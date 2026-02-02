<?php
namespace App\Services\PublishersService;
use App\Entity\Publisher;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class PublishersService
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
     * @param string $name
     * * @param string $country
     * * @param int $founded_year
     * @return Publisher
     */
    public function createPublishers(
        string $name,
        string $country,
        int    $founded_year
    ): Publisher {
        $publisher = $this->createPublisherObject($name, $country, $founded_year);
        $this->requestCheckerService->validateRequestDataByConstraints($publisher);
        $this->entityManager->persist($publisher);
        return $publisher;
    }
    /**
     * @param string $name
     * @param string $country
     * @param int $founded_year
     * @return Publisher
     */
    private function createPublisherObject(
        string $name,
        string $country,
        int    $founded_year
    ): Publisher {
        $publisher = new Publisher();
        $publisher
            ->setName($name)
            ->setCountry($country)
            ->setFoundedYear($founded_year);
        return $publisher;
    }
    /**
     * @param Publisher $publisher
     * @param array $data
     * @return void
     */
    public function updatePublisher(Publisher $publisher, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($publisher, $method)) {
                continue;
            }
            $publisher->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($publisher);
    }
}