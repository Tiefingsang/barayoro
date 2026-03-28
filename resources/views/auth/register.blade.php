<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Inscription entreprise - Barayoro</title>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .form-input {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .form-input input, .form-input select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.3s ease;
            background: transparent;
            font-size: 0.95rem;
        }
        .form-input input:focus, .form-input select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-input label {
            position: absolute;
            left: 1rem;
            top: 0.875rem;
            color: #94a3b8;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
            padding: 0 0.25rem;
            font-size: 0.95rem;
        }
        .form-input input:focus ~ label,
        .form-input input:not(:placeholder-shown) ~ label,
        .form-input select:focus ~ label,
        .form-input select:has(:checked) ~ label {
            top: -0.6rem;
            left: 0.75rem;
            font-size: 0.7rem;
            background: white;
            color: #3b82f6;
        }
        .dark .form-input input:focus ~ label,
        .dark .form-input input:not(:placeholder-shown) ~ label {
            background: #1f2937;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
            font-size: 1rem;
            border: none;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .plan-card {
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .plan-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .plan-card.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .dark .plan-card {
            border-color: #374151;
            background: #1f2937;
        }
        .dark .plan-card.selected {
            background: #1e3a8a;
            border-color: #3b82f6;
        }
        .register-container {
            max-width: 560px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .dark .register-container {
            background: #1f2937;
        }
        @media (max-width: 640px) {
            .register-container {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-neutral-900 dark:to-neutral-800 min-h-screen f-center py-8 px-4">

    <div class="register-container p-6 sm:p-8 lg:p-10">
        <!-- Logo / En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Barayoro</h1>
            <p class="text-gray-500 dark:text-gray-400">Créez votre espace entreprise</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <!-- Informations de l'entreprise -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informations de l'entreprise</h2>

                <div class="form-input">
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                           class="w-full" placeholder=" " required />
                    <label for="company_name">Nom de l'entreprise *</label>
                </div>

                <div class="form-input">
                    <input type="text" id="siret" name="siret" value="{{ old('siret') }}"
                           class="w-full" placeholder=" " />
                    <label for="siret">Numéro SIRET (optionnel)</label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-input">
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full" placeholder=" " />
                        <label for="phone">Téléphone</label>
                    </div>

                    <div class="form-input">
                        <select id="country" name="country" class="w-full">
                            <option value="">Sélectionner</option>
                            <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                            <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>Sénégal</option>
                            <option value="CI" {{ old('country') == 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                            <option value="CM" {{ old('country') == 'CM' ? 'selected' : '' }}>Cameroun</option>
                            <option value="ML" {{ old('country') == 'ML' ? 'selected' : '' }}>Mali</option>
                            <option value="BF" {{ old('country') == 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                            <option value="NE" {{ old('country') == 'NE' ? 'selected' : '' }}>Niger</option>
                            <option value="TG" {{ old('country') == 'TG' ? 'selected' : '' }}>Togo</option>
                            <option value="BJ" {{ old('country') == 'BJ' ? 'selected' : '' }}>Bénin</option>
                            <option value="GN" {{ old('country') == 'GN' ? 'selected' : '' }}>Guinée</option>
                            <option value="MR" {{ old('country') == 'MR' ? 'selected' : '' }}>Mauritanie</option>
                        </select>
                        <label for="country">Pays</label>
                    </div>
                </div>
            </div>

            <!-- Plan d'abonnement -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Plan d'abonnement</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'trial'"
                         :class="selected ? 'selected' : ''" class="plan-card text-center cursor-pointer">
                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">Essai gratuit</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">0 €</div>
                        <div class="text-xs text-gray-500 mt-1">30 jours d'essai</div>
                        <div class="text-xs text-gray-400 mt-1">5 utilisateurs max</div>
                    </div>
                    <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'premium'"
                         :class="selected ? 'selected' : ''" class="plan-card text-center cursor-pointer">
                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">Premium Annuel</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">490 €</div>
                        <div class="text-xs text-gray-500 mt-1">/ an</div>
                        <div class="text-xs text-gray-400 mt-1">Utilisateurs illimités</div>
                    </div>
                </div>
                <input type="hidden" name="subscription_plan" id="plan" x-ref="plan" value="trial">
            </div>

            <!-- Administrateur -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informations de l'administrateur</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-input">
                        <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}"
                               class="w-full" placeholder=" " required />
                        <label for="admin_name">Nom complet *</label>
                    </div>

                    <div class="form-input">
                        <input type="text" id="admin_position" name="admin_position" value="{{ old('admin_position') }}"
                               class="w-full" placeholder=" " />
                        <label for="admin_position">Fonction</label>
                    </div>
                </div>

                <div class="form-input">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full" placeholder=" " required />
                    <label for="email">Email professionnel *</label>
                </div>

                <div class="form-input relative">
                    <input x-model="password" :type="showPass ? 'text' : 'password'" id="password" name="password"
                           class="w-full" placeholder=" " required />
                    <label for="password">Mot de passe *</label>
                    <span @click="showPass = !showPass"
                          class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
                        <i x-show="!showPass" class="fas fa-eye-slash text-lg"></i>
                        <i x-show="showPass" class="fas fa-eye text-lg"></i>
                    </span>
                </div>

                <div class="form-input relative">
                    <input x-model="password_confirmation" :type="showConfirmPass ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                           class="w-full" placeholder=" " required />
                    <label for="password_confirmation">Confirmer le mot de passe *</label>
                    <span @click="showConfirmPass = !showConfirmPass"
                          class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
                        <i x-show="!showConfirmPass" class="fas fa-eye-slash text-lg"></i>
                        <i x-show="showConfirmPass" class="fas fa-eye text-lg"></i>
                    </span>
                </div>
            </div>

            <!-- Conditions -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="terms" class="mr-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required />
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        J'accepte les <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-800">conditions d'utilisation</a> et la
                        <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-800">politique de confidentialité</a>
                    </span>
                </label>
            </div>

            <!-- Bouton -->
            <button type="submit" class="btn-primary">
                Créer mon espace entreprise
            </button>

            <!-- Lien connexion -->
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Vous avez déjà un compte ?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Se connecter</a>
            </p>
        </form>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registerForm', () => ({
                showPass: false,
                showConfirmPass: false,
                password: '',
                password_confirmation: ''
            }))
        })
    </script>
</body>
</html>
