<?php

namespace DealCommands\Tests\Support;

use DealCommands\Deal\DealContextInterface;

class FakeDealContext implements DealContextInterface
{
    private int $dealId;

    /** @var array<int, string> */
    private array $properties = [];

    /** @var string[] */
    private array $messages = [];

    private string $contact;

    public function __construct(int $dealId, string $contact)
    {
        $this->dealId = $dealId;
        $this->contact = $contact;
    }

    public function getDealId(): int
    {
        return $this->dealId;
    }

    public function setProperty(int $propertyId, string $value): void
    {
        $this->properties[$propertyId] = $value;
    }

    public function getProperty(int $propertyId): ?string
    {
        return $this->properties[$propertyId] ?? null;
    }

    public function addServiceMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    public function getClientContact(): string
    {
        return $this->contact;
    }

    /**
     * @return array<int, string>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
