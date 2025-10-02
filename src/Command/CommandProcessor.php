<?php

namespace DealCommands\Command;

use DealCommands\Deal\DealContextInterface;
use DealCommands\Exception\CommandParsingException;
use DealCommands\Exception\CommandValidationException;
use DealCommands\Exception\UnknownCommandException;
use DealCommands\Logging\CommandLoggerInterface;
use DealCommands\Logging\Entry\CommandLogEntry;

class CommandProcessor
{
    private CommandRegistry $registry;
    private CommandLoggerInterface $logger;

    public function __construct(CommandRegistry $registry, CommandLoggerInterface $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    public function process(string $input, DealContextInterface $context): CommandResult
    {
        try {
            $request = CommandRequest::fromString($input);
        } catch (CommandParsingException $exception) {
            $this->logger->log(new CommandLogEntry('unknown', $context->getDealId(), trim($input), false, $exception->getMessage()));
            throw $exception;
        }

        $command = $this->registry->get($request->getName());
        if ($command === null) {
            $this->logger->log(new CommandLogEntry($request->getName(), $context->getDealId(), $request->getRawInput(), false, 'Command not found.'));
            throw UnknownCommandException::forName($request->getName());
        }

        try {
            $result = $command->execute($request, $context);
            $this->logger->log(new CommandLogEntry($command->getName(), $context->getDealId(), $request->getRawInput(), $result->isSuccess(), $result->isSuccess() ? null : ($result->getMessage() ?? 'Execution failed.')));
            return $result;
        } catch (CommandValidationException $validationException) {
            $this->logger->log(new CommandLogEntry($command->getName(), $context->getDealId(), $request->getRawInput(), false, $validationException->getMessage()));
            throw $validationException;
        } catch (\Throwable $throwable) {
            $this->logger->log(new CommandLogEntry($command->getName(), $context->getDealId(), $request->getRawInput(), false, $throwable->getMessage()));
            throw $throwable;
        }
    }
}
