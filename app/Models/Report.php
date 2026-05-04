<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property string|null $file_path
 * @property string|null $error_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'start_date', 'end_date', 'status', 'file_path', 'error_message'];

    const STATUS_PENDING = 'pending';

    const STATUS_PROCESSING = 'processing';

    const STATUS_READY = 'ready';

    const STATUS_FAILED = 'failed';
}
