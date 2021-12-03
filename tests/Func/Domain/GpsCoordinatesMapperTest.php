<?php

declare(strict_types=1);

namespace App\Tests\Func\Domain;

use App\Domain\GpsCoordinatesMapper;
use App\Domain\Model\GpsCoordinates;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\GpsCoordinatesMapper
 */
class GpsCoordinatesMapperTest extends TestCase
{
    private GpsCoordinatesMapper $currentTested;

    public function setUp(): void
    {
        $this->currentTested = new GpsCoordinatesMapper();
    }

    public function provideDataToTestFromString()
    {
        return [
          'empty string' => [
              '',
              0,
              0,
          ],
          'string with only comma' => [
              ',',
              0,
              0,
          ],
          'string other' => [
              '444?77aaa',
              0,
              0,
          ],
        ];
    }

    /**
     * @param string $currentString
     * @param int $expectedLongitude
     * @param int $expectedLatitude
     * @dataProvider provideDataToTestFromString
     */
    public function testFromString(string $currentString, int $expectedLongitude, int $expectedLatitude): void
    {
        $gpsCoordinates = $this->currentTested->fromString($currentString);
        $this->assertSame($expectedLongitude, $gpsCoordinates->getLongitude());
        $this->assertSame($expectedLatitude, $gpsCoordinates->getLatitude());
    }

    public function testToString(): void
    {
        $string = $this->currentTested->toString(new GpsCoordinates(1,2));
        $this->assertSame('1,2', $string);
    }
}