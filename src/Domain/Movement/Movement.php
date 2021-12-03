<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

interface Movement
{
    public const EAST = "E";
    public const WEST = "W";
    public const SOUTH = "S";
    public const NORTH = "N";

    public function isApplicable(string $currentDirection): bool;

    public function move(Adventurer $adventurer);
}
