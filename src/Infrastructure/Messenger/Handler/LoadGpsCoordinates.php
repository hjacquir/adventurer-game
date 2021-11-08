<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Handler;

use App\Domain\Model\GpsCoordinates;
use App\Domain\Repository\GpsCoordinatesRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class LoadGpsCoordinates implements MessageHandlerInterface
{
    private GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository;

    public function __construct(GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository)
    {
        $this->gpsCoordinatesRepository = $gpsCoordinatesRepository;
    }

    public function __invoke(GpsCoordinates $gpsCoordinates)
    {
        $this->gpsCoordinatesRepository->save($gpsCoordinates);
    }
}
