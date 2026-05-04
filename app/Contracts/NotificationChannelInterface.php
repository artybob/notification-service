<?php

namespace App\Contracts;

interface NotificationChannelInterface
{
    public function send(int $userId, string $message): array;
    public function getName(): string;
}
