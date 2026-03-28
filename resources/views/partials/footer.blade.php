<footer
  :class="[$store.app.sidebar && $store.app.menu=='vertical'?'w-full xl:ltr:ml-[280px] xl:rtl:mr-[280px] xl:w-[calc(100%-280px)]':'w-full',$store.app.sidebar && $store.app.menu=='hovered'?'w-full xl:ltr:ml-[80px] xl:w-[calc(100%-80px)] xl:rtl:mr-[80px]':'w-full']"
  class="footer bg-neutral-0 dark:bg-neutral-904 text-neutral-700 dark:text-neutral-20"
>
  <div :class="$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto':''" class="flex flex-col items-center justify-center gap-3 px-4 py-5 lg:flex-row lg:justify-between xxl:px-8 xxl:py-6">
    <p class="text-sm max-md:w-full max-md:text-center">
      Copyright @ <span id="current-year"></span>
      <a class="text-primary-300 font-medium" href="index.html"> Softify </a>
      . All Rights Reserved
    </p>

    <ul class="flex gap-3 text-sm max-lg:w-full max-lg:justify-center lg:gap-4">
      <li>
        <a href="#" class="footer-link">Help Center</a>
      </li>
      <li>
        <a href="#" class="footer-link">Privacy Policy</a>
      </li>
    </ul>
  </div>
</footer>
