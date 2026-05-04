<?php

namespace Tests\Feature;

use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_notification(): void
    {
        $response = $this->postJson('/api/notifications', [
            'user_id' => 1,
            'channel' => 'email',
            'message' => 'Test message',
        ]);

        $response->assertStatus(201);

        // Проверяем что уведомление создалось в БД (статус может быть sent или pending)
        $this->assertDatabaseHas('notifications', [
            'user_id' => 1,
            'channel' => 'email',
        ]);

        // Проверяем что ID вернулся
        $this->assertArrayHasKey('id', $response->json('data'));
    }

    public function test_validation_fails_for_long_message(): void
    {
        $response = $this->postJson('/api/notifications', [
            'user_id' => 1,
            'channel' => 'email',
            'message' => str_repeat('a', 501),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_can_get_notification_status(): void
    {
        $notification = Notification::factory()->create();

        $response = $this->getJson("/api/notifications/{$notification->id}");

        $response->assertStatus(200);
        $this->assertEquals($notification->id, $response->json('data.id'));
    }

    public function test_can_filter_history_by_status(): void
    {
        Notification::factory()->create(['user_id' => 1, 'status' => 'sent', 'channel' => 'email', 'message' => 'Sent']);
        Notification::factory()->create(['user_id' => 1, 'status' => 'pending', 'channel' => 'email', 'message' => 'Pending']);

        $response = $this->getJson('/api/notifications/user/1/history?status=sent');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }
}
