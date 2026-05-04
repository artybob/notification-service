<?php

namespace App\Services\Channels;

use App\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\Log;

class TelegramChannel implements NotificationChannelInterface
{
    public function send(int $userId, string $message): array
    {
        Log::info("Sending telegram", ['user_id' => $userId, 'message' => $message]);

        if (rand(1, 100) <= 10) {
            return ['success' => false, 'error' => 'Telegram API rate limit'];
        }

        return ['success' => true, 'response' => 'Telegram sent'];
    }

    public function getName(): string
    {
        return 'telegram';
    }
}
