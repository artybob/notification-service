<?php

namespace App\Http\Requests;

use App\Models\Notification;
use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'channel' => 'required|string|in:'.implode(',', Notification::getChannels()),
            'message' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'message.max' => 'Message cannot exceed 500 characters.',
            'channel.in' => 'Invalid channel. Supported: '.implode(', ', Notification::getChannels()),
        ];
    }
}
