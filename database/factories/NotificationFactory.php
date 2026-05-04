<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'channel' => fake()->randomElement(['email', 'telegram']),
            'message' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'sent', 'failed']),
            'error_message' => null,
            'retry_count' => 0,
            'sent_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
