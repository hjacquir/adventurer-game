<?php

declare(strict_types=1);

namespace App\Tests\Func\Domain;

use App\Domain\LatitudeLine;
use App\Domain\LatitudeLineToGpsCoordinatesMapper;
use App\Domain\Model\GpsCoordinates;
use PHPUnit\Framework\TestCase;

/**
 * @cover LatitudeLineToGpsCoordinatesMapper
 */
class LatitudeLineToGpsCoordinatesMapperTest extends TestCase
{
    public function testMap()
    {
       $currentTested = new LatitudeLineToGpsCoordinatesMapper();
       $latitudeLine = new LatitudeLine(
          "# % ",
          0
       );

       $a = iterator_to_array($currentTested->map($latitudeLine));
       // we assert number of coordinates
       $this->assertSame(2, count($a));

       // we assert that the gps coordinates as expected
       /** @var GpsCoordinates $first */
       $first = $a[0];
       $this->assertSame(1, $first->getLongitude());
       $this->assertSame(0, $first->getLatitude());

        /** @var GpsCoordinates $second */
        $second = $a[1];
        $this->assertSame(3, $second->getLongitude());
        $this->assertSame(0, $second->getLatitude());
    }
}
