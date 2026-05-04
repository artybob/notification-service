<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $notification_id
 * @property string $channel
 * @property string $status
 * @property string|null $response
 * @property int $attempt
 */
class NotificationLog extends Model
{
    protected $fillable = [
        'notification_id', 'channel', 'status', 'response', 'attempt',
    ];
}
