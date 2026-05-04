<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $channel
 * @property string $message
 * @property string $status
 * @property string|null $error_message
 * @property int $retry_count
 * @property string|null $sent_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'channel', 'message', 'status', 'error_message', 'retry_count', 'sent_at',
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_SENT = 'sent';

    const STATUS_FAILED = 'failed';

    public static function getChannels(): array
    {
        return ['email', 'telegram'];
    }

    public function markAsSent(): void
    {
        $this->status = self::STATUS_SENT;
        $this->sent_at = now();
        $this->save();
    }

    public function markAsFailed(string $error): void
    {
        $this->status = self::STATUS_FAILED;
        $this->error_message = $error;
        $this->save();
    }
}
