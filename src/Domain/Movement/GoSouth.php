<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

class GoSouth implements Movement
{
    public function isApplicable(string $currentDirection): bool
    {
        return $currentDirection === 'S';
    }

    public function move(Adventurer $adventurer)
    {
        $currentValue = $adventurer->getInitialCoordinates()->getLatitude();
        $currentValue++;

        $adventurer->getFinalCoordinates()->setLatitude($currentValue);
    }
}
