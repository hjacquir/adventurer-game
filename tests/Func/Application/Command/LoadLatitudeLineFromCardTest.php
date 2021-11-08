<?php

declare(strict_types=1);

namespace App\Tests\Func\Application\Command;

use App\Application\Command\LoadLatitudeLineFromCard;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\TraceableMessageBus;

/**
 * @covers \App\Application\Command\LoadLatitudeLineFromCard
 */
class LoadLatitudeLineFromCardTest extends TestCase
{
    public function testLoadFileAndSendLatitudeLine(): void
    {
        // we use a traceable bus message to assert that dispatched message are as expected
        $bus = new TraceableMessageBus(new MessageBus());
        $currentTested = new LoadLatitudeLineFromCard(
            new Logger('test'),
            $bus
        );

        $commandTester = new CommandTester($currentTested);
        $commandTester->execute(
            [
                LoadLatitudeLineFromCard::COMMAND_ARGUMENT_CARD_FILE_PATH => __DIR__ . '/card.txt',
            ]
        );
        $messages = $bus->getDispatchedMessages();
        // we have two messages dispatched
        $this->assertSame(2, count($messages));
        // we assert message dispatched content
        $this->assertThatMessageAreExpected($messages[0], "# #\r\n", 0);
       $this->assertThatMessageAreExpected($messages[1], "##\r\n", 1);
    }

    private function assertThatMessageAreExpected(array $message, string $expectedLine, int $expectedLatitude): void
    {
        $this->assertSame($expectedLatitude, $message['message']->getLatitude());
        $this->assertSame($expectedLine, $message['message']->getLine());
    }
}
