<?php

declare(strict_types=1);

namespace App\Domain\Model;

class GpsCoordinates
{
    private int $id;
    private int $latitude;
    private int $longitude;

    /**
     * @param int $latitude
     * @param int $longitude
     */
    public function __construct(int $latitude, int $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): GpsCoordinates
    {
        $this->id = $id;

        return $this;
    }

    public function getLatitude(): int
    {
        return $this->latitude;
    }

    public function setLatitude(int $latitude): GpsCoordinates
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): int
    {
        return $this->longitude;
    }

    public function setLongitude(int $longitude): GpsCoordinates
    {
        $this->longitude = $longitude;

        return $this;
    }
}
