<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function messages(): array
    {
        return [
            'message.max' => 'The message cannot exceed 500 characters.',
            'channel.in' => 'The channel must be email or telegram.',
        ];
    }
}
