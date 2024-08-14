<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email|unique:admins,email|min:10|max:255',
            'password' => [
                'required',
                'max:100',
                Password::min(6)
                ->letters()
                ->numbers()
                ->mixedCase()
                ->symbols()
                ->uncompromised()
            ],
        ];
    }
}
