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
            margin-bottom: 1rem;
        }
        .form-input input, .form-input select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            outline: none;
            transition: all 0.3s ease;
            background: transparent;
        }
        .form-input input:focus, .form-input select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        .form-input label {
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            color: #9ca3af;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
            padding: 0 0.25rem;
        }
        .form-input input:focus ~ label,
        .form-input input:not(:placeholder-shown) ~ label,
        .form-input select:focus ~ label,
        .form-input select:not([value=""]) ~ label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
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
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
        }
        .btn-outline:hover {
            background: #3b82f6;
            color: white;
        }
        .f-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .plan-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .plan-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }
        .plan-card.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }
        .dark .plan-card {
            border-color: #374151;
        }
        .dark .plan-card.selected {
            background: rgba(59, 130, 246, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-neutral-900">
    <!-- Main Content -->
    <main class="relative min-h-screen overflow-x-hidden f-center bg-neutral-0 dark:bg-neutral-900 py-12">
        <!-- Background decoration -->
        <div class="h-screen absolute inset-0 overflow-hidden">
            <div class="absolute -top-8 -left-8 lg:-top-32 lg:-left-40 size-40 lg:size-[340px] rounded-full bg-blue-400 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -top-8 -right-8 lg:-top-32 lg:-right-40 size-40 lg:size-[340px] rounded-full bg-red-400 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -right-8 -bottom-8 lg:-right-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-green-400 opacity-[0.15] blur-[100px]"></div>
            <div class="absolute -left-8 -bottom-8 lg:-left-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-yellow-400 opacity-[0.15] blur-[100px]"></div>
        </div>

        <div class="container overflow-y-auto px-4">
            <div class="grid grid-cols-12 gap-4 lg:gap-6 items-center relative z-[4] text-neutral-700 dark:text-neutral-200">
                <!-- Formulaire d'inscription entreprise -->
                <div class="col-span-12 lg:col-span-6 xxl:col-span-5">
                    <h3 class="text-2xl lg:text-3xl font-bold mb-4 lg:mb-6">Créez votre espace entreprise</h3>
                    <p class="mb-7 lg:mb-10 text-gray-600 dark:text-gray-400">Inscrivez votre entreprise et commencez à gérer vos activités</p>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" class="grid grid-cols-2 gap-4 lg:gap-6">
                        @csrf

                        <!-- Informations de l'entreprise -->
                        <div class="col-span-2">
                            <div class="form-input">
                                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " required />
                                <label for="company_name" class="dark:text-gray-400">Nom de l'entreprise</label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="form-input">
                                <input type="text" id="siret" name="siret" value="{{ old('siret') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " />
                                <label for="siret" class="dark:text-gray-400">Numéro SIRET (optionnel)</label>
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <div class="form-input">
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " />
                                <label for="phone" class="dark:text-gray-400">Téléphone</label>
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <div class="form-input">
                                <select id="country" name="country" class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white">
                                    <option value="">Sélectionner</option>
                                    <option value="FR">France</option>
                                    <option value="SN">Sénégal</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                    <option value="CM">Cameroun</option>
                                    <option value="ML">Mali</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="NE">Niger</option>
                                    <option value="TG">Togo</option>
                                    <option value="BJ">Bénin</option>
                                    <option value="GN">Guinée</option>
                                    <option value="MR">Mauritanie</option>
                                </select>
                                <label for="country" class="dark:text-gray-400">Pays</label>
                            </div>
                        </div>

                        <!-- Plan d'abonnement -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Plan d'abonnement</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'trial'"
                                     :class="selected ? 'selected' : ''" class="plan-card text-center">
                                    <div class="text-lg font-bold text-blue-600">Essai gratuit</div>
                                    <div class="text-2xl font-bold mt-2">0 €</div>
                                    <div class="text-xs text-gray-500 mt-1">30 jours d'essai</div>
                                    <div class="text-xs text-gray-500">5 utilisateurs max</div>
                                </div>
                                <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'premium'"
                                     :class="selected ? 'selected' : ''" class="plan-card text-center">
                                    <div class="text-lg font-bold text-blue-600">Premium Annuel</div>
                                    <div class="text-2xl font-bold mt-2">490 €</div>
                                    <div class="text-xs text-gray-500 mt-1">/ an</div>
                                    <div class="text-xs text-gray-500">Utilisateurs illimités</div>
                                </div>
                            </div>
                            <input type="hidden" name="subscription_plan" id="plan" x-ref="plan" value="trial">
                        </div>

                        <!-- Administrateur -->
                        <div class="col-span-2 mt-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informations de l'administrateur</p>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <div class="form-input">
                                <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " required />
                                <label for="admin_name" class="dark:text-gray-400">Nom complet</label>
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <div class="form-input">
                                <input type="text" id="admin_position" name="admin_position" value="{{ old('admin_position') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " />
                                <label for="admin_position" class="dark:text-gray-400">Fonction</label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="form-input">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " required />
                                <label for="email" class="dark:text-gray-400">Email professionnel</label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div x-data="{ showPass: false }" class="form-input relative">
                                <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " required />
                                <label for="password" class="dark:text-gray-400">Mot de passe</label>
                                <span @click="showPass = !showPass"
                                      class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i x-show="!showPass" class="fas fa-eye-slash text-lg"></i>
                                    <i x-show="showPass" class="fas fa-eye text-lg"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div x-data="{ showConfirmPass: false }" class="form-input relative">
                                <input :type="showConfirmPass ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                                       class="!rounded-full w-full border border-gray-300 dark:border-gray-600 dark:bg-transparent dark:text-white"
                                       placeholder=" " required />
                                <label for="password_confirmation" class="dark:text-gray-400">Confirmer le mot de passe</label>
                                <span @click="showConfirmPass = !showConfirmPass"
                                      class="absolute ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                    <i x-show="!showConfirmPass" class="fas fa-eye-slash text-lg"></i>
                                    <i x-show="showConfirmPass" class="fas fa-eye text-lg"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="terms" class="mr-2 rounded border-gray-300" required />
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800">conditions d'utilisation</a> et la
                                        <a href="#" class="text-blue-600 hover:text-blue-800">politique de confidentialité</a>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <p class="mb-6 text-center text-gray-600 dark:text-gray-400">
                                Vous avez déjà un compte ?
                                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800">Se connecter</a>
                            </p>
                            <button type="submit" class="btn-primary w-full">
                                Créer mon espace entreprise
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Image de droite -->
                <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
                    <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-gray-100 dark:bg-gray-800 f-center">
                        <img src="{{ asset('assets/images/register-business.svg') }}" alt="Inscription entreprise" class="w-3/4 h-auto" />
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
