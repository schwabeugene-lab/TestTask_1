<?php

namespace DealCommands\Command;

class CommandRegistry
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    /**
     * @param iterable<CommandInterface> $commands
     */
    public function __construct(iterable $commands = [])
    {
        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    public function add(CommandInterface $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function get(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }

    /**
     * @return array<string, CommandInterface>
     */
    public function all(): array
    {
        return $this->commands;
    }
}
