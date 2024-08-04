<?php

namespace App\Errors;

class AuthenticationError
{
    private string $messageKey;
    private array $messageData;

    public function __construct(string $messageKey, array $messageData = [])
    {
        $this->messageKey = $messageKey;
        $this->messageData = $messageData;
    }

    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    public function getMessageData(): array
    {
        return $this->messageData;
    }
}