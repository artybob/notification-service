<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;

    protected Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function handle(NotificationService $service): void
    {
        try {
            $channel = $service->getChannel($this->notification->channel);
            $result = $channel->send($this->notification->user_id, $this->notification->message);

            if ($result['success']) {
                $this->notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                Log::info("Notification {$this->notification->id} sent");
            } else {
                $this->handleFailure($result['error'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            $this->handleFailure($e->getMessage());
        }
    }

    private function handleFailure(string $error): void
    {
        $attempt = $this->attempts();

        if ($attempt >= $this->tries) {
            $this->notification->update([
                'status' => 'failed',
                'error_message' => $error,
            ]);
            Log::error("Notification {$this->notification->id} failed permanently: {$error}");

            return;
        }

        $this->notification->increment('retry_count');
        Log::warning("Notification {$this->notification->id} retry {$attempt}/{$this->tries}");

        // Повторный запуск
        self::dispatch($this->notification)->delay(now()->addSeconds(5 * $attempt));
    }
}
