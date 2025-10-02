<?php

namespace DealCommands\Command\Commands;

use DealCommands\Command\CommandInterface;
use DealCommands\Command\CommandRequest;
use DealCommands\Command\CommandResult;
use DealCommands\Deal\DealContextInterface;

class ContactInfoCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'контакт';
    }

    public function execute(CommandRequest $request, DealContextInterface $context): CommandResult
    {
        $contact = $context->getClientContact();
        $context->addServiceMessage(sprintf('Контакт клиента: %s', $contact));

        return CommandResult::success('Контакт клиента отправлен в сделку.');
    }
}
