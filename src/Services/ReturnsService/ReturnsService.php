<?php
namespace App\Services\ReturnsService;
use App\Entity\Borrowing;
use App\Entity\Returns;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class ReturnsService
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
     * @param string $borrowing_id
     * @param string $return_date
     * @param string $condition
     * @param string $fine_amount
     * @return Returns
     * @throws \Exception
     */
    public function createReturn(
        string $borrowing_id,
        string $return_date,
        string $condition,
        string $fine_amount
    ): Returns {
        $returns = $this->createReturnObject($borrowing_id,$return_date,$condition,$fine_amount);
        $this->requestCheckerService->validateRequestDataByConstraints($returns);
        $this->entityManager->persist($returns);
        return $returns;
    }

    /**
     * @param string $borrow_id
     * @param string $return_date
     * @param string $condition
     * @param float $fine_amount
     * @return Returns
     * @throws \Exception
     */
    private function createReturnObject(
        string $borrow_id,
        string $return_date,
        string $condition,
        float $fine_amount
    ): Returns {
        $returns = new Returns();
        $return_date = new \DateTime($return_date);
        $borrowingRepository = $this->entityManager->getRepository(Borrowing::class);
        $borrowing = $borrowingRepository->find($borrow_id);

        $returns->setBookCondition($condition)
            ->setReturnDate($return_date)
            ->setBorrowing($borrowing)
            ->setFineAmount($fine_amount);
        return $returns;
    }
    /**
     * @param Returns $returns
     * @param array $data
     * @return void
     */
    public function updateReturn(Returns $returns, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($returns, $method)) {
                continue;
            }
            $returns->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($returns);
    }
}