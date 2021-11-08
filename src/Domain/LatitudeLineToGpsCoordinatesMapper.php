<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\GpsCoordinates;

class LatitudeLineToGpsCoordinatesMapper
{
    public function map(LatitudeLine $latitudeLine): \Generator
    {
        $line = $latitudeLine->getLine();
        $latitude = $latitudeLine->getLatitude();

        foreach (str_split($line) as $index => $item) {
            // we only pick the space
            if (" " === $item) {
                yield new GpsCoordinates($latitude, $index);
            }
        }
    }
}
