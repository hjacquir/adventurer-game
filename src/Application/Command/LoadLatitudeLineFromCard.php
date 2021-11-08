<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\LatitudeLine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class LoadLatitudeLineFromCard extends Command
{
    public const COMMAND_ARGUMENT_CARD_FILE_PATH = 'file';
    private const COMMAND_FINISHED_WITH_SUCCESS = 0;
    private const COMMAND_FINISHED_WITH_FAILURE = 1;
    protected static $defaultName = 'app:extract-latitude';
    private LoggerInterface $logger;
    private MessageBusInterface $bus;

    public function __construct(
        LoggerInterface $logger,
        MessageBusInterface $bus
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this->setDescription("Parse card file and load each line.");
        $this->addArgument(
            self::COMMAND_ARGUMENT_CARD_FILE_PATH,
            InputArgument::REQUIRED,
            'The card file path.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $filePath */
        $filePath = $input->getArgument(self::COMMAND_ARGUMENT_CARD_FILE_PATH);

        $lines  = file($filePath);

        if (false === $lines) {
            $this->logger->error('An error occurred : the file does not exist.');

            return self::COMMAND_FINISHED_WITH_FAILURE;
        }

        foreach ($lines as $latitudeIndex => $latitudeAsString) {
            // we send a message with the index latitudeIndex and the latitudeAsString as string
            $this->bus->dispatch(new LatitudeLine($latitudeAsString, $latitudeIndex));
        }

        $this->logger->info('Operation finished : all latitude are send to the handler.');

        return self::COMMAND_FINISHED_WITH_SUCCESS;
    }
}
