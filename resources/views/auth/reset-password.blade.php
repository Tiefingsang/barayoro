<!DOCTYPE html>
<html dir="ltr" lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <title>Réinitialisation du mot de passe - Barayoro</title>
    <script defer src="{{ asset('assets/js/app.js') }}"></script>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body x-cloak x-data="customizer" :class="$store.app.isDarkMode?'dark':''">
    <!-- loader -->
    <div x-cloak class="screen_loader animate__animated duration-700 fixed inset-0 z-[60] grid place-content-center bg-neutral-400">
        <svg viewBox="25 25 50 50">
            <circle r="20" cy="50" cx="50"></circle>
        </svg>
    </div>

    <!-- Main Content -->
    <main class="relative min-h-screen overflow-x-hidden f-center bg-neutral-0 dark:bg-neutral-904 py-12">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-8 -left-8 lg:-top-32 lg:-left-40 size-40 lg:size-[340px] rounded-full bg-secondary-300 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -top-8 -right-8 lg:-top-32 lg:-right-40 size-40 lg:size-[340px] rounded-full bg-error-300 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -right-8 -bottom-8 lg:-right-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-info-300 opacity-[0.15] blur-[100px]"></div>
            <div class="absolute -left-8 -bottom-8 lg:-left-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-warning-300 opacity-[0.15] blur-[100px]"></div>
        </div>

        <div class="container overflow-y-auto">
            <div class="grid grid-cols-12 gap-4 xxl:gap-6 items-center relative z-[4] text-neutral-700 dark:text-neutral-20">
                <div class="col-span-12 lg:col-span-6 xxl:col-span-5">
                    <h3 class="mb-4 xl:mb-6">Nouveau mot de passe</h3>
                    <p class="mb-7 xl:mb-10">Créez un nouveau mot de passe pour votre compte.</p>

                    @if(session('status'))
                        <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="grid grid-cols-1 gap-4 xxl:gap-6">
                        @csrf

                        <!-- Token caché (obligatoire) -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Champ email (obligatoire) -->
                        <div class="col-span-1">
                            <div class="form-input">
                                <input type="email" name="email" id="email" class="!rounded-full"
                                    value="{{ $email ?? old('email') }}" required placeholder=" " />
                                <label for="email">Adresse email</label>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Champ mot de passe -->
                        <div class="col-span-1">
                            <div x-data="{showPass: false}" class="form-input">
                                <input name="password" :type="showPass?'text':'password'" id="password"
                                    class="!rounded-full" placeholder=" " required />
                                <label for="password">Nouveau mot de passe</label>
                                <span @click="showPass = !showPass" class="absolute ltr:right-5 rtl:left-5 top-1/2 flex size-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full duration-300 hover:bg-neutral-40 dark:hover:bg-neutral-700">
                                    <i x-show="showPass" class="las la-eye text-xl"></i>
                                    <i x-show="!showPass" class="las la-eye-slash text-xl"></i>
                                </span>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Champ confirmation -->
                        <div class="col-span-1">
                            <div x-data="{showPass: false}" class="form-input">
                                <input name="password_confirmation" :type="showPass?'text':'password'" id="password_confirmation"
                                    class="!rounded-full" placeholder=" " required />
                                <label for="password_confirmation">Confirmer le mot de passe</label>
                                <span @click="showPass = !showPass" class="absolute ltr:right-5 rtl:left-5 top-1/2 flex size-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full duration-300 hover:bg-neutral-40 dark:hover:bg-neutral-700">
                                    <i x-show="showPass" class="las la-eye text-xl"></i>
                                    <i x-show="!showPass" class="las la-eye-slash text-xl"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="col-span-1">
                            <div class="flex gap-4 xxl:gap-6 mb-7 xl:mb-10">
                                <button type="submit" class="btn-primary w-full">Réinitialiser le mot de passe</button>
                                <a class="btn-primary-outlined w-full" href="{{ route('login') }}">Retour à la connexion</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
                    <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-neutral-30 dark:bg-neutral-700 f-center">
                        <img src="{{ asset('assets/images/new-password-one.png') }}" alt="Réinitialisation mot de passe" />
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/libs/alpine.collapse.js') }}"></script>
    <script src="{{ asset('assets/js/libs/alpine.persist.js') }}"></script>
    <script defer src="{{ asset('assets/js/libs/alpine.min.js') }}"></script>
</body>

</html>
