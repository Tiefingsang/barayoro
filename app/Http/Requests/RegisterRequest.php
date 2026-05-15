<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-\'àâäéèêëïîôöùûüçÀÂÄÉÈÊËÏÎÔÖÙÛÜÇ]+$/u',
            'admin_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\-\'àâäéèêëïîôöùûüçÀÂÄÉÈÊËÏÎÔÖÙÛÜÇ]+$/u',
            'admin_position' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email|unique:companies,email',
            'password' => 'required|min:8|max:60|confirmed',
            'subscription_plan' => 'required|in:trial,premium',
            'terms' => 'accepted',
            'phone' => 'nullable|string|regex:/^[0-9+]{8,15}$/',
            'country' => 'nullable|string|in:ML,SN,CI,BF,NE,TG,BJ,GN,CM,FR',
            'siret' => 'nullable|string|size:14|regex:/^[0-9]+$/',
            'business_type' => 'nullable|string|in:commerce,services,agroalimentaire,tech,sante,education,immobilier,transport,hotellerie,artisanat,autre',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Le nom de l\'entreprise est requis.',
            'company_name.regex' => 'Le nom de l\'entreprise ne doit contenir que des lettres, chiffres et espaces.',
            'admin_name.required' => 'Le nom de l\'administrateur est requis.',
            'admin_name.regex' => 'Le nom de l\'administrateur ne doit contenir que des lettres et espaces.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'phone.regex' => 'Le numéro de téléphone doit être valide (8-13 chiffres).',
            'siret.size' => 'Le numéro SIRET doit contenir exactement 14 chiffres.',
            'siret.regex' => 'Le numéro SIRET ne doit contenir que des chiffres.',
            'subscription_plan.in' => 'Le plan d\'abonnement sélectionné est invalide.',
            'country.in' => 'Le pays sélectionné n\'est pas disponible.',
        ];
    }
}