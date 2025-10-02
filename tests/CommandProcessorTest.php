<?php

namespace DealCommands\Tests;

use DealCommands\Command\CommandProcessor;
use DealCommands\Command\CommandRegistry;
use DealCommands\Command\Commands\AcceptPaymentCommand;
use DealCommands\Command\Commands\ContactInfoCommand;
use DealCommands\Command\Commands\SetClosureReasonCommand;
use DealCommands\Command\Commands\ShowClosureReasonCommand;
use DealCommands\Exception\CommandValidationException;
use DealCommands\Exception\UnknownCommandException;
use DealCommands\Logging\InMemoryCommandLogger;
use DealCommands\Tests\Support\FakeDealContext;
use PHPUnit\Framework\TestCase;

class CommandProcessorTest extends TestCase
{
    private CommandProcessor $processor;
    private FakeDealContext $context;
    private InMemoryCommandLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new InMemoryCommandLogger();
        $registry = new CommandRegistry([
            new AcceptPaymentCommand(),
            new ContactInfoCommand(),
            new SetClosureReasonCommand(),
            new ShowClosureReasonCommand(),
        ]);
        $this->processor = new CommandProcessor($registry, $this->logger);
        $this->context = new FakeDealContext(42, '+1-202-555-0178');
    }

    public function testAcceptCommandUpdatesProperties(): void
    {
        $result = $this->processor->process('/принято 500 офис', $this->context);

        self::assertTrue($result->isSuccess());
        self::assertSame('500', $this->context->getProperty(14));
        self::assertSame('офис', $this->context->getProperty(15));
    }

    public function testAcceptCommandValidatesAmount(): void
    {
        $this->expectException(CommandValidationException::class);
        $this->processor->process('/принято пятьсот офис', $this->context);
    }

    public function testContactCommandPublishesMessage(): void
    {
        $result = $this->processor->process('/контакт', $this->context);

        self::assertTrue($result->isSuccess());
        self::assertSame(['Контакт клиента: +1-202-555-0178'], $this->context->getMessages());
    }

    public function testSetClosureReasonUpdatesProperty(): void
    {
        $result = $this->processor->process('/причина_закрытия удалена транзакция', $this->context);

        self::assertTrue($result->isSuccess());
        self::assertSame('удалена транзакция', $this->context->getProperty(222));
    }

    public function testShowClosureReasonPublishesMessage(): void
    {
        $this->context->setProperty(222, 'удалена транзакция');
        $result = $this->processor->process('/причина', $this->context);

        self::assertTrue($result->isSuccess());
        self::assertSame(['Причина закрытия: удалена транзакция'], $this->context->getMessages());
    }

    public function testShowClosureReasonWhenMissing(): void
    {
        $result = $this->processor->process('/причина', $this->context);

        self::assertTrue($result->isSuccess());
        self::assertSame(['Причина закрытия не указана.'], $this->context->getMessages());
    }

    public function testUnknownCommandThrowsException(): void
    {
        $this->expectException(UnknownCommandException::class);
        $this->processor->process('/несуществующая', $this->context);
    }

    public function testLoggerCapturesCommandCalls(): void
    {
        try {
            $this->processor->process('/несуществующая', $this->context);
        } catch (UnknownCommandException $exception) {
            // expected
        }

        $this->processor->process('/контакт', $this->context);

        self::assertCount(2, $this->logger->getEntries());
        self::assertFalse($this->logger->getEntries()[0]->isSuccess());
        self::assertTrue($this->logger->getEntries()[1]->isSuccess());
    }
}
