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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Inscription entreprise - Barayoro</title>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Couleurs Barayoro */
        :root {
            --barayoro-orange: #ff6c00;
            --barayoro-orange-dark: #e05a00;
            --barayoro-orange-light: #fff5eb;
        }
        
        .form-input {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .form-input input, .form-input select, .form-input textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.3s ease;
            background: white;
            font-size: 0.95rem;
        }
        .form-input input:focus, .form-input select:focus, .form-input textarea:focus {
            border-color: #ff6c00;
            box-shadow: 0 0 0 3px rgba(255, 108, 0, 0.1);
        }
        .form-input label {
            position: absolute;
            left: 1rem;
            top: 0.875rem;
            color: #94a3b8;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 0.25rem;
            font-size: 0.95rem;
        }
        .form-input input:focus ~ label,
        .form-input input:not(:placeholder-shown) ~ label,
        .form-input select:focus ~ label,
        .form-input select:has(:checked) ~ label,
        .form-input textarea:focus ~ label,
        .form-input textarea:not(:placeholder-shown) ~ label {
            top: -0.6rem;
            left: 0.75rem;
            font-size: 0.7rem;
            background: white;
            color: #ff6c00;
        }
        
        /* Bouton principal Barayoro */
        .btn-primary {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
            font-size: 1rem;
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #e05a00 0%, #cc4f00 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 108, 0, 0.3);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Cartes plan - Version plus grande */
        .plan-card {
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
        }
        .plan-card:hover {
            border-color: #ff6c00;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .plan-card.selected {
            border-color: #ff6c00;
            background: #fff5eb;
        }
        .plan-card .plan-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ff6c00;
            margin-bottom: 0.5rem;
        }
        .plan-card .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
        }
        .plan-card .plan-period {
            font-size: 0.7rem;
            color: #6b7280;
        }
        .plan-card .plan-feature {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 0.5rem;
        }
        
        /* Container plus grand */
        .register-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Logo Barayoro */
        .logo-barayoro {
            background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Badge optionnel */
        .optional-badge {
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: normal;
            margin-left: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .register-container {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 to-gray-100 min-h-screen py-8 px-4">

    <div class="register-container p-6 sm:p-8 md:p-10">
        <!-- Logo / En-tête Barayoro -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 shadow-lg" style="background: linear-gradient(135deg, #ff6c00 0%, #e05a00 100%);">
                <i class="fas fa-building text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold logo-barayoro mb-2">Barayoro</h1>
            <p class="text-gray-500">Créez votre espace entreprise et simplifiez votre gestion</p>
        </div>

        @if($errors->any())
            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-xl mb-6">
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

        <form method="POST" action="{{ route('register.post') }}" x-data="registerForm">
            @csrf

            <!-- Informations de l'entreprise -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-building mr-2 text-orange-500"></i>Informations de l'entreprise
                </h2>

                <div class="form-input">
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                           class="w-full" placeholder=" " required />
                    <label for="company_name">Nom de l'entreprise *</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-input">
                        <input type="text" id="siret" name="siret" value="{{ old('siret') }}"
                               class="w-full" placeholder=" " />
                        <label for="siret">Numéro SIRET <span class="optional-badge">(optionnel)</span></label>
                    </div>

                    <div class="form-input">
                        <select id="business_type" name="business_type" class="w-full">
                            <option value="">Sélectionner un domaine</option>
                            <option value="commerce" {{ old('business_type') == 'commerce' ? 'selected' : '' }}>🛒 Commerce / Distribution</option>
                            <option value="services" {{ old('business_type') == 'services' ? 'selected' : '' }}>💼 Services professionnels</option>
                            <option value="agroalimentaire" {{ old('business_type') == 'agroalimentaire' ? 'selected' : '' }}>🌾 Agroalimentaire</option>
                            <option value="tech" {{ old('business_type') == 'tech' ? 'selected' : '' }}>💻 Technologies / IT</option>
                            <option value="sante" {{ old('business_type') == 'sante' ? 'selected' : '' }}>🏥 Santé / Médical</option>
                            <option value="education" {{ old('business_type') == 'education' ? 'selected' : '' }}>📚 Éducation / Formation</option>
                            <option value="immobilier" {{ old('business_type') == 'immobilier' ? 'selected' : '' }}>🏠 Immobilier / Construction</option>
                            <option value="transport" {{ old('business_type') == 'transport' ? 'selected' : '' }}>🚚 Transport / Logistique</option>
                            <option value="hotellerie" {{ old('business_type') == 'hotellerie' ? 'selected' : '' }}>🏨 Hôtellerie / Restauration</option>
                            <option value="artisanat" {{ old('business_type') == 'artisanat' ? 'selected' : '' }}>🎨 Artisanat / Création</option>
                            <option value="autre" {{ old('business_type') == 'autre' ? 'selected' : '' }}>📌 Autre secteur</option>
                        </select>
                        <label for="business_type">Domaine d'activité</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-input">
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full" placeholder=" " />
                        <label for="phone">Téléphone</label>
                    </div>

                    <div class="form-input">
                        <select id="country" name="country" class="w-full">
                            <option value="">Sélectionner</option>
                            <option value="ML" {{ old('country') == 'ML' ? 'selected' : '' }} selected>🇲🇱 Mali</option>
                            <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>🇸🇳 Sénégal</option>
                            <option value="CI" {{ old('country') == 'CI' ? 'selected' : '' }}>🇨🇮 Côte d'Ivoire</option>
                            <option value="BF" {{ old('country') == 'BF' ? 'selected' : '' }}>🇧🇫 Burkina Faso</option>
                            <option value="NE" {{ old('country') == 'NE' ? 'selected' : '' }}>🇳🇪 Niger</option>
                            <option value="TG" {{ old('country') == 'TG' ? 'selected' : '' }}>🇹🇬 Togo</option>
                            <option value="BJ" {{ old('country') == 'BJ' ? 'selected' : '' }}>🇧🇯 Bénin</option>
                            <option value="GN" {{ old('country') == 'GN' ? 'selected' : '' }}>🇬🇳 Guinée</option>
                            <option value="CM" {{ old('country') == 'CM' ? 'selected' : '' }}>🇨🇲 Cameroun</option>
                            <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>🇫🇷 France</option>
                        </select>
                        <label for="country">Pays</label>
                    </div>
                </div>

                <div class="form-input">
                    <textarea id="address" name="address" rows="2" class="w-full" placeholder=" ">{{ old('address') }}</textarea>
                    <label for="address">Adresse</label>
                </div>
            </div>

            <!-- Plan d'abonnement -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-tag mr-2 text-orange-500"></i>Plan d'abonnement
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'trial'"
                         :class="selected ? 'selected' : ''" class="plan-card">
                        <div class="plan-name">🚀 ESSAI GRATUIT</div>
                        <div class="plan-price">0 FCFA</div>
                        <div class="plan-period">30 jours d'essai</div>
                        <div class="plan-feature">✓ 5 utilisateurs max</div>
                        <div class="plan-feature">✓ Fonctionnalités limitées</div>
                        <div class="plan-feature">✓ Support standard</div>
                    </div>
                    <div x-data="{ selected: false }" @click="selected = true; $refs.plan.value = 'premium'"
                         :class="selected ? 'selected' : ''" class="plan-card">
                        <div class="plan-name">⭐ PREMIUM ANNUEL</div>
                        <div class="plan-price">49 000 FCFA</div>
                        <div class="plan-period">/ an</div>
                        <div class="plan-feature">✓ Utilisateurs illimités</div>
                        <div class="plan-feature">✓ Toutes les fonctionnalités</div>
                        <div class="plan-feature">✓ Support prioritaire 24/7</div>
                        <div class="plan-feature">✓ Accès API</div>
                    </div>
                </div>
                <input type="hidden" name="subscription_plan" id="plan" x-ref="plan" value="trial">
            </div>

            <!-- Administrateur -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-user-cog mr-2 text-orange-500"></i>Informations de l'administrateur
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-input">
                        <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}"
                               class="w-full" placeholder=" " required />
                        <label for="admin_name">Nom complet *</label>
                    </div>

                    <div class="form-input">
                        <input type="text" id="admin_position" name="admin_position" value="{{ old('admin_position') }}"
                               class="w-full" placeholder=" " />
                        <label for="admin_position">Fonction <span class="optional-badge">(optionnel)</span></label>
                    </div>
                </div>

                <div class="form-input">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full" placeholder=" " required />
                    <label for="email">Email professionnel *</label>
                </div>

                <div class="form-input relative">
                    <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                           class="w-full pr-12" placeholder=" " required x-model="password" />
                    <label for="password">Mot de passe *</label>
                    <button type="button" @click="togglePassword" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 focus:outline-none">
                        <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-lg"></i>
                    </button>
                </div>

                <div class="form-input relative">
                    <input :type="showConfirmPass ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                           class="w-full pr-12" placeholder=" " required x-model="password_confirmation" />
                    <label for="password_confirmation">Confirmer le mot de passe *</label>
                    <button type="button" @click="toggleConfirmPassword" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 focus:outline-none">
                        <i :class="showConfirmPass ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Conditions -->
            <div class="mb-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="terms" class="mr-3 rounded border-gray-300 text-orange-500 focus:ring-orange-500" required />
                    <span class="text-sm text-gray-600">
                        J'accepte les <a href="{{ route('terms') }}" class="text-orange-600 hover:text-orange-800 font-medium">conditions d'utilisation</a> et la
                        <a href="{{ route('privacy') }}" class="text-orange-600 hover:text-orange-800 font-medium">politique de confidentialité</a>
                    </span>
                </label>
            </div>

            <!-- Bouton -->
            <button type="submit" class="btn-primary">
                <i class="fas fa-check-circle mr-2"></i>
                Créer mon espace entreprise
            </button>

            <!-- Lien connexion -->
            <p class="text-center text-sm text-gray-500 mt-6">
                Vous avez déjà un compte ?
                <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-800 font-semibold">Se connecter</a>
            </p>

            <!-- Sécurité -->
            <div class="text-center text-xs text-gray-400 mt-6 pt-4 border-t border-gray-100">
                <i class="fas fa-shield-alt mr-1"></i> Vos données sont sécurisées
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registerForm', () => ({
                showPass: false,
                showConfirmPass: false,
                password: '',
                password_confirmation: '',
                togglePassword() {
                    this.showPass = !this.showPass;
                },
                toggleConfirmPassword() {
                    this.showConfirmPass = !this.showConfirmPass;
                }
            }));
        });
    </script>
</body>
</html>