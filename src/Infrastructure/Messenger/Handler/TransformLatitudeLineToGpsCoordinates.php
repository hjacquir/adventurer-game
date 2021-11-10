<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Handler;

use App\Domain\LatitudeLine;
use App\Domain\LatitudeLineToGpsCoordinates;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TransformLatitudeLineToGpsCoordinates implements MessageHandlerInterface
{
    private LatitudeLineToGpsCoordinates $mapper;
    private MessageBusInterface $bus;

    /**
     * @param LatitudeLineToGpsCoordinates $mapper
     * @param MessageBusInterface $bus
     */
    public function __construct(LatitudeLineToGpsCoordinates $mapper, MessageBusInterface $bus)
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
