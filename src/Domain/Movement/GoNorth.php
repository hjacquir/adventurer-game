<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

class GoNorth implements Movement
{
    public function isApplicable(string $currentDirection): bool
    {
        return $currentDirection === 'N';
    }

    public function move(Adventurer $adventurer)
    {
        $currentValue = $adventurer->getInitialCoordinates()->getLatitude();
        $currentValue--;

        $adventurer->getFinalCoordinates()->setLatitude($currentValue);
    }
}
