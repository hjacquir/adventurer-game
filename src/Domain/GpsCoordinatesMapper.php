<?php

namespace App\Domain;

use App\Domain\Model\GpsCoordinates;

class GpsCoordinatesMapper
{
    private const SEPARATOR = ',';

    public function fromString(string $gpsCoordinatesAsString): GpsCoordinates
    {
        $explodedCoordinates = explode(self::SEPARATOR, $gpsCoordinatesAsString);

        // string is empty, or other -> we return an 0,0 gps coordinates
        if (count($explodedCoordinates) === 1) {
            $latitude = 0;
            $longitude = 0;

            return new GpsCoordinates($latitude, $longitude);
        }

        $latitude = (int) $explodedCoordinates[0];
        $longitude = (int) $explodedCoordinates[1];

        return new GpsCoordinates($latitude, $longitude);
    }

    public function toString(GpsCoordinates $gpsCoordinates): string
    {
        return $gpsCoordinates->getLatitude() .
            self::SEPARATOR .
            $gpsCoordinates->getLongitude();
    }
}