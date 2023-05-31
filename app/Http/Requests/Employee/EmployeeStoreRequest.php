<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nick_name' => ['required', 'string'],
            'full_name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'join_date' => ['required', 'date'],

            'unit' => ['required'],
            'positions.*' => ['required'],

            'username' => ['required', 'unique:users,username', 'min:3'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'min:6']
        ];
    }
}