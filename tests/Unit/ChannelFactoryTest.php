<?php

namespace Tests\Unit;

use App\Services\ChannelFactory;
use InvalidArgumentException;
use Tests\TestCase;

class ChannelFactoryTest extends TestCase
{
    public function test_get_valid_channel(): void
    {
        $factory = new ChannelFactory;

        $emailChannel = $factory->getChannel('email');
        $this->assertEquals('email', $emailChannel->getName());

        $telegramChannel = $factory->getChannel('telegram');
        $this->assertEquals('telegram', $telegramChannel->getName());
    }

    public function test_get_invalid_channel_throws_exception(): void
    {
        $factory = new ChannelFactory;

        $this->expectException(InvalidArgumentException::class);
        $factory->getChannel('invalid');
    }
}
