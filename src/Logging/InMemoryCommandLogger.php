<?php

namespace DealCommands\Logging;

use DealCommands\Logging\Entry\CommandLogEntry;

class InMemoryCommandLogger implements CommandLoggerInterface
{
    /** @var CommandLogEntry[] */
    private array $entries = [];

    public function log(CommandLogEntry $entry): void
    {
        $this->entries[] = $entry;
    }

    /**
     * @return CommandLogEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }
}
