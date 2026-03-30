<!doctype html>
<html dir="ltr" lang="en">


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="asstes/images/favicon.html" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <title>Softify - Multi Component UI Web with Client and Admin Dashboard</title>
    <script defer src="{{ asset('assets/js/app.js') }}"></script>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body x-cloak x-data="customizer" :class="$store.app.isDarkMode?'dark':''">
    <!-- loader -->
    <!-- screen loader -->
    <div x-cloak class="screen_loader animate__animated duration-700 fixed inset-0 z-[60] grid place-content-center bg-neutral-400">
    <svg viewBox="25 25 50 50">
        <circle r="20" cy="50" cx="50"></circle>
    </svg>
    </div>


    <!-- Main Content -->
    <main class="relative min-h-screen overflow-x-hidden f-center bg-neutral-0 dark:bg-neutral-904">
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-8 -left-8 lg:-top-32 lg:-left-40 size-40 lg:size-[340px] rounded-full bg-secondary-300 opacity-[0.2] blur-[100px]"></div>
        <div class="absolute -top-8 -right-8 lg:-top-32 lg:-right-40 size-40 lg:size-[340px] rounded-full bg-error-300 opacity-[0.2] blur-[100px]"></div>
        <div class="absolute -right-8 -bottom-8 lg:-right-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-info-300 opacity-[0.15] blur-[100px]"></div>
        <div class="absolute -left-8 -bottom-8 lg:-left-40 lg:-bottom-28 size-40 lg:size-[340px] rounded-full bg-warning-300 opacity-[0.15] blur-[100px]"></div>
      </div>

      <div class="container overflow-y-auto">
        <div class="grid grid-cols-12 gap-4 xxl:gap-6 items-center relative z-[4] text-neutral-700 dark:text-neutral-20 py-12">
          <div class="col-span-12 lg:col-span-6 xxl:col-span-5">
            <h3 class="mb-4 xl:mb-6">Barayoro!</h3>
            <p class="mb-7 xl:mb-10">Connectez-vous à votre compte entreprise</p>
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
            <form method="POST" action="{{ route('login.post') }}" class="">
                @csrf
              <div class="form-input mb-4 xl:mb-6">
                <input name="email" type="text" id="email" class="!rounded-full" placeholder="votre email" required />
                <label for="email">Email </label>
              </div>
              <div x-data="{showPass: false}" class="form-input rounded-3xl">
                <input :type="showPass?'text':'password'" name="password" id="pass2" class="!rounded-full" placeholder="Textfield" />
                <label for="pass2">Mot de passe</label>
                <span @click="showPass = !showPass" class="absolute ltr:right-5 rtl:left-5 top-1/2 flex size-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full duration-300 hover:bg-neutral-40 dark:hover:bg-neutral-700">
                  <i x-show="showPass" class="las la-eye text-xl"></i>
                  <i x-show="!showPass" class="las la-eye-slash text-xl"></i>
                </span>
              </div>
              <div class="flex justify-end mt-2 mb-5">
                <a href="{{ route('password.request') }}" class="text-secondary-300">Mot de passe oublié ?</a>
              </div>
              <p class="mb-7 xl:mb-10"> Vous n'avez pas de compte ?  <a href="{{ route('register') }}" class="font-semibold text-primary-300">Créer un compte</a></p>
              <button type="submit" class="btn-primary w-full">Se connecter</button>
            </form>
          </div>
          <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
            <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-neutral-30 dark:bg-neutral-700 f-center">
              <img src="{{ asset('assets/images/login-1.png') }}" alt="" />
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
