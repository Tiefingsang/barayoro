<nav
    :class="[$store.app.sidebar && $store.app.menu == 'vertical' ? 'w-full xl:ltr:ml-[280px] xl:w-[calc(100%-280px)] xl:rtl:mr-[280px]':'w-full', $store.app.sidebar && $store.app.menu == 'hovered' ? 'w-full xl:ltr:ml-[80px] xl:w-[calc(100%-80px)] xl:rtl:mr-[80px]':'w-full', $store.app.menu == 'horizontal' ? 'bg-neutral-20 dark:bg-neutral-903':'bg-neutral-0 dark:bg-neutral-904']"
    class="w-full fixed top-0 p-3 shadow-custom-4 duration-300 z-10"
  >
    <div :class="$store.app.menu == 'horizontal' ? 'max-w-[1704px] w-full right-0 left-0 mx-auto' :''" class="flex justify-between items-center">
      <div class="flex gap-4 xxl:gap-6 items-center">
        <a x-show="$store.app.menu == 'horizontal'" href="index.html" class="text-primary-300 flex gap-3 items-center max-xl:!hidden">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_9642_133645)">
              <path
                d="M20.5716 17.5166L19.674 13.4388L16.5908 16.2554L17.0598 16.403L12.2837 31.7543C1.43017 27.3907 0.00733577 12.5359 9.82304 6.173C15.4788 2.50631 22.7587 3.30374 27.5051 7.6686L28.5416 4.33954C22.7583 -0.132655 14.5448 -0.722943 8.07255 3.47288C-5.2819 12.1298 -1.23273 32.7566 14.4218 35.7129C15.4924 32.2695 18.1055 23.8728 20.1268 17.376L20.5716 17.5166Z"
                fill="currentColor"
              />
              <path
                d="M32.7199 8.96344L31.3295 13.4321C34.4068 21.9951 28.8794 31.3718 19.7571 32.6634L21.288 27.7448C22.6152 28.028 21.5668 27.805 25.4758 28.6385C28.4719 19.0106 30.6186 12.1073 33.164 3.92455L33.6012 4.06323L32.7035 -0.015625L29.6204 2.80102L30.0966 2.95233C28.4298 8.3099 28.3457 8.58051 23.2755 24.8794C21.9979 24.6065 23.0063 24.8217 19.0869 23.9857C15.7039 34.8601 16.9887 30.7289 15.3906 35.8672C30.3023 37.8435 40.5552 21.5237 32.7199 8.96344Z"
                fill="currentColor"
              />
              <path d="M22.5662 23.628L26.3888 11.3387L26.8504 11.4855L25.9527 7.40625L22.8696 10.2229L23.3215 10.3663L19.4062 22.9539L22.5662 23.628Z" fill="currentColor" />
              <path
                d="M14.2726 35.4612C7.21781 34.0536 1.98186 28.7935 0.584283 21.6896C-0.82592 14.5228 2.08941 7.61258 8.19247 3.65624C11.0227 1.82146 14.2985 0.851562 17.6648 0.851562C21.4899 0.851562 25.2559 2.11799 28.2888 4.42044L27.3993 7.27872C24.7149 4.92333 21.2754 3.62999 17.6751 3.62999C14.8432 3.62999 12.087 4.4458 9.70558 5.98993C5.02126 9.02622 2.54734 14.1682 3.08711 19.7451C3.6259 25.3135 7.03382 29.8789 12.2032 31.9569L12.4232 32.0451L17.3339 16.2607L17.0189 16.1613L19.5428 13.8558L20.2779 17.1948L19.9846 17.1019C19.9844 17.1016 14.9144 33.3963 14.2726 35.4612Z"
                fill="currentColor"
              />
              <path d="M19.6875 22.7955L23.596 10.2291L23.2978 10.1345L25.8219 7.82812L26.5572 11.1682L26.2469 11.0695L22.4185 23.3782L19.6875 22.7955Z" fill="currentColor" />
              <path
                d="M17.8402 35.81C17.1252 35.81 16.3969 35.7666 15.6719 35.6802L19.2314 24.2384L23.4197 25.1321L30.3649 2.80778L30.042 2.70465L32.5665 0.398438L33.3018 3.73835L33.0158 3.64752L25.3219 28.3813L21.134 27.4869L19.4414 32.9255L19.783 32.877C24.1618 32.2572 27.9844 29.7263 30.2713 25.9333C32.5598 22.1379 33.0264 17.5832 31.5547 13.425L32.7798 9.48927C35.9381 14.8622 36.016 21.2963 32.964 26.7963C29.8321 32.4404 24.1793 35.8101 17.8426 35.8101C17.8418 35.81 17.8411 35.81 17.8402 35.81Z"
                fill="currentColor"
              />
              <path d="M18.6684 21.3143C19.4295 18.8676 19.9803 17.0977 19.9803 17.0977L20.2736 17.1906L19.5384 13.8516L17.0145 16.1571L17.3295 16.2565L15.8203 21.1074C16.7669 21.2005 17.716 21.2691 18.6684 21.3143Z" fill="currentColor" />
              <path
                d="M3.01171 18.1501C3.0415 13.199 5.46923 8.73446 9.70261 5.98993C12.084 4.4458 14.8402 3.62999 17.6721 3.62999C21.2725 3.62999 24.712 4.92333 27.3963 7.27873L28.2858 4.42044C25.253 2.11799 21.4869 0.851562 17.6618 0.851562C14.2955 0.851562 11.0198 1.82135 8.1895 3.65624C3.42421 6.74548 0.602474 11.6351 0.265625 17.0418C1.17105 17.4347 2.08656 17.8047 3.01171 18.1501Z"
                fill="currentColor"
              />
              <path d="M20.125 21.3709C21.0928 21.3941 22.0634 21.3938 23.0366 21.3732L26.2413 11.0695L26.5516 11.1682L25.8163 7.82812L23.2922 10.1343L23.5903 10.2289L20.125 21.3709Z" fill="currentColor" />
              <path d="M31.5547 13.4279C32.3777 15.7536 32.5933 18.203 32.2243 20.5745C33.1934 20.413 34.1571 20.2237 35.113 20.001C35.4765 16.4044 34.6965 12.7529 32.7798 9.49219L31.5547 13.4279Z" fill="currentColor" />
              <path d="M24.6094 21.3211C25.5991 21.2808 26.5894 21.2223 27.5777 21.1413L33.0196 3.64752L33.3056 3.73835L32.5704 0.398438L30.0458 2.70465L30.3687 2.80778L24.6094 21.3211Z" fill="currentColor" />
            </g>
            <defs>
              <clippath id="clip0_9642_133645">
                <rect width="35.4462" height="36" fill="white" />
              </clippath>
            </defs>
          </svg>
          <span class="h4 shrink-0 max-[380px]:hidden"><span class="text-neutral-700 dark:text-neutral-0 h4">Softify</span></span>
        </a>

        <button :class="$store.app.menu=='horizontal'?'xl:hidden':''" @click="$store.app.toggleSidebar()"><i class="las la-bars text-2xl"></i></button>

        <form :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-0 dark:bg-neutral-904'" class="max-w-[357px] max-md:hidden rounded-lg border focus-within:border-primary-300 border-neutral-30 dark:border-neutral-500 p-1 flex items-center">
          <input type="text" class="px-4 w-full bg-transparent text-sm" placeholder="Search..." />
          <span class="size-8 shrink-0 rounded-full f-center">
            <i class="las la-search text-xl"></i>
          </span>
        </form>
      </div>
      <div class="flex gap-3 xxl:gap-4 items-center">
        <!-- full screen toggle btn -->
        <button title="Toggle Fullscreen" :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-20 dark:bg-neutral-903'" id="fullscreenButton" class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500">
          <i class="las la-expand text-xl full-screen-icon"></i>
        </button>
        <!-- Dark ligth switch -->
        <button
          title="Toggle Theme"
          :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-20 dark:bg-neutral-903'"
          x-cloak
          x-show="$store.app.theme === 'light'"
          @click="$store.app.toggleTheme('dark')"
          class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500"
        >
          <i class="las la-moon"></i>
        </button>
        <button title="Toggle Theme" x-cloak x-show="$store.app.theme === 'dark'" @click="$store.app.toggleTheme('light')" class="flex size-9 items-center justify-center rounded-full border border-neutral-30 bg-neutral-20 text-xl dark:border-neutral-500 dark:bg-neutral-700">
          <i class="las la-sun"></i>
        </button>
        <!-- Language switch -->
        <div x-data="{open:false,selected:'English',options:['English','French','Spanish','Arabic']}" class="relative">
          <button title="Change Language" :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-20 dark:bg-neutral-903'" @click="open = !open" class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500">
            <i class="las la-language"></i>
          </button>
          <div @click.away="open = false" x-show="open" class="absolute w-[150px] z-20 bg-neutral-0 dark:bg-neutral-904 top-full ltr:right-0 shadow-lg rtl:left-0 p-2 rounded-xl">
            <ul class="flex flex-col gap-1">
              <template x-for="option in options">
                <li><span @click="selected = option; open = false" x-text="option" class="flex cursor-pointer duration-300 hover:text-primary-300 rounded-md px-4 py-1.5 hover:bg-primary-50" :class="selected===option ? 'bg-primary-300 text-neutral-0' : ''"></span></li>
              </template>
            </ul>
          </div>
        </div>

        <!-- Notification switch -->
        <div class="relative" x-data="{open:false}">
          <span class="size-4 text-xs absolute -top-1 -right-1 f-center text-neutral-0 bg-primary-300 rounded-full"> 2 </span>
          <button title="Notifications" @click="open = !open" :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-20 dark:bg-neutral-903'" class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500">
            <i class="las la-bell"></i>
          </button>
          <div
            @click.away="open = false"
            x-show="open"
            class="absolute top-full z-10 origin-[60%_0] rounded-md bg-neutral-0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-neutral-904 ltr:-right-[110px] sm:ltr:right-0 sm:ltr:origin-top-right rtl:-left-[120px] sm:rtl:left-0 sm:rtl:origin-top-left"
          >
            <div class="flex items-center justify-between border-b p-3 dark:border-n500 lg:px-4">
              <h5 class="h5">Notifications</h5>
              <a href="#" class="text-xs text-primary-300"> View All </a>
            </div>
            <ul class="flex w-[300px] flex-col p-4">
              <div class="flex cursor-pointer gap-2 rounded-md p-2 duration-300 hover:bg-primary-300/10">
                <img src="assets/images/users/user-s-3.png" width="44" height="40" class="shrink-0 rounded-full" alt="img" />
                <div class="text-sm">
                  <div class="flex gap-1">
                    <span class="font-medium">Otwell</span>
                    <span>Sent a message</span>
                  </div>
                  <span class="text-xs text-n100 dark:text-n50">1 hour ago</span>
                </div>
              </div>
              <div class="flex cursor-pointer gap-2 rounded-md p-2 duration-300 hover:bg-primary-300/10">
                <img src="assets/images/users/user-s-4.png" width="44" height="40" class="shrink-0 rounded-full" alt="img" />
                <div class="text-sm">
                  <div class="flex gap-1">
                    <span class="font-medium">David</span>
                    <span>Left a Comment</span>
                  </div>
                  <span class="text-xs text-n100 dark:text-n50">1 hour ago</span>
                </div>
              </div>
              <div class="flex cursor-pointer gap-2 rounded-md p-2 duration-300 hover:bg-primary-300/10">
                <img src="assets/images/users/user-s-2.png" width="44" height="40" class="shrink-0 rounded-full" alt="img" />
                <div class="text-sm">
                  <div class="flex gap-1">
                    <span class="font-medium">Benjamin</span>
                    <span>Sent a message</span>
                  </div>
                  <span class="text-xs text-n100 dark:text-n50">2 hour ago</span>
                </div>
              </div>
              <div class="flex cursor-pointer gap-2 rounded-md p-2 duration-300 hover:bg-primary-300/10">
                <img src="assets/images/users/user-s-1.png" width="44" height="40" class="shrink-0 rounded-full" alt="img" />
                <div class="text-sm">
                  <div class="flex gap-1">
                    <span class="font-medium">Samuel</span>
                    <span>Uploaded a file</span>
                  </div>
                  <span class="text-xs text-n100 dark:text-n50">Yesterday</span>
                </div>
              </div>
              <div class="flex cursor-pointer gap-2 rounded-md p-2 duration-300 hover:bg-primary-300/10">
                <img src="assets/images/users/user-s-2.png" width="44" height="40" class="shrink-0 rounded-full" alt="img" />
                <div class="text-sm">
                  <div class="flex gap-1">
                    <span class="font-medium">David</span>
                    <span>Left a Comment</span>
                  </div>
                  <span class="text-xs text-n100 dark:text-n50">Yesterday</span>
                </div>
              </div>
            </ul>
          </div>
        </div>

        <!-- user profile -->
        <div x-data="dropdown" class="relative shrink-0">
          <div title="User Profile" @click="toggle" class="size-9 cursor-pointer">
            <img src="assets/images/users/user-s-4.png" class="rounded-full" alt="profile img" />
          </div>
          <div @click.away="close" x-show="isOpen" class="absolute top-full z-20 rounded-md bg-neutral-0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-neutral-904 ltr:right-0 ltr:origin-top-right rtl:left-0 rtl:origin-top-left">
            <div class="flex flex-col items-center border-b border-neutral-30 p-3 text-center dark:border-neutral-500 lg:p-4">
              <img src="assets/images/users/user-s-4.png" width="60" height="60" class="rounded-full" alt="profile img" />
              <h6 class="h6 mt-2">William James</h6>
              <span class="text-sm">james@mail.com</span>
            </div>
            <ul class="flex w-[250px] flex-col p-4">
              <li>
                <a href="user-profile.html" class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                  <span>
                    <i class="las la-user mt-0.5 text-xl"></i>
                  </span>
                  Profile
                </a>
              </li>
              <li>
                <a href="chat.html" class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                  <span>
                    <i class="las la-envelope mt-0.5 text-xl"></i>
                  </span>
                  Messages
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                  <span>
                    <i class="las la-life-ring mt-0.5 text-xl"></i>
                  </span>
                  Help
                </a>
              </li>
              <li>
                <a href="user-account.html" class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                  <span>
                    <i class="las la-cog mt-0.5 text-xl"></i>
                  </span>
                  Settings
                </a>
              </li>
              <li>
                <a href="login.html" class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                  <span>
                    <i class="las la-sign-out-alt mt-0.5 text-xl"></i>
                  </span>
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
