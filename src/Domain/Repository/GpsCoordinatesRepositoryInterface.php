<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\GpsCoordinates;

interface GpsCoordinatesRepositoryInterface
{
    public function save(GpsCoordinates $map): void;

    public function findOneByLatitudeAndLongitude(int $latitude, int $longitude): ?GpsCoordinates;

    public function getMaxLatitude(): int;

    public function getMaxLongitude(): int;
}
