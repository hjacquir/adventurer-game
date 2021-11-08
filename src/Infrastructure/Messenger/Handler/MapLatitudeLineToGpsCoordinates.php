<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Handler;

use App\Domain\LatitudeLine;
use App\Domain\LatitudeLineToGpsCoordinatesMapper;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MapLatitudeLineToGpsCoordinates implements MessageHandlerInterface
{
    private LatitudeLineToGpsCoordinatesMapper $mapper;
    private MessageBusInterface $bus;

    /**
     * @param LatitudeLineToGpsCoordinatesMapper $mapper
     * @param MessageBusInterface $bus
     */
    public function __construct(LatitudeLineToGpsCoordinatesMapper $mapper, MessageBusInterface $bus)
    {
        $this->mapper = $mapper;
        $this->bus = $bus;
    }

    public function __invoke(LatitudeLine $latitudeLine)
    {
        foreach ($this->mapper->map($latitudeLine) as $gpsCoordinates) {
            $this->bus->dispatch($gpsCoordinates);
        }
    }
}
