<?php

namespace DealCommands\Command;

use DealCommands\Exception\CommandParsingException;

/**
 * Represents a parsed command request.
 */
class CommandRequest
{
    private string $name;

    /** @var string[] */
    private array $arguments;

    private string $rawInput;

    private function __construct(string $rawInput, string $name, array $arguments)
    {
        $this->rawInput = $rawInput;
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public static function fromString(string $input): self
    {
        $trimmed = trim($input);
        if ($trimmed === '') {
            throw new CommandParsingException('Empty command.');
        }

        if (!str_starts_with($trimmed, '/')) {
            throw new CommandParsingException('Commands must start with a "/".');
        }

        $withoutSlash = substr($trimmed, 1);
        if ($withoutSlash === '') {
            throw new CommandParsingException('Command name is missing.');
        }

        $spacePosition = strpos($withoutSlash, ' ');
        if ($spacePosition === false) {
            $name = $withoutSlash;
            $argumentsString = '';
        } else {
            $name = substr($withoutSlash, 0, $spacePosition);
            $argumentsString = substr($withoutSlash, $spacePosition + 1);
        }

        $name = trim($name);
        if ($name === '') {
            throw new CommandParsingException('Command name is missing.');
        }

        $argumentsString = trim($argumentsString);
        $arguments = $argumentsString === ''
            ? []
            : preg_split('/\s+/', $argumentsString) ?? [];

        return new self($trimmed, $name, $arguments);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getRawInput(): string
    {
        return $this->rawInput;
    }

    public function getArgumentsString(int $fromIndex = 0): string
    {
        if ($fromIndex < 0) {
            $fromIndex = 0;
        }

        return implode(' ', array_slice($this->arguments, $fromIndex));
    }
}
