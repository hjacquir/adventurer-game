<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Processor;
use App\Domain\Adventurer;
use App\Domain\GpsCoordinatesMapper;
use App\Domain\Map;
use App\Domain\Movement\GoEast;
use App\Domain\Movement\GoNorth;
use App\Domain\Movement\GoSouth;
use App\Domain\Movement\GoWest;
use App\Domain\Repository\GpsCoordinatesRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayGame extends Command
{
    public const COMMAND_ARGUMENT_INITIAL_COORDINATES = 'initial-coordinates';
    public const COMMAND_ARGUMENT_INITIAL_MOVE_SEQUENCE = 'move-sequence';
    private const COMMAND_FINISHED_WITH_SUCCESS = 0;
    protected static $defaultName = 'app:play-game';
    private LoggerInterface $logger;
    private GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository;
    private GpsCoordinatesMapper $gpsCoordinatesMapper;

    public function __construct(
        GpsCoordinatesRepositoryInterface $gpsCoordinatesRepository,
        LoggerInterface $logger,
        GpsCoordinatesMapper $gpsCoordinatesMapper
    ) {
        parent::__construct();

        $this->gpsCoordinatesRepository = $gpsCoordinatesRepository;
        $this->logger = $logger;
        $this->gpsCoordinatesMapper = $gpsCoordinatesMapper;
    }

    protected function configure()
    {
        $this->setDescription("Play game.");
        $this->addArgument(
            self::COMMAND_ARGUMENT_INITIAL_COORDINATES,
            InputArgument::REQUIRED,
            'The initial gps coordinates (latitude[vertical axis],longitude[horizontal axis]) with comma separated (e.g : 1,2)).'
        );
        $this->addArgument(
            self::COMMAND_ARGUMENT_INITIAL_MOVE_SEQUENCE,
            InputArgument::REQUIRED,
            'The moving sequence according to the 4 cardinal points (e.g : SNNEW)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $initialCoordinatesAsString = $input->getArgument(self::COMMAND_ARGUMENT_INITIAL_COORDINATES);
        $initialGpsCoordinates = $this->gpsCoordinatesMapper->fromString($initialCoordinatesAsString);

        // todo add input validation : only authorize value : N E S W
        $movingSequence = $input->getArgument(self::COMMAND_ARGUMENT_INITIAL_MOVE_SEQUENCE);
        // todo encapsulate into an object : use a collection in place of an array
        $directions = str_split($movingSequence);

        $adventurer = new Adventurer(
            new Map($this->gpsCoordinatesRepository),
            $initialGpsCoordinates
        );

        $processor = new Processor(
            $this->gpsCoordinatesRepository,
            $directions,
            // todo encapsulate into an object : use a collection in place of an array
            [
                new GoWest(),
                new GoEast(),
                new GoNorth(),
                new GoSouth()
            ],
            $this->gpsCoordinatesMapper
        );

        $moved = $processor->process($adventurer);

        if (false === empty($moved)) {
            foreach ($moved as $item) {
                $this->logger->info("Moved to : {$item}");
            }

            $this->logger->info("Game finished. Bye !");
            return self::COMMAND_FINISHED_WITH_SUCCESS;
        }

        $this->logger->notice('The adventurer can not move from his initial position ' .
        'OR the initial position that you given does not exist. Game aborted. Bye !');

        return self::COMMAND_FINISHED_WITH_SUCCESS;
    }
}
