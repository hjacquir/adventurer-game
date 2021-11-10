<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\GpsCoordinates;

class Adventurer
{
    private Map $map;
    private GpsCoordinates $initialCoordinates;
    private GpsCoordinates $finalCoordinates;

    /**
     * @param Map $map
     * @param GpsCoordinates $initialCoordinates
     */
    public function __construct(Map $map, GpsCoordinates $initialCoordinates)
    {
        $this->map = $map;
        $this->initialCoordinates = $initialCoordinates;
        // we consider that on init : initial and final coordinates are same
        $this->finalCoordinates = $initialCoordinates;
    }

    public function canMoveFrom(GpsCoordinates $gpsCoordinates): bool
    {
        return $gpsCoordinates->getLatitude() >= 0
            && $gpsCoordinates->getLongitude() >= 0
            && $gpsCoordinates->getLatitude() <= $this->map->getMaxLatitude()
            && $gpsCoordinates->getLongitude() <= $this->map->getMaxLongitude();
    }

    public function getInitialCoordinates(): GpsCoordinates
    {
        return $this->initialCoordinates;
    }

    public function getFinalCoordinates(): GpsCoordinates
    {
        return $this->finalCoordinates;
    }
}
