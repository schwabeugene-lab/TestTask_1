<?php

namespace DealCommands\Logging;

use DealCommands\Logging\Entry\CommandLogEntry;

interface CommandLoggerInterface
{
    public function log(CommandLogEntry $entry): void;
}
