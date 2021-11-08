<?php

declare(strict_types=1);

namespace App\Domain\Movement;

use App\Domain\Adventurer;

interface Movement
{
    public function isApplicable(string $currentDirection): bool;

    public function move(Adventurer $adventurer);
}
