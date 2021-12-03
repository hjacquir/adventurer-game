<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

class GoEast implements Movement
{
    public function isApplicable(string $currentDirection): bool
    {
        return $currentDirection === Movement::EAST;
    }

    public function move(Adventurer $adventurer)
    {
        $currentValue = $adventurer->getInitialCoordinates()->getLongitude();
        $currentValue++;

        $adventurer->getFinalCoordinates()->setLongitude($currentValue);
    }
}
