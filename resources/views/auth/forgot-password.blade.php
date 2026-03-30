<!doctype html>
<html dir="ltr" lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="asstes/images/favicon.html" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <title>Softify - Multi Component UI Web with Client and Admin Dashboard</title>
  <script defer src="{{ asset('assets/js/app.js') }}"></script><link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">



  <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
  <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .animate__animated {
            animation-duration: 0.5s;
        }
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate__fadeInRight {
            animation-name: fadeInRight;
        }
    </style>
</head>

  <body x-cloak x-data="customizer" :class="$store.app.isDarkMode?'dark':''">
    <!-- loader -->
    <!-- screen loader -->
<div x-cloak class="screen_loader animate__animated duration-700 fixed inset-0 z-[60] grid place-content-center bg-neutral-400">
  <svg viewBox="25 25 50 50">
    <circle r="20" cy="50" cx="50"></circle>
  </svg>
</div>

@include('components.flash-messages')


    <!-- Main Content -->
    <main class="relative min-h-screen overflow-x-hidden f-center bg-neutral-0 dark:bg-neutral-904 py-12">
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-8 -left-8 lg:-top-32 lg:-left-40 size-40 lg:size-[340px] rounded-full bg-secondary-300 opacity-[0.2] blur-[100px]"></div>
        <div class="absolute -top-8 -right-8 lg:-top-32 lg:-right-40 size-40 lg:size-[340px] rounded-full bg-error-300 opacity-[0.2] blur-[100px]"></div>
        <div class="absolute -right-8 -bottom-8 lg:-right-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-info-300 opacity-[0.15] blur-[100px]"></div>
        <div class="absolute -left-8 -bottom-8 lg:-left-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-warning-300 opacity-[0.15] blur-[100px]"></div>
      </div>

      <div class="container overflow-y-auto">
        <div class="grid grid-cols-12 gap-6 items-center relative z-[4] text-neutral-700 dark:text-neutral-20">
          <div class="col-span-12 lg:col-span-6 xxl:col-span-5">
            <h3 class="mb-4 xl:mb-6">Mot de passe oublié ?</h3>
            <p class="mb-7 xl:mb-10">Veuillez saisir l'adresse email associée à votre compte. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
            <form action="{{ route('password.email') }}" method="POST">
              <div class="form-input mb-7 xxl:mb-10">
                <input name="email" type="email" id="activated" class="!rounded-full" placeholder="Saisissez votre email" />
                <label for="activated">Email</label>
              </div>

              <div class="flex gap-4 xxl:gap-6">
                <button class="btn-primary w-full">Envoyer la demande</button>
                <a class="btn-primary-outlined w-full" href="{{ route('login') }}">Retour à la connexion</a>
              </div>
            </form>
          </div>
          <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
            <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-neutral-30 dark:bg-neutral-700 f-center">
              <img src="{{ asset('assets/images/forgot-password-1.png') }}" alt="" />
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
