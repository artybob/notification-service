<?php

namespace App\Services;

use App\Contracts\NotificationChannelInterface;
use App\Services\Channels\EmailChannel;
use App\Services\Channels\TelegramChannel;
use InvalidArgumentException;

class ChannelFactory
{
    private array $channels = [];

    public function __construct()
    {
        $this->registerChannel(new EmailChannel);
        $this->registerChannel(new TelegramChannel);
    }

    public function registerChannel(NotificationChannelInterface $channel): void
    {
        $this->channels[$channel->getName()] = $channel;
    }

    public function getChannel(string $channelName): NotificationChannelInterface
    {
        if (! isset($this->channels[$channelName])) {
            throw new InvalidArgumentException("Channel '{$channelName}' is not supported");
        }

        return $this->channels[$channelName];
    }

    public function getAvailableChannels(): array
    {
        return array_keys($this->channels);
    }
}
