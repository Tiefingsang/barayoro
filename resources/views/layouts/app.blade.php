<!doctype html>
<html dir="ltr" lang="en">

<!-- Mirrored from softivuslab.com/html/softify/dist/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 28 Mar 2026 13:23:42 GMT -->
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('asstes/images/favicon.html') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <title>Barayoro -  Admin Dashboard</title>
  <script defer src="{{ asset('assets/js/app.js') }}"></script><link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"></head>
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

  <body x-cloak x-data="customizer" :class="$store.app.isDarkMode?'dark':''" class="bg-neutral-20 dark:bg-neutral-903 relative">
    <!-- Customizer -->
    <div class="text-neutral-700 dark:text-neutral-10">
  <div
    class="fixed top-0 duration-300 ltr:right-0 ltr:left-auto rtl:left-0 rtl:right-auto bottom-0 w-[312px] overflow-y-auto custom-scrollbar h-screen p-6 xl:p-8 bg-neutral-0 dark:bg-neutral-904 z-[25] shadow-xl"
    x-cloak
    :class="customizerIsOpen?'translate-x-0 opacity-100 visible':'ltr:translate-x-full rtl:-translate-x-full opacity-0 invisible'"
  >
    <div class="flex justify-between items-center pb-4 lg:pb-6 mb-4 lg:mb-6 border-b border-dashed border-neutral-40 dark:border-neutral-500">
      <h4 class="text-xl font-semibold">Settings</h4>
      <div class="flex gap-4 items-center shrink-0 text-xl">
        <button @click="$store.app.resetTheme()"><i class="las la-redo-alt"></i></button>
        <button class="hover:rotate-180 duration-300" @click="closeCustomizer"><i class="las la-times"></i></button>
      </div>
    </div>
    <!-- Color Mode -->
    <p class="md:text-lg font-medium mb-6">Mode</p>
    <div class="flex justify-between items-center gap-4 bb-dashed-n30">
      <button class="border grow p-6 rounded-lg border-primary-300 dark:border-neutral-500 bg-primary-50 text-primary-300 dark:text-neutral-10 dark:bg-neutral-903" @click="$store.app.toggleTheme('light')">
        <i class="las la-sun text-3xl"></i>
      </button>
      <button class="border grow p-6 rounded-lg dark:border-primary-300 bg-neutral-0 dark:text-primary-300 dark:bg-neutral-903" @click="$store.app.toggleTheme('dark')">
        <i class="las la-moon text-3xl"></i>
      </button>
    </div>

    <!-- direction -->
    <p class="md:text-lg font-medium mb-6">Direction</p>
    <div class="flex justify-between items-center gap-4 bb-dashed-n30">
      <button class="border dark:rtl:border-neutral-500 grow p-6 rounded-lg ltr:border-primary-300 ltr:bg-primary-50 ltr:text-primary-300 ltr:dark:text-primary-300 dark:text-neutral-10 dark:bg-neutral-903" @click="$store.app.toggleRTL('ltr')">
        <i class="las la-align-left text-3xl"></i>
      </button>
      <button class="border dark:ltr:border-neutral-500 grow p-6 rounded-lg rtl:border-primary-300 bg-neutral-0 rtl:bg-primary-50 rtl:text-primary-300 dark:bg-neutral-903" @click="$store.app.toggleRTL('rtl')">
        <i class="las la-align-right text-3xl"></i>
      </button>
    </div>

    <!-- Contrast -->
    <p class="md:text-lg font-medium mb-6">Contrast</p>
    <div class="flex justify-between items-center gap-4 bb-dashed-n30">
      <button class="border grow p-6 rounded-lg" @click="$store.app.toggleContrast('low')" :class="$store.app.contrast=='low'?'border-primary-300 bg-primary-50 text-primary-300':'border-neutral-30 dark:border-neutral-500'">
        <i class="las la-adjust text-3xl"></i>
      </button>
      <button class="border flex items-center h-[86px] justify-center grow p-6 rounded-lg" @click="$store.app.toggleContrast('high')" :class="$store.app.contrast=='high'?'border-primary-300 bg-primary-50 text-primary-300':'border-neutral-30 dark:border-neutral-500'">
        <img src="assets/images/contrast.png" width="24" class="dark:brightness-0 dark:invert" alt="" />
      </button>
    </div>

    <!-- Layout -->
    <p class="md:text-lg font-medium mb-6">Layout</p>
    <div class="grid grid-cols-2 items-center gap-4 bb-dashed-n30">
      <!-- Vertical -->
      <div class="col-span-1 border rounded-lg p-6 cursor-pointer" @click="$store.app.toggleMenu('vertical')" :class="$store.app.menu==='vertical'?'border-primary-300':'border-neutral-30 dark:border-neutral-500'">
        <div class="p-1 rounded-md border border-neutral-30 dark:border-neutral-500 flex divide-x divide-neutral-30 dark:divide-neutral-500 gap-1">
          <div class="flex flex-col gap-1">
            <div class="w-2 h-2 rounded-full" :class="$store.app.menu=='vertical'?'bg-primary-300':'bg-neutral-40'"></div>
            <div class="h-[3px] w-[22px] rounded" :class="$store.app.menu=='vertical'?'bg-primary-200':'bg-neutral-40'"></div>
            <div class="h-[3px] w-[12px] rounded" :class="$store.app.menu=='vertical'?'bg-primary-100':'bg-neutral-40'"></div>
          </div>
          <div class="rounded h-[47px] w-[26px]" :class="$store.app.menu=='vertical'?'bg-primary-100':'bg-neutral-40'"></div>
        </div>
      </div>

      <div class="col-span-1 border rounded-lg p-6 cursor-pointer" @click="$store.app.toggleMenu('horizontal')" :class="$store.app.menu==='horizontal'?'border-primary-300':'border-neutral-30 dark:border-neutral-500'">
        <div class="p-1 rounded-md border border-neutral-30 dark:border-neutral-500 flex flex-col divide-x divide-neutral-30 dark:divide-neutral-500 gap-1">
          <div class="flex items-center gap-1">
            <div class="w-2 h-2 rounded-full" :class="$store.app.menu=='horizontal'?'bg-primary-300':'bg-neutral-40'"></div>
            <div class="h-[4px] w-[14px] rounded" :class="$store.app.menu=='horizontal'?'bg-primary-200':'bg-neutral-40'"></div>
            <div class="h-[4px] w-[8px] rounded" :class="$store.app.menu=='horizontal'?'bg-primary-100':'bg-neutral-40'"></div>
          </div>
          <div class="rounded h-[34px] w-[46px]" :class="$store.app.menu=='horizontal'?'bg-primary-100':'bg-neutral-40'"></div>
        </div>
      </div>
      <div class="col-span-1 border rounded-lg p-6 cursor-pointer" @click="$store.app.toggleMenu('hovered')" :class="$store.app.menu==='hovered'?'border-primary-300':'border-neutral-30 dark:border-neutral-500'">
        <div class="p-1 rounded-md border border-neutral-30 dark:border-neutral-500 flex divide-x divide-neutral-30 dark:divide-neutral-500 gap-1">
          <div class="flex flex-col gap-1">
            <div class="w-2 h-2 rounded-full" :class="$store.app.menu=='hovered'?'bg-primary-300':'bg-neutral-40'"></div>
            <div class="h-[2px] w-[8px] rounded" :class="$store.app.menu=='hovered'?'bg-primary-200':'bg-neutral-40'"></div>
            <div class="h-[2px] w-[4px] rounded" :class="$store.app.menu=='hovered'?'bg-primary-100':'bg-neutral-40'"></div>
          </div>
          <div class="rounded h-[47px] w-[40px]" :class="$store.app.menu=='hovered'?'bg-primary-100':'bg-neutral-40'"></div>
        </div>
      </div>
    </div>

    <!-- strech -->
    <p class="md:text-lg font-medium mb-6">Stretch</p>
    <div class="bb-dashed-n30">
      <button class="border grow p-6 f-center rounded-lg border-neutral-30 dark:border-neutral-500 w-full" @click="$store.app.toggleStretch()">
        <span class="flex items-center gap-1 text-lg" x-show="$store.app.stretch">
          <i class="las la-angle-right"></i>
          <span class="w-8 bb-dashed-n30"></span>
          <i class="las la-angle-left"></i>
        </span>
        <span class="flex items-center gap-1 text-lg text-primary-300" x-show="!$store.app.stretch">
          <i class="las la-angle-left"></i>
          <span class="w-28 bb-dashed-n30 !border-primary-300"></span>
          <i class="las la-angle-right"></i>
        </span>
      </button>
    </div>

    <!-- Presets -->
    <p class="md:text-lg font-medium mb-6">Presets</p>
    <div class="grid grid-cols-3 gap-4 bb-dashed-n30">
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='44 123 229'}" @click="$store.app.changeColor('44 123 229')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='44 123 229'}" class="size-5 duration-300 rounded-full bg-[#2C7BE5]"></div>
      </div>
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='142 51 255'}" @click="$store.app.changeColor('142 51 255')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='142 51 255'}" class="size-5 duration-300 rounded-full bg-secondary-300"></div>
      </div>
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='0 184 217'}" @click="$store.app.changeColor('0 184 217')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='0 184 217'}" class="size-5 duration-300 rounded-full bg-info-300"></div>
      </div>
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='34 197 94'}" @click="$store.app.changeColor('34 197 94')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='34 197 94'}" class="size-5 duration-300 rounded-full bg-success-300"></div>
      </div>
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='189 123 0'}" @click="$store.app.changeColor('189 123 0')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='189 123 0'}" class="size-5 duration-300 rounded-full bg-[#BD7B00]"></div>
      </div>
      <div :class="{'border-primary-200 dark:border-primary-200':$store.app.currentColor=='255 86 48'}" @click="$store.app.changeColor('255 86 48')" class="col-span-1 cursor-pointer size-[72px] rounded-md border border-neutral-20 dark:border-neutral-500 f-center">
        <div :class="{'size-8':$store.app.currentColor=='255 86 48'}" class="size-5 duration-300 rounded-full bg-error-300"></div>
      </div>
    </div>
  </div>

  <button @click="toggleCustomizer" class="fixed bottom-5 ltr:right-5 rtl:left-5 z-[24] bg-primary-300 rounded-xl flex px-4 py-2 items-center gap-2 justify-center text-neutral-0 shadow-xl">
    <i class="las la-cog animate-spin-slow"></i>
    <span class="text-sm"> Customize </span>
  </button>

  <div x-show="customizerIsOpen" @click="closeCustomizer" class="fixed z-[21] bg-neutral-900 bg-opacity-10 inset-0 duration-300"></div>
</div>


    <!-- loader -->
    <!-- screen loader -->
<div x-cloak class="screen_loader animate__animated duration-700 fixed inset-0 z-[60] grid place-content-center bg-neutral-400">
  <svg viewBox="25 25 50 50">
    <circle r="20" cy="50" cx="50"></circle>
  </svg>
</div>


    <!-- Navigation -->
    <section class="text-neutral-700 dark:text-neutral-20 bg-neutral-0 dark:bg-neutral-904">
  <!-- Topbar -->
  @include('partials.navbar')
   <!-- Messages flash -->
    @include('components.flash-messages')

  <!-- Vertical Sidebar -->
  @include('partials.sidebar')

  <!-- Horizontal sidebar -->
  @include('partials.horisontal_sidebar')

  <!-- Sidebar Overlay -->
  <div @click="$store.app.sidebar=false" :class="$store.app.sidebar?'block':'hidden'" class="fixed inset-0 z-[11] bg-neutral-900/80 xl:hidden"></div>
</section>


    <!-- Main Content -->
    <main
      :class="[$store.app.sidebar && $store.app.menu=='vertical'?'w-full xl:ltr:ml-[280px] xl:rtl:mr-[280px] xl:w-[calc(100%-280px)]':'w-full',$store.app.sidebar && $store.app.menu=='hovered'?'w-full xl:ltr:ml-[80px] xl:w-[calc(100%-80px)] xl:rtl:mr-[80px]':'w-full', $store.app.menu == 'horizontal' && 'xl:!pt-[118px]', $store.app.contrast=='high'?'bg-neutral-0 dark:bg-neutral-904':'bg-neutral-20 dark:bg-neutral-903']"
      class="w-full text-neutral-700 min-h-screen dark:text-neutral-20 pt-[60px] md:pt-[66px] duration-300"
    >
      @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')


    <!-- js libraries and custom scripts -->
    <script src="{{ asset('assets/js/libs/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/alpine.collapse.js') }}"></script>
    <script src="{{ asset('assets/js/libs/alpine.persist.js') }}"></script>
    <script defer src="{{ asset('assets/js/libs/alpine.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/nice-select2.js') }}"></script>
    <script src="{{ asset('assets/js/charts.js') }}"></script>

    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('chart', () => ({
          marketGraph: null,
          portfolio: null,
          init() {
            isDark = this.$store.app.theme === 'dark' ? true : false
            isRtl = this.$store.app.dir === 'rtl' ? true : false

            setTimeout(() => {
              this.marketGraph = new ApexCharts(this.$refs.marketGraph, this.marketGraphOptions)
              this.marketGraph.render()

              this.portfolio = new ApexCharts(this.$refs.portfolio, this.portfolioOptions)
              this.portfolio.render()
            }, 300)

            this.$watch('$store.app.theme', () => {
              this.refreshOptions()
            })

            this.$watch('$store.app.rtlClass', () => {
              this.refreshOptions()
            })
          },

          refreshOptions() {
            isDark = this.$store.app.theme === 'dark' ? true : false
            isRtl = this.$store.app.dir === 'rtl' ? true : false
            this.marketGraph.updateOptions(this.marketGraphOptions)
            this.portfolio.updateOptions(this.portfolioOptions)
          },

          get marketGraphOptions() {
            return {
              series: [
                {
                  data: [
                    {
                      x: '2019-06-21T00:00:00.000Z',
                      y: [171.82, 172.54, 166.34, 169.87]
                    },
                    {
                      x: '2019-06-24T00:00:00.000Z',
                      y: [170.23, 170.64, 164.12, 165.3]
                    },
                    {
                      x: '2019-06-25T00:00:00.000Z',
                      y: [165.94, 165.94, 155.64, 158.59]
                    },
                    {
                      x: '2019-06-26T00:00:00.000Z',
                      y: [160.5, 161.75, 150.29, 150.69]
                    },
                    {
                      x: '2019-06-27T00:00:00.000Z',
                      y: [150.88, 153.35, 148.04, 151.49]
                    },
                    {
                      x: '2019-06-28T00:00:00.000Z',
                      y: [151.91, 152.78, 145.26, 152.09]
                    },
                    {
                      x: '2019-07-01T00:00:00.000Z',
                      y: [155.32, 155.32, 143.85, 147.67]
                    },
                    {
                      x: '2019-07-02T00:00:00.000Z',
                      y: [145.62, 154.41, 144.38, 154.05]
                    },
                    {
                      x: '2019-07-03T00:00:00.000Z',
                      y: [155.44, 158.45, 153.46, 154.98]
                    },
                    {
                      x: '2019-07-05T00:00:00.000Z',
                      y: [152.42, 156.02, 151.34, 155.9]
                    },
                    {
                      x: '2019-07-08T00:00:00.000Z',
                      y: [154.94, 158.68, 153.01, 157.04]
                    },
                    {
                      x: '2019-07-09T00:00:00.000Z',
                      y: [156.56, 158.06, 154.39, 157.72]
                    },
                    { x: '2019-07-10T00:00:00.000Z', y: [159, 161.38, 156.44, 157.23] },
                    {
                      x: '2019-07-11T00:00:00.000Z',
                      y: [157.26, 159.33, 154.33, 158.04]
                    },
                    {
                      x: '2019-07-12T00:00:00.000Z',
                      y: [157.24, 158.49, 151.03, 153.1]
                    },
                    {
                      x: '2019-07-15T00:00:00.000Z',
                      y: [153.29, 157.1, 152.39, 153.17]
                    },
                    {
                      x: '2019-07-16T00:00:00.000Z',
                      y: [153.05, 155.46, 146.65, 151.79]
                    },
                    {
                      x: '2019-07-17T00:00:00.000Z',
                      y: [152.4, 161.77, 151.57, 160.79]
                    },
                    {
                      x: '2019-07-18T00:00:00.000Z',
                      y: [159.77, 163.17, 157.15, 162.93]
                    },
                    { x: '2019-07-19T00:00:00.000Z', y: [164.88, 166.41, 161, 164] },
                    {
                      x: '2019-07-22T00:00:00.000Z',
                      y: [164.39, 166.3, 160.23, 160.86]
                    },
                    {
                      x: '2019-07-23T00:00:00.000Z',
                      y: [162.56, 163.1, 154.34, 155.97]
                    },
                    { x: '2019-07-24T00:00:00.000Z', y: [155.12, 158, 152.18, 156.02] },
                    { x: '2019-07-25T00:00:00.000Z', y: [156, 158.58, 153.33, 155.7] },
                    {
                      x: '2019-07-26T00:00:00.000Z',
                      y: [156.73, 159.5, 155.5, 158.06]
                    },
                    {
                      x: '2019-07-29T00:00:00.000Z',
                      y: [157.9, 158.52, 143.45, 144.89]
                    },
                    { x: '2019-07-30T00:00:00.000Z', y: [144.01, 146.08, 140, 142.03] },
                    { x: '2019-07-31T00:00:00.000Z', y: [143, 146.9, 140.88, 143.22] },
                    {
                      x: '2019-08-01T00:00:00.000Z',
                      y: [142.93, 148.78, 138.12, 143.66]
                    },
                    {
                      x: '2019-08-02T00:00:00.000Z',
                      y: [142.23, 143.5, 137.35, 140.78]
                    },
                    {
                      x: '2019-08-05T00:00:00.000Z',
                      y: [135.87, 141.14, 134.32, 135.88]
                    },
                    { x: '2019-08-06T00:00:00.000Z', y: [137.98, 141.09, 137.2, 139] },
                    {
                      x: '2019-08-07T00:00:00.000Z',
                      y: [135.78, 143.2, 135.41, 142.63]
                    },
                    {
                      x: '2019-08-08T00:00:00.000Z',
                      y: [144.06, 150.79, 144.06, 149.51]
                    },
                    {
                      x: '2019-08-09T00:00:00.000Z',
                      y: [148.78, 151.37, 146.48, 148.9]
                    },
                    {
                      x: '2019-08-12T00:00:00.000Z',
                      y: [148.47, 148.47, 142.16, 143.51]
                    },
                    {
                      x: '2019-08-13T00:00:00.000Z',
                      y: [142.02, 147.82, 142.02, 146.23]
                    },
                    {
                      x: '2019-08-14T00:00:00.000Z',
                      y: [142.92, 145.15, 138.36, 142.28]
                    },
                    { x: '2019-08-15T00:00:00.000Z', y: [142.7, 144.5, 139.8, 141.65] },
                    {
                      x: '2019-08-16T00:00:00.000Z',
                      y: [143.23, 146.73, 142.51, 143.79]
                    },
                    {
                      x: '2019-08-19T00:00:00.000Z',
                      y: [146.67, 147.69, 140.34, 140.58]
                    },
                    { x: '2019-08-20T00:00:00.000Z', y: [140, 140.9, 137.34, 139.19] },
                    {
                      x: '2019-08-21T00:00:00.000Z',
                      y: [141.95, 149.51, 140.75, 148.08]
                    },
                    {
                      x: '2019-08-22T00:00:00.000Z',
                      y: [148.11, 149.27, 142.16, 142.34]
                    },
                    { x: '2019-08-23T00:00:00.000Z', y: [141.97, 146, 138.62, 139.37] },
                    {
                      x: '2019-08-26T00:00:00.000Z',
                      y: [142.36, 146.92, 140.38, 146.65]
                    },
                    {
                      x: '2019-08-27T00:00:00.000Z',
                      y: [153.05, 158.25, 149.15, 150.21]
                    },
                    {
                      x: '2019-08-28T00:00:00.000Z',
                      y: [154.26, 156.89, 145.35, 147.98]
                    },
                    {
                      x: '2019-08-29T00:00:00.000Z',
                      y: [150.5, 155.91, 148.25, 154.36]
                    },
                    { x: '2019-08-30T00:00:00.000Z', y: [155.59, 157.19, 148, 152.31] },
                    { x: '2019-09-03T00:00:00.000Z', y: [150.7, 155, 147.35, 149.94] },
                    {
                      x: '2019-09-04T00:00:00.000Z',
                      y: [152.98, 160.41, 152.98, 158.66]
                    },
                    { x: '2019-09-05T00:00:00.000Z', y: [156.66, 159, 142.35, 150.44] },
                    {
                      x: '2019-09-06T00:00:00.000Z',
                      y: [149.66, 150.33, 140.17, 140.63]
                    },
                    {
                      x: '2019-09-09T00:00:00.000Z',
                      y: [140.62, 142.56, 125.2, 131.37]
                    },
                    {
                      x: '2019-09-10T00:00:00.000Z',
                      y: [127.43, 136.25, 126.14, 129.02]
                    },
                    {
                      x: '2019-09-11T00:00:00.000Z',
                      y: [128.58, 132.49, 126.45, 130.58]
                    },
                    { x: '2019-09-12T00:00:00.000Z', y: [133, 135.8, 128.3, 128.53] },
                    {
                      x: '2019-09-13T00:00:00.000Z',
                      y: [127.74, 128.18, 121.12, 123.29]
                    },
                    { x: '2019-09-16T00:00:00.000Z', y: [121.29, 132.12, 120.05, 132] },
                    { x: '2019-09-17T00:00:00.000Z', y: [131.56, 136.08, 129.71, 136] },
                    {
                      x: '2019-09-18T00:00:00.000Z',
                      y: [136.06, 136.74, 131.19, 134.28]
                    },
                    { x: '2019-09-19T00:00:00.000Z', y: [134.21, 135, 130.36, 131.46] },
                    {
                      x: '2019-09-20T00:00:00.000Z',
                      y: [133.01, 134.9, 129.52, 131.82]
                    }
                  ]
                }
              ],
              chart: {
                toolbar: {
                  show: false
                },
                type: 'candlestick',
                height: '380'
              },
              responsive: [
                {
                  breakpoint: 580,
                  options: {
                    chart: {
                      height: 280
                    }
                  }
                }
              ],
              plotOptions: {
                candlestick: {
                  colors: {
                    upward: '#2C7BE5',
                    downward: '#FFAB00'
                  }
                }
              },

              xaxis: {
                type: 'category',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                tooltip: {
                  enabled: false
                },
                axisBorder: {
                  show: false
                },
                axisTicks: {
                  show: false
                },
                labels: {
                  show: false
                }
              },
              yaxis: {
                tooltip: {
                  enabled: false
                  // followCursor: true
                },
                labels: {
                  // show: window.innerWidth > 768 ? true : false,
                  formatter: function (val) {
                    return val.toFixed(0)
                  }
                }
              },
              grid: {
                xaxis: {
                  lines: {
                    show: false
                  }
                },
                yaxis: {
                  lines: {
                    show: true
                  }
                }
              },
              tooltip: {
                enabled: true
              },
              legend: {
                position: 'bottom',
                itemMargin: {
                  vertical: 8,
                  horizontal: 20
                },
                horizontalAlign: 'center',
                markers: {
                  width: 5,
                  height: 5,
                  offsetX: isRtl ? 5 : -5
                }
              }
            }
          },
          get portfolioOptions() {
            return {
              series: [44, 55, 41, 17, 15],
              chart: {
                type: 'donut',
                height: 350
              },
              fill: {
                colors: ['#5D69F4', '#2C7BE5', '#FFC861', '#FF6161', '#775DD0']
              },
              plotOptions: {
                pie: {
                  donut: {
                    labels: {
                      show: true,
                      value: {
                        fontSize: '28px',
                        fontWeight: 600,
                        offsetY: 2
                      },
                      total: {
                        show: true,
                        label: 'Total',
                        fontSize: '20px',
                        formatter: () => '$105145'
                      }
                    }
                  }
                }
              },
              legend: {
                show: false
              },
              dataLabels: {
                style: {
                  fontSize: '10px',
                  fontWeight: 400
                }
              }
            }
          }
        }))
      })
    </script>
  </body>


</html>
