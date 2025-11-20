<?php
namespace App\Services\GenresService;
use App\Entity\Genre;
use App\Services\RequestCheckerService;
use Doctrine\ORM\EntityManagerInterface;
class GenresService
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
     * @return Genre
     */
    public function createGenre(
        string $name,
    ): Genre {
        $Genres = $this->createGenreObject($name);
        $this->requestCheckerService->validateRequestDataByConstraints($Genres);
        $this->entityManager->persist($Genres);
        return $Genres;
    }
    /**
     * @param string $name
     * @return Genre
     */
    private function createGenreObject(
        string $name,
    ): Genre {
        $Genres = new Genre();
        $Genres->setName($name);

        return $Genres;
    }
    /**
     * @param Genre $genres
     * @param array $data
     * @return void
     */
    public function updateGenre(Genre $genres, array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (!method_exists($genres, $method)) {
                continue;
            }
            $genres->$method($value);
        }
        $this->requestCheckerService->validateRequestDataByConstraints($genres);
    }
}