<?php

namespace App\Services;

use App\Contracts\NotificationChannelInterface;
use App\Models\Notification;
use App\Services\Channels\EmailChannel;
use App\Services\Channels\TelegramChannel;
use InvalidArgumentException;

class NotificationService
{
    private array $channels = [];

    public function __construct()
    {
        $this->channels = [
            'email' => new EmailChannel,
            'telegram' => new TelegramChannel,
        ];
    }

    public function getChannel(string $name): NotificationChannelInterface
    {
        if (! isset($this->channels[$name])) {
            throw new InvalidArgumentException("Channel '{$name}' is not supported");
        }

        return $this->channels[$name];
    }

    public function createNotification(int $userId, string $channel, string $message): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'channel' => $channel,
            'message' => $message,
            'status' => Notification::STATUS_PENDING ?? 'pending',
            'retry_count' => 0,
        ]);
    }
}
