<?php

namespace DealCommands\Logging\Entry;

use DateTimeImmutable;

class CommandLogEntry
{
    private string $commandName;
    private int $dealId;
    private string $rawInput;
    private bool $success;
    private ?string $errorMessage;
    private DateTimeImmutable $timestamp;

    public function __construct(string $commandName, int $dealId, string $rawInput, bool $success, ?string $errorMessage = null, ?DateTimeImmutable $timestamp = null)
    {
        $this->commandName = $commandName;
        $this->dealId = $dealId;
        $this->rawInput = $rawInput;
        $this->success = $success;
        $this->errorMessage = $errorMessage;
        $this->timestamp = $timestamp ?? new DateTimeImmutable();
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function getDealId(): int
    {
        return $this->dealId;
    }

    public function getRawInput(): string
    {
        return $this->rawInput;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
