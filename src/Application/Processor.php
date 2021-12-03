<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\ValueNotAllowedException;
use App\Domain\Adventurer;
use App\Domain\GpsCoordinatesMapper;
use App\Domain\Movement\Movement;
use App\Domain\Repository\GpsCoordinatesRepositoryInterface;

class Processor
{
    private GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository;
    private array $moved = [];
    private string $movingSequences = "";
    private array $movements = [];
    private GpsCoordinatesMapper $gpsCoordinatesMapper;

    public function __construct(
        GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository,
        string $movingSequences,
        array $movements,
        GpsCoordinatesMapper $gpsCoordinatesMapper
    ) {
        $this->gpsCoordinatesRepository = $gpsCoordinatesRepository;
        $this->movements = $movements;
        $this->gpsCoordinatesMapper = $gpsCoordinatesMapper;
        $this->movingSequences = $movingSequences;
    }

    /**
     * @param Adventurer $adventurer
     * @return array
     * @throws ValueNotAllowedException
     */
    public function process(Adventurer $adventurer): array
    {
        $directions = $this->buildDirections($this->movingSequences);

        foreach ($directions as $direction) {

            if (true === empty($this->movements)) {
                return [];
            }

            /** @var Movement $movement */
            foreach ($this->movements as $movement) {
                if (true === $movement->isApplicable($direction)) {
                    $movement->move($adventurer);
                }
            }

            $adventurerFinalCoordinates = $adventurer->getFinalCoordinates();

            if (false === $adventurer->canMoveFrom($adventurerFinalCoordinates)) {
                return $this->moved;
            }

            $currentLatitude = $adventurerFinalCoordinates->getLatitude();
            $currentLongitude = $adventurerFinalCoordinates->getLongitude();

            $nextMove = $this->gpsCoordinatesRepository
                ->findOneByLatitudeAndLongitude($currentLatitude, $currentLongitude);

            if (null === $nextMove) {
                return $this->moved;
            }

            array_push($this->moved, $this->gpsCoordinatesMapper->toString($adventurerFinalCoordinates));
        }

        return $this->moved;
    }

    /**
     * @param string $movingSequences
     * @return array|false
     * @throws ValueNotAllowedException
     */
    private function buildDirections(string $movingSequences)
    {
        $directions = str_split($movingSequences);

        $this->assertAllowedValuesForDirections($directions);

        return $directions;
    }

    private function assertAllowedValuesForDirections(array $directions)
    {
        $allowedValues = [
            Movement::WEST,
            Movement::SOUTH,
            Movement::NORTH,
            Movement::EAST,
        ];

        foreach ($directions as $direction) {
            if (false === in_array($direction, $allowedValues)) {
                throw new ValueNotAllowedException("The value {$direction} is not allowed for direction");
            }
        }
    }
}
