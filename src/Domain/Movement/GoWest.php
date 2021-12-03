<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

class GoWest implements Movement
{
    public function isApplicable(string $currentDirection): bool
    {
        return $currentDirection === Movement::WEST;
    }

    public function move(Adventurer $adventurer)
    {
        $currentValue = $adventurer->getInitialCoordinates()->getLongitude();
        $currentValue--;

        $adventurer->getFinalCoordinates()->setLongitude($currentValue);
    }
}
