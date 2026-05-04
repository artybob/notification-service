<?php

namespace App\Services\Channels;

use App\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\Log;

class EmailChannel implements NotificationChannelInterface
{
    public function send(int $userId, string $message): array
    {
        Log::info('Sending email', ['user_id' => $userId, 'message' => $message]);

        // Симулируем 10% ошибок для тестирования
        if (rand(1, 100) <= 10) {
            return ['success' => false, 'error' => 'Email service temporarily unavailable'];
        }

        return ['success' => true, 'response' => 'Email sent'];
    }

    public function getName(): string
    {
        return 'email';
    }
}
