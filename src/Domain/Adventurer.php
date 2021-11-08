<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\GpsCoordinates;

class Adventurer
{
    private Card $card;
    private GpsCoordinates $initialCoordinates;
    private GpsCoordinates $finalCoordinates;

    /**
     * @param Card $card
     * @param GpsCoordinates $initialCoordinates
     */
    public function __construct(Card $card, GpsCoordinates $initialCoordinates)
    {
        $this->card = $card;
        $this->initialCoordinates = $initialCoordinates;
        // we consider that on init initial and final coordinates are same
        $this->finalCoordinates = $initialCoordinates;
    }

    public function canMoveFrom(GpsCoordinates $gpsCoordinates): bool
    {
        return $gpsCoordinates->getLatitude() >= 0
            && $gpsCoordinates->getLongitude() >= 0
            && $gpsCoordinates->getLatitude() <= $this->card->getMaxLatitude()
            && $gpsCoordinates->getLongitude() <= $this->card->getMaxLongitude();
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
