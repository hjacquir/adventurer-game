<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Repository\GpsCoordinatesRepositoryInterface;

class Card
{
    private ?int $maxLatitude = null;
    private ?int $maxLongitude = null;
    private GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository;

    /**
     * @param GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository
     */
    public function __construct(GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository)
    {
        $this->gpsCoordinatesRepository = $gpsCoordinatesRepository;
    }

    public function getMaxLatitude(): ?int
    {
        if (null !== $this->maxLatitude) {
            return $this->maxLatitude;
        }

        return $this->gpsCoordinatesRepository->getMaxLatitude();
    }

    public function getMaxLongitude(): ?int
    {
        if (null !== $this->maxLongitude) {
            return $this->maxLongitude;
        }

        return $this->gpsCoordinatesRepository->getMaxLongitude();
    }
}
