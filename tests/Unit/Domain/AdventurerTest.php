<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Adventurer;
use App\Domain\Card;
use App\Domain\Model\GpsCoordinates;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Adventurer
 */
class AdventurerTest extends TestCase
{
    private Adventurer $currentTested;
    private MockObject $card;
    private MockObject $gpsCoordinates;

    public function setUp(): void
    {
        $this->card = $this->createMock(Card::class);
        $this->gpsCoordinates = $this->createMock(GpsCoordinates::class);

        $this->currentTested = new Adventurer($this->card, $this->gpsCoordinates);
    }

    public function provideDataForCanMoveFrom()
    {
        return [
            'gps coordinates latitude is < 0' => [
                // latitude
                -1,
                // longitude
                8,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                false
            ],
            'gps coordinates longitude is < 0' => [
                // latitude
                10,
                // longitude
                -8,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                false
            ],
            'gps coordinates latitude is > max latitude' => [
                // latitude
                11,
                // longitude
                2,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                false
            ],
            'gps coordinates longitude is > max longitude' => [
                // latitude
                1,
                // longitude
                20,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                false
            ],
            'gps coordinates are positive and < max card coordinates' => [
                // latitude
                1,
                // longitude
                2,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                true
            ],
            'gps coordinates are positive and = max card coordinates' => [
                // latitude
                10,
                // longitude
                10,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                true
            ],
            'gps coordinates = 0 and < max card coordinates' => [
                // latitude
                0,
                // longitude
                0,
                // max latitude
                10,
                // max longitude
                10,
                // expected
                true
            ],
        ];
    }

    /**
     * @dataProvider provideDataForCanMoveFrom
     */
    public function testCanMoveFromShouldReturnFalseWhenCoordinatesLatitudeIsNegative(
      int $latitude,
      int $longitude,
      int $maxLatitude,
      int $maxLongitude,
      bool $expectedResult
    ): void {
        $this->gpsCoordinates->method('getLatitude')
            ->willReturn($latitude);

        $this->gpsCoordinates->method('getLongitude')
            ->willReturn($longitude);

        $this->card->method('getMaxLatitude')
            ->willReturn($maxLatitude);

        $this->card->method('getMaxLongitude')
            ->willReturn($maxLongitude);

        $this->assertSame($expectedResult, $this->currentTested->canMoveFrom($this->gpsCoordinates));
    }
}
