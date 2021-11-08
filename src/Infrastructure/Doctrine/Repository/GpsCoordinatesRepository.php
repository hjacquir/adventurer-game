<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\GpsCoordinates;
use App\Domain\Repository\GpsCoordinatesRepositoryInterface;
use App\Infrastructure\Exception\DoctrinePersistenceException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class GpsCoordinatesRepository implements GpsCoordinatesRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function save(GpsCoordinates $map): void
    {
        try {
            $this->entityManager->persist($map);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->logger
                ->error(
                    'An exception occurred when trying to persist the map : ' . $e->getMessage(),
                );

            throw new DoctrinePersistenceException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function findOneByLatitudeAndLongitude(int $latitude, int $longitude): ?GpsCoordinates
    {
        return $this->entityManager->getRepository(GpsCoordinates::class)
            ->findOneBy([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    public function getMaxLatitude(): int
    {
        return $this->entityManager->getRepository(GpsCoordinates::class)
            ->createQueryBuilder('g')
            ->select('MAX(g.latitude)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMaxLongitude(): int
    {
        return $this->entityManager->getRepository(GpsCoordinates::class)
            ->createQueryBuilder('g')
            ->select('MAX(g.longitude)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
