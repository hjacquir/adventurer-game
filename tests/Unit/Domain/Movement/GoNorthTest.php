<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Movement;

use App\Domain\Adventurer;
use App\Domain\Model\GpsCoordinates;
use App\Domain\Movement\GoNorth;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Movement\GoNorth
 */
class GoNorthTest extends TestCase
{
    private GoNorth $currentTested;
    private MockObject $adventurer;
    private MockObject $initialCoordinates;
    private MockObject $finalCoordinates;

    public function setUp(): void
    {
        $this->adventurer = $this->createMock(Adventurer::class);
        $this->initialCoordinates = $this->createMock(GpsCoordinates::class);
        $this->finalCoordinates = $this->createMock(GpsCoordinates::class);

        $this->currentTested = new GoNorth();
    }

    public function provideDataForIsApplicable()
    {
        return [
            [
                true,
                'N',
            ],
            [
                false,
                'S',
            ],
            [
                false,
                'bla',
            ]

        ];
    }

    /**
     * @dataProvider provideDataForIsApplicable
     * @param bool $expectedIsApplicable
     * @param string $direction
     */
    public function testIsApplicable(bool $expectedIsApplicable, string $direction)
    {
        $this->assertSame($expectedIsApplicable, $this->currentTested->isApplicable($direction));
    }

    public function testMove()
    {
        $this->initialCoordinates->expects($this->once())
            ->method('getLatitude')
            ->willReturn(1);
        $this->adventurer->expects($this->once())
            ->method('getInitialCoordinates')
            ->willReturn($this->initialCoordinates);

        $this->adventurer->expects($this->once())
            ->method('getFinalCoordinates')
            ->willReturn($this->finalCoordinates);

        $this->finalCoordinates->expects($this->once())
            ->method('setLatitude')
            ->with(0);

        $this->currentTested->move($this->adventurer);
    }
}
