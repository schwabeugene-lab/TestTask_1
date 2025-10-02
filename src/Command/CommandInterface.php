<?php

namespace DealCommands\Command;

use DealCommands\Deal\DealContextInterface;

interface CommandInterface
{
    public function getName(): string;

    public function execute(CommandRequest $request, DealContextInterface $context): CommandResult;
}
