<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:8|max:60|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'email.exists' => 'Aucun compte associé à cet email.'
        ];
    }
}