<?php

namespace DealCommands\Command\Commands;

use DealCommands\Command\CommandInterface;
use DealCommands\Command\CommandRequest;
use DealCommands\Command\CommandResult;
use DealCommands\Deal\DealContextInterface;
use DealCommands\Exception\CommandValidationException;

class AcceptPaymentCommand implements CommandInterface
{
    private const AMOUNT_PROPERTY_ID = 14;
    private const SOURCE_PROPERTY_ID = 15;

    public function getName(): string
    {
        return 'принято';
    }

    public function execute(CommandRequest $request, DealContextInterface $context): CommandResult
    {
        $arguments = $request->getArguments();
        if (count($arguments) < 2) {
            throw new CommandValidationException('Команда /принято требует сумму и источник.');
        }

        $amount = $arguments[0];
        if (!is_numeric($amount)) {
            throw new CommandValidationException('Сумма должна быть числом.');
        }

        $source = $request->getArgumentsString(1);
        if (trim($source) === '') {
            throw new CommandValidationException('Источник оплаты не может быть пустым.');
        }

        $context->setProperty(self::AMOUNT_PROPERTY_ID, (string)$amount);
        $context->setProperty(self::SOURCE_PROPERTY_ID, $source);

        return CommandResult::success('Оплата сохранена.');
    }
}
