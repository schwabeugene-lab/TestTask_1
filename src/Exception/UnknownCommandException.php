<?php

namespace DealCommands\Exception;

use RuntimeException;

class UnknownCommandException extends RuntimeException
{
    public static function forName(string $name): self
    {
        return new self(sprintf('Unknown command "%s".', $name));
    }
}
