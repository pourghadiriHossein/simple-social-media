<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateAdmin extends FormRequest
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
            'name' => 'nullable|min:3|max:100',
            'email' => 'nullable|email|min:10|max:255',
            'password' => [
                'nullable',
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
