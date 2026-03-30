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
            <h3 class="mb-4 xl:mb-6">Request sent successfully!</h3>
            <p class="mb-7 xl:mb-10">We've sent a 6-digit confirmation email to your email.Please enter the code in below box to verify your email.</p>
            <form class="grid grid-cols-1 gap-4 xxl:gap-6">
              <div class="col-span-1">
                <div class="form-input">
                  <input type="text" id="activated" class="!rounded-full" placeholder="Email" />
                  <label for="activated">Email Address</label>
                </div>
              </div>
              <div class="col-span-1">
                <div class="m-auto flex justify-between gap-2.5 text-center">
                  <input type="number" placeholder="-" class="otp__digit otp__field__1 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                  <input type="number" placeholder="-" class="otp__digit otp__field__2 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                  <input type="number" placeholder="-" class="otp__digit otp__field__3 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                  <input type="number" placeholder="-" class="otp__digit otp__field__4 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                  <input type="number" placeholder="-" class="otp__digit otp__field__5 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                  <input type="number" placeholder="-" class="otp__digit otp__field__6 bg-neutral-0 dark:bg-neutral-904 w-16 h-14 rounded-3xl border border-neutral-30 text-center dark:border-neutral-500 focus:border-primary-300 focus:border-2" />
                </div>
              </div>
              <div class="col-span-1">
                <div x-data="{showPass: false}" class="form-input">
                  <input :type="showPass?'text':'password'" id="pass1" class="!rounded-full" placeholder="Password" />
                  <label for="pass1">Password</label>
                  <span @click="showPass = !showPass" class="absolute ltr:right-5 rtl:left-5 top-1/2 flex size-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full duration-300 hover:bg-neutral-40 dark:hover:bg-neutral-700">
                    <i x-show="showPass" class="las la-eye text-xl"></i>
                    <i x-show="!showPass" class="las la-eye-slash text-xl"></i>
                  </span>
                </div>
              </div>
              <div class="col-span-1">
                <div x-data="{showPass: false}" class="form-input">
                  <input :type="showPass?'text':'password'" id="pass2" class="!rounded-full" placeholder="confirm password" />
                  <label for="pass2">Confirm Password</label>
                  <span @click="showPass = !showPass" class="absolute ltr:right-5 rtl:left-5 top-1/2 flex size-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full duration-300 hover:bg-neutral-40 dark:hover:bg-neutral-700">
                    <i x-show="showPass" class="las la-eye text-xl"></i>
                    <i x-show="!showPass" class="las la-eye-slash text-xl"></i>
                  </span>
                </div>
              </div>

              <div class="col-span-1">
                <div class="flex gap-4 xxl:gap-6 mb-7 xl:mb-10">
                  <button class="btn-primary w-full">Send Request</button>
                  <a class="btn-primary-outlined w-full" href="login.html">Return to sign in</a>
                </div>
                <p>Don't have a code? <button @click.prevent class="font-semibold text-primary-300">Resend Code</button></p>
              </div>
            </form>
          </div>
          <div class="col-span-12 lg:col-span-6 xxl:col-start-7 flex justify-center">
            <div class="size-72 sm:size-[450px] xxl:size-[636px] rounded-full bg-neutral-30 dark:bg-neutral-700 f-center">
              <img src="{{ asset('assets/images/new-password-one.png') }}" alt="" />
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
