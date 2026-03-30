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
    <title>Email envoyé - Barayoro</title>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
            border: none;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .btn-primary-outlined {
            background: transparent;
            border: 1px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary-outlined:hover {
            background: #3b82f6;
            color: white;
        }
        .f-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .confirmation-container {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }
        @media (max-width: 640px) {
            .confirmation-container {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
        .checkmark-circle {
            width: 80px;
            height: 80px;
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .checkmark {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #10b981;
            stroke-miterlimit: 10;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #10b981;
            fill: none;
            animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
        }
        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }
        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }
        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #10b98120;
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-neutral-900">

    <main class="relative min-h-screen overflow-x-hidden f-center bg-white dark:bg-neutral-900 py-12">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-8 -left-8 lg:-top-32 lg:-left-40 size-40 lg:size-[340px] rounded-full bg-blue-400 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -top-8 -right-8 lg:-top-32 lg:-right-40 size-40 lg:size-[340px] rounded-full bg-red-400 opacity-[0.2] blur-[100px]"></div>
            <div class="absolute -right-8 -bottom-8 lg:-right-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-green-400 opacity-[0.15] blur-[100px]"></div>
            <div class="absolute -left-8 -bottom-8 lg:-left-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-yellow-400 opacity-[0.15] blur-[100px]"></div>
        </div>

        <div class="container overflow-y-auto px-4">
            <div class="grid grid-cols-12 gap-6 items-center relative z-[4]">

                <div class="col-span-12 lg:col-span-6 xxl:col-span-5">
                    <div class="confirmation-container mx-auto text-center">
                        <!-- Animation de succès -->
                        <div class="checkmark-circle">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                            </svg>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Email envoyé !</h1>

                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Un lien de réinitialisation a été envoyé à l'adresse :<br/>
                            <strong class="text-primary-300">{{ $email }}</strong>
                        </p>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-8">
                            <div class="flex items-start gap-3">
                                <i class="las la-envelope text-blue-500 text-2xl"></i>
                                <div class="text-left">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">
                                        <strong>Instructions :</strong>
                                    </p>
                                    <ul class="text-sm text-gray-600 dark:text-gray-400 list-disc list-inside space-y-1">
                                        <li>Vérifiez votre boîte de réception</li>
                                        <li>Cliquez sur le lien dans l'email</li>
                                        <li>Créez un nouveau mot de passe</li>
                                    </ul>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Le lien expire dans 60 minutes.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('login') }}" class="btn-primary w-full">
                                Retour à la connexion
                            </a>

                            <form method="POST" action="{{ route('password.email') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit" class="btn-primary-outlined w-full">
                                    Renvoyer l'email
                                </button>
                            </form>
                        </div>

                        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                            Vous n'avez pas reçu l'email ? Vérifiez vos spams ou
                            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="text-primary-300 hover:text-primary-400">
                                renvoyez-le
                            </a>
                        </p>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
                    <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-gray-100 dark:bg-gray-800 f-center">
                        <img src="{{ asset('assets/images/forgot-password-1.png') }}" alt="Email envoyé" class="w-3/4 h-auto" />
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
