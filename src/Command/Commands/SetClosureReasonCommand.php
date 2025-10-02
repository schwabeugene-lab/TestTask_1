<?php

namespace DealCommands\Command\Commands;

use DealCommands\Command\CommandInterface;
use DealCommands\Command\CommandRequest;
use DealCommands\Command\CommandResult;
use DealCommands\Deal\DealContextInterface;
use DealCommands\Exception\CommandValidationException;

class SetClosureReasonCommand implements CommandInterface
{
    private const CLOSURE_REASON_PROPERTY_ID = 222;

    public function getName(): string
    {
        return 'причина_закрытия';
    }

    public function execute(CommandRequest $request, DealContextInterface $context): CommandResult
    {
        $reason = $request->getArgumentsString();
        if (trim($reason) === '') {
            throw new CommandValidationException('Причина закрытия не может быть пустой.');
        }

        $context->setProperty(self::CLOSURE_REASON_PROPERTY_ID, $reason);

        return CommandResult::success('Причина закрытия сохранена.');
    }
}
