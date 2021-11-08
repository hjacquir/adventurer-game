<?php

declare(strict_types=1);

namespace App\Tests\Func\Application;

use App\Application\Processor;
use App\Domain\Adventurer;
use App\Domain\Card;
use App\Domain\Model\GpsCoordinates;
use App\Domain\Movement\GoEast;
use App\Domain\Movement\GoNorth;
use App\Domain\Movement\GoSouth;
use App\Domain\Movement\GoWest;
use App\Infrastructure\Doctrine\Repository\GpsCoordinatesRepository;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProcessorTest extends KernelTestCase
{
    private GpsCoordinatesRepository $gpsCoordinatesRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->gpsCoordinatesRepository = new GpsCoordinatesRepository($entityManager, new Logger('Test'));
    }

    /**
     * @dataProvider provideDataForProcess
     */
    public function testProcessReturnAnEmptyArrayWhenMovementAreEmpty(
        GpsCoordinates $initialCoordinates,
        array $directions,
        array $movements,
        array $expectedMoved
    ): void {
        // register possible coordinates
        $this->gpsCoordinatesRepository->save($initialCoordinates);
        $this->gpsCoordinatesRepository->save(new GpsCoordinates(2,0));
        $this->gpsCoordinatesRepository->save(new GpsCoordinates(2,1));
        $this->gpsCoordinatesRepository->save(new GpsCoordinates(2,2));

        $currentTested = new Processor(
            $this->gpsCoordinatesRepository,
            $directions,
            $movements
        );

        $adventurer = new Adventurer(
            new Card($this->gpsCoordinatesRepository),
            $initialCoordinates
        );

        $moved = $currentTested->process($adventurer);

        $this->assertSame($expectedMoved, $moved);
    }

    /**
     * @return \array[][]
     */
    public function provideDataForProcess()
    {
        return [
            'direction and movement are empty' => [
                new GpsCoordinates(1, 0),
                [],
                [],
                // moved is empty
                []
            ],
            'direction is empty' => [
                new GpsCoordinates(1, 0),
                [],
                [
                    new GoWest(),
                ],
                // moved is empty
                []
            ],
            'movement is empty' => [
                new GpsCoordinates(1, 0),
                [
                    'S',
                ],
                [],
                // moving is empty
                []
            ],
            'initial coordinates not found' => [
                new GpsCoordinates(10, 0),
                [
                    'S',
                ],
                [
                    new GoWest(),
                    new GoEast(),
                    new GoSouth(),
                    new GoNorth()
                ],
                // moved is empty
                []
            ],
            'initial coordinates found and moving with moving sequence exactly same as card' => [
                new GpsCoordinates(1, 0),
                [
                    'S',
                    'E',
                    'E',
                ],
                [
                    new GoWest(),
                    new GoEast(),
                    new GoSouth(),
                    new GoNorth()
                ],
                // moved is as expected
                [
                    '2,0',
                    '2,1',
                    '2,2',
                ]
            ],
            'initial coordinates found and moving with moving sequence > as card' => [
                new GpsCoordinates(1, 0),
                [
                    'S',
                    'E',
                    'E',
                    'E',
                    'N',
                ],
                [
                    new GoWest(),
                    new GoEast(),
                    new GoSouth(),
                    new GoNorth()
                ],
                // moved is as expected and stop on last move
                [
                    '2,0',
                    '2,1',
                    '2,2',
                ]
            ],
            'initial coordinates found and moving with moving sequence < as card' => [
                new GpsCoordinates(1, 0),
                [
                    'S',
                ],
                [
                    new GoWest(),
                    new GoEast(),
                    new GoSouth(),
                    new GoNorth()
                ],
                // moved is as expected
                [
                    '2,0',
                ]
            ],
        ];
    }
}
