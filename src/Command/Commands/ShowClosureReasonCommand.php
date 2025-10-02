<?php

namespace DealCommands\Command\Commands;

use DealCommands\Command\CommandInterface;
use DealCommands\Command\CommandRequest;
use DealCommands\Command\CommandResult;
use DealCommands\Deal\DealContextInterface;

class ShowClosureReasonCommand implements CommandInterface
{
    private const CLOSURE_REASON_PROPERTY_ID = 222;

    public function getName(): string
    {
        return 'причина';
    }

    public function execute(CommandRequest $request, DealContextInterface $context): CommandResult
    {
        $reason = $context->getProperty(self::CLOSURE_REASON_PROPERTY_ID);
        if ($reason === null || trim($reason) === '') {
            $context->addServiceMessage('Причина закрытия не указана.');
            return CommandResult::success('Причина закрытия отсутствует.');
        }

        $context->addServiceMessage(sprintf('Причина закрытия: %s', $reason));

        return CommandResult::success('Причина закрытия отправлена в сделку.');
    }
}
