<?php

namespace DealCommands\Deal;

interface DealContextInterface
{
    public function getDealId(): int;

    public function setProperty(int $propertyId, string $value): void;

    public function getProperty(int $propertyId): ?string;

    public function addServiceMessage(string $message): void;

    public function getClientContact(): string;
}
