<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $user_id
 * @property string $channel
 * @property string $message
 */
class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'channel' => 'required|string|in:email,telegram',
            'message' => 'required|string|max:500',
        ];
    }
}
