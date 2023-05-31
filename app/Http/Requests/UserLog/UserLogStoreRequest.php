<?php

namespace App\Http\Requests\UserLog;

use Illuminate\Foundation\Http\FormRequest;

class UserLogStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string'],
            'log_level' => ['required', 'string'],
            'context' => ['required', 'string'],
            'ipv4' => ['required', 'string']
        ];
    }
}
