<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Adventurer;
use App\Domain\Movement\Movement;
use App\Domain\Repository\GpsCoordinatesRepositoryInterface;

class Processor
{
    private GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository;
    private array $moved = [];
    private array $directions = [];
    private array $movements = [];

    /**
     * @param GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository
     * @param array $directions
     * @param array $movements
     */
    public function __construct(
        GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository,
        array $directions,
        array $movements
    ) {
        $this->gpsCoordinatesRepository = $gpsCoordinatesRepository;
        $this->directions = $directions;
        $this->movements = $movements;
    }

    public function process(Adventurer $adventurer): array
    {
        foreach ($this->directions as $direction) {

            if (true === empty($this->movements)) {
                return [];
            }

            /** @var Movement $movement */
            foreach ($this->movements as $movement) {
                if (true === $movement->isApplicable($direction)) {
                    $movement->move($adventurer);
                }
            }

            if (false === $adventurer->canMoveFrom($adventurer->getFinalCoordinates())) {
                return $this->moved;
            }

            $currentLatitude = $adventurer->getFinalCoordinates()->getLatitude();
            $currentLongitude = $adventurer->getFinalCoordinates()->getLongitude();

            $nextMove = $this->gpsCoordinatesRepository
                ->findOneByLatitudeAndLongitude($currentLatitude, $currentLongitude);

            if (null === $nextMove) {
                return $this->moved;
            }

            array_push($this->moved, "{$currentLatitude},{$currentLongitude}");
        }

        return $this->moved;
    }
}
