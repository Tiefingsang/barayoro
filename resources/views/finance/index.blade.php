@extends('layouts.app')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6">
        <div x-data="chart" class="grid grid-cols-12 gap-4 xxl:gap-6">
          <!-- statistics -->
          <div class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl bg-primary-300/30 overflow-hidden">
            <div class="px-3 pt-3 md:px-4 md:pt-4 xl:px-6 xl:pt-6 xxl:px-8 xxl:pt-8 flex justify-between items-start">
              <div>
                <p class="m-text text-[#006644] dark:text-neutral-10 font-medium mb-4">Income</p>
                <h3 class="mb-4">$66,745</h3>
                <div class="flex gap-3 items-center">
                  <span class="text-[#006644] dark:text-neutral-10 flex items-center gap-2"><i class="las la-arrow-up text-lg"></i> 55.6%</span>
                  <span>than last month</span>
                </div>
              </div>
              <span class="size-[52px] rounded-full bg-[#00754E] text-neutral-0 f-center">
                <i class="las la-arrow-down text-3xl"></i>
              </span>
            </div>
            <div class="bank-stat-one"></div>
          </div>

          <div class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl bg-secondary-300/30 overflow-hidden">
            <div class="px-3 pt-3 md:px-4 md:pt-4 xl:px-6 xl:pt-6 xxl:px-8 xxl:pt-8 flex justify-between items-start">
              <div>
                <p class="m-text text-[#571F9C] dark:text-neutral-10 font-medium mb-4">Expense</p>
                <h3 class="mb-4">$9,145</h3>
                <div class="flex gap-3 items-center">
                  <span class="text-[#571F9C] dark:text-neutral-10 flex items-center gap-2"><i class="las la-arrow-up text-lg"></i> 55.6%</span>
                  <span>than last month</span>
                </div>
              </div>
              <span class="size-[52px] rounded-full bg-[#571F9C] text-neutral-0 f-center">
                <i class="las la-arrow-up text-3xl"></i>
              </span>
            </div>
            <div class="bank-stat-two"></div>
          </div>

          <div dir="ltr" x-data="modal" class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl relative">
            <div class="swiper cardSlider">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] bg-warning-300/30 after:size-80 after:absolute after:bg-warning-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                    <img src="assets/images/visa-bg.png" class="absolute top-0 right-20 opacity-5 dark:opacity-10" alt="" />
                    <div class="flex justify-between items-start mb-5 xl:mb-8">
                      <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Current Balance</p>
                        <h3 class="mb-4">$80,700.00</h3>
                      </div>
                      <button @click="openModal" class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-plus text-3xl"></i>
                      </button>
                    </div>
                    <div class="flex gap-2 mb-5 xl:mb-8">
                      <img src="assets/images/visa-m.png" alt="" />
                      <p>•••• •••• •••• 8854</p>
                    </div>
                    <div class="flex gap-5">
                      <div>
                        <p class="s-text mb-3">Card Holder</p>
                        <p class="xl-text font-medium">Felecia Brown</p>
                      </div>
                      <div>
                        <p class="s-text mb-3">Valid Dates</p>
                        <p class="xl-text font-medium">12/24</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] after:size-80 after:absolute bg-error-300/30 after:bg-error-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                    <div class="flex justify-between items-start mb-5 xl:mb-8">
                      <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Current Balance</p>
                        <h3 class="mb-4">$80,700.00</h3>
                      </div>
                      <button @click="openModal" class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-plus text-3xl"></i>
                      </button>
                    </div>
                    <div class="flex gap-2 mb-5 xl:mb-8">
                      <img src="assets/images/visa-m.png" alt="" />
                      <p>•••• •••• •••• 8854</p>
                    </div>
                    <div class="flex gap-5">
                      <div>
                        <p class="s-text mb-3">Card Holder</p>
                        <p class="xl-text font-medium">Felecia Brown</p>
                      </div>
                      <div>
                        <p class="s-text mb-3">Valid Dates</p>
                        <p class="xl-text font-medium">12/24</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] bg-warning-300/30 after:size-80 after:absolute after:bg-warning-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                    <img src="assets/images/visa-bg.png" class="absolute top-0 right-20 opacity-5 dark:opacity-10" alt="" />
                    <div class="flex justify-between items-start mb-5 xl:mb-8">
                      <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Current Balance</p>
                        <h3 class="mb-4">$80,700.00</h3>
                      </div>
                      <button @click="openModal" class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-plus text-3xl"></i>
                      </button>
                    </div>
                    <div class="flex gap-2 mb-5 xl:mb-8">
                      <img src="assets/images/visa-m.png" alt="" />
                      <p>•••• •••• •••• 8854</p>
                    </div>
                    <div class="flex gap-5">
                      <div>
                        <p class="s-text mb-3">Card Holder</p>
                        <p class="xl-text font-medium">Felecia Brown</p>
                      </div>
                      <div>
                        <p class="s-text mb-3">Valid Dates</p>
                        <p class="xl-text font-medium">12/24</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] after:size-80 after:absolute bg-error-300/30 after:bg-error-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                    <div class="flex justify-between items-start mb-5 xl:mb-8">
                      <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Current Balance</p>
                        <h3 class="mb-4">$80,700.00</h3>
                      </div>
                      <button @click="openModal" class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-plus text-3xl"></i>
                      </button>
                    </div>
                    <div class="flex gap-2 mb-5 xl:mb-8">
                      <img src="assets/images/visa-m.png" alt="" />
                      <p>•••• •••• •••• 8854</p>
                    </div>
                    <div class="flex gap-5">
                      <div>
                        <p class="s-text mb-3">Card Holder</p>
                        <p class="xl-text font-medium">Felecia Brown</p>
                      </div>
                      <div>
                        <p class="s-text mb-3">Valid Dates</p>
                        <p class="xl-text font-medium">12/24</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] bg-warning-300/30 after:size-80 after:absolute after:bg-warning-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                    <img src="assets/images/visa-bg.png" class="absolute top-0 right-20 opacity-5 dark:opacity-10" alt="" />
                    <div class="flex justify-between items-start mb-5 xl:mb-8">
                      <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Current Balance</p>
                        <h3 class="mb-4">$80,700.00</h3>
                      </div>
                      <button @click="openModal" class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-plus text-3xl"></i>
                      </button>
                    </div>
                    <div class="flex gap-2 mb-5 xl:mb-8">
                      <img src="assets/images/visa-m.png" alt="" />
                      <p>•••• •••• •••• 8854</p>
                    </div>
                    <div class="flex gap-5">
                      <div>
                        <p class="s-text mb-3">Card Holder</p>
                        <p class="xl-text font-medium">Felecia Brown</p>
                      </div>
                      <div>
                        <p class="s-text mb-3">Valid Dates</p>
                        <p class="xl-text font-medium">12/24</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <template x-teleport="body">
              <div class="fixed inset-0 z-[999] hidden overflow-y-auto bg-neutral-900/80 dark:bg-neutral-40/80 text-neutral-700 dark:text-neutral-20" :class="isOpen && '!block'">
                <div class="flex min-h-screen items-center justify-center px-4" @click.self="closeModal">
                  <div x-show="isOpen" x-transition x-transition.duration.300 class="panel my-8 w-full max-w-lg overflow-hidden rounded-lg border-0 bg-neutral-0 p-3 dark:bg-neutral-904 sm:p-4 md:p-6 lg:p-8">
                    <div class="mb-4 flex items-center justify-between bb-dashed-n30">
                      <h4>Add New Card</h4>
                      <i class="las la-times cursor-pointer text-xl" @click="closeModal"></i>
                    </div>
                    <form>
                      <div class="mt-6 grid grid-cols-2 gap-4 xl:mt-8 xxxl:gap-6">
                        <div class="col-span-2">
                          <label for="card-number" class="mb-4 block font-medium md:text-lg"> Card Number </label>
                          <div class="relative rounded-3xl border border-neutral-30 bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-903">
                            <input type="number" class="w-full rounded-3xl bg-transparent px-6 py-2.5 text-sm md:py-3" placeholder="5890 - 6858 - 6332 - 9843" id="card-number" required />
                            <img src="assets/images/mastercard.png" class="absolute top-1/2 -translate-y-1/2 ltr:right-4 rtl:left-4" width="20" height="20" alt="mastercard" />
                          </div>
                        </div>
                        <div class="col-span-2">
                          <label for="holder" class="mb-4 block font-medium md:text-lg"> Card Holder </label>
                          <input type="text" class="w-full rounded-3xl border border-neutral-30 bg-neutral-20 px-6 py-2.5 text-sm dark:border-neutral-500 dark:bg-neutral-903 md:py-3" placeholder="Enter Name" id="holder" required />
                        </div>

                        <div class="col-span-2 md:col-span-1">
                          <label for="month" class="mb-4 block font-medium md:text-lg"> Month </label>
                          <input type="number" class="w-full rounded-3xl border border-neutral-30 bg-neutral-20 px-6 py-2.5 text-sm dark:border-neutral-500 dark:bg-neutral-903 md:py-3" placeholder="12" id="month" required />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                          <label for="year" class="mb-4 block font-medium md:text-lg"> Year </label>
                          <input type="number" class="w-full rounded-3xl border border-neutral-30 bg-neutral-20 px-6 py-2.5 text-sm dark:border-neutral-500 dark:bg-neutral-903 md:py-3" placeholder="2027" id="year" required />
                        </div>
                        <div class="col-span-2 mt-4">
                          <button class="btn-primary w-full" type="submit">Add Card</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </template>
            <div class="card-pagination"></div>
          </div>

          <!-- Balance statictics -->
          <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box overflow-hidden">
            <div class="bb-dashed-n30 flex flex-col sm:flex-row justify-center sm:justify-between gap-4 items-center">
              <div>
                <h4>Balance statictics</h4>
                <p class="m-text mt-2">(+43%) than last year</p>
              </div>
              <div class="flex items-center gap-3">
                <span>Sort By : </span>
                <select name="sort" class="nc-select n20">
                  <option value="day">Last 15 Days</option>
                  <option value="week">Last 1 Month</option>
                  <option value="year">Last 6 Month</option>
                </select>
              </div>
            </div>
            <div x-ref="balanceStats"></div>
          </div>

          <!-- Quick Transfer -->
          <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Quick Transfer</h4>
            <div class="flex justify-between items-center mb-6">
              <p class="l-text font-medium">Recent</p>
              <a href="#" class="h6 text-primary-300 font-semibold">View All <i class="las la-arrow-right text-lg"></i></a>
            </div>
            <div dir="ltr" class="mb-4 xl:mb-6 flex gap-4 xxl:gap-6 justify-between items-center">
              <button class="card-user-prev shrink-0 size-10 flex rounded-full border border-primary-300 justify-center items-center text-primary-300 hover:bg-primary-300 hover:text-neutral-0 duration-300"><i class="las la-angle-left text-xl"></i></button>
              <div class="swiper cardUser py-3">
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-1.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-2.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-3.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-4.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-5.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                  <div class="swiper-slide">
                    <img src="assets/images/avatar/avatar-big-4.png" width="48" height="48" class="rounded-full" alt="" />
                  </div>
                </div>
              </div>
              <button class="card-user-next shrink-0 size-10 flex rounded-full border border-primary-300 justify-center items-center text-primary-300 hover:bg-primary-300 hover:text-neutral-0 duration-300"><i class="las la-angle-right text-xl"></i></button>
            </div>
            <div x-data="{isOpen:false,selected:'X Bank', items:['X Bank','Y Bank','Z Bank']}" class="relative mb-6">
              <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-30 px-6 py-3 dark:border-neutral-500">
                <span x-text="selected"></span>
                <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Bank Name</span>
              </div>
              <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                <template x-for="item in items">
                  <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300  text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item"></div>
                </template>
              </div>
            </div>
            <div class="flex gap-6">
              <div x-data="{isOpen:false,selected:'$', items:['$','€','¥','£','₹']}" class="relative w-28">
                <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-30 px-5 py-3.5 dark:border-neutral-500">
                  <span x-text="selected"></span>
                  <i class="las la-angle-down duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                  <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Currency</span>
                </div>
                <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <template x-for="item in items">
                    <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300  text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item"></div>
                  </template>
                </div>
              </div>
              <div class="form-input">
                <input type="number" class="!py-3" id="amount" placeholder="Textfield" />
                <label for="amount">Enter Amount</label>
              </div>
            </div>
            <div class="flex justify-between items-center my-5 xl:my-8">
              <p class="font-medium l-text">Your Balance</p>
              <p class="l-text">$99.24</p>
            </div>
            <button class="w-full btn-primary">Transfer Now</button>
          </div>

          <!-- Expenses Categories -->
          <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box overflow-hidden">
            <div class="bb-dashed-n30 flex justify-between items-center">
              <h4>Expenses Categories</h4>
              <p class="m-text mt-2">(+43%) than last year</p>
            </div>
            <div class="grid grid-cols-2 bb-dashed-n30 divide-x divide-neutral-30 divide-dashed dark:divide-neutral-500">
              <div class="col-span-1">
                <p class="m-text mb-3">Categories</p>
                <h3>9</h3>
              </div>
              <div class="col-span-1 ltr:pl-10 rtl:pr-10">
                <p class="m-text mb-3">Categories Cost</p>
                <h3>$18,765</h3>
              </div>
            </div>
            <div class="pie-chart xxl:w-[90%] xxl:mx-auto" x-ref="expenseCategories"></div>
          </div>

          <!-- current visits -->
          <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Contacts</h4>
            <div class="flex flex-col gap-4 lg:gap-6 xxl:gap-8">
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <img src="assets/images/avatar/avatar-big-1.png" width="60" alt="" />
                  <div>
                    <p class="l-text font-medium mb-2">Devon Lane</p>
                    <p class="s-text">example@gmail.com</p>
                  </div>
                </div>
                <span class="size-12 rounded-full bg-neutral-20 dark:bg-neutral-903 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </span>
              </div>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <img src="assets/images/avatar/avatar-big-2.png" width="60" alt="" />
                  <div>
                    <p class="l-text font-medium mb-2">Kristin Watson</p>
                    <p class="s-text">example@gmail.com</p>
                  </div>
                </div>
                <span class="size-12 rounded-full bg-neutral-20 dark:bg-neutral-903 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </span>
              </div>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <img src="assets/images/avatar/avatar-big-3.png" width="60" alt="" />
                  <div>
                    <p class="l-text font-medium mb-2">Esther Howard</p>
                    <p class="s-text">example@gmail.com</p>
                  </div>
                </div>
                <span class="size-12 rounded-full bg-neutral-20 dark:bg-neutral-903 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </span>
              </div>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <img src="assets/images/avatar/avatar-big-4.png" width="60" alt="" />
                  <div>
                    <p class="l-text font-medium mb-2">Robert Fox</p>
                    <p class="s-text">example@gmail.com</p>
                  </div>
                </div>
                <span class="size-12 rounded-full bg-neutral-20 dark:bg-neutral-903 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </span>
              </div>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                  <img src="assets/images/avatar/avatar-big-5.png" width="60" alt="" />
                  <div>
                    <p class="l-text font-medium mb-2">Darrell Steward</p>
                    <p class="s-text">example@gmail.com</p>
                  </div>
                </div>
                <span class="size-12 rounded-full bg-neutral-20 dark:bg-neutral-903 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </span>
              </div>
            </div>
            <a href="#" class="h6 text-primary-300 inline-flex items-center gap-2 font-semibold mt-4 xl:mt-6">View All <i class="las la-arrow-right text-lg"></i></a>
          </div>

          <!-- recent transitions -->
          <div class="col-span-12 xl:col-span-7 xxl:col-span-8">
            <div class="white-box">
              <div class="bb-dashed-n30 flex flex-col gap-4 justify-center sm:justify-between sm:flex-row items-center">
                <h4>Recent Transitions</h4>
                <div class="flex items-center gap-3">
                  <span>Sort By : </span>
                  <select name="sort" class="nc-select n20">
                    <option value="day">Last 15 Days</option>
                    <option value="week">Last 1 Month</option>
                    <option value="year">Last 6 Month</option>
                  </select>
                </div>
              </div>
              <div x-data="{dense: false}" class="checkboxes-container overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                  <thead class="text-left">
                    <tr class="bg-neutral-20 dark:bg-neutral-903">
                      <th class="px-6 duration-300" :class="dense? 'py-2': 'py-3 lg:py-5'">Description</th>
                      <th class="px-6 duration-300" :class="dense? 'py-2': 'py-3 lg:py-5'">Date</th>
                      <th class="px-6 duration-300" :class="dense? 'py-2': 'py-3 lg:py-5'">Amount</th>
                      <th class="px-6 duration-300" :class="dense? 'py-2': 'py-3 lg:py-5'">Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-1.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-error-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Payment for</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-primary-300 bg-primary-50 border border-neutral-30 dark:border-neutral-500">Completed</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-2.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-primary-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Receive money from</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-primary-300 bg-primary-50 border border-neutral-30 dark:border-neutral-500">Completed</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-3.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-error-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Payment for</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-primary-300 bg-primary-50 border border-neutral-30 dark:border-neutral-500">Completed</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-4.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-primary-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Receive money from</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-warning-300 bg-warning-300/20 border border-neutral-30 dark:border-neutral-500">In Progress</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-5.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-error-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Payment for</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-error-300 bg-error-300/20 border border-neutral-30 dark:border-neutral-500">Failed</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                    <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div class="flex items-center gap-4">
                          <div class="relative shrink-0">
                            <img src="assets/images/users/user-s-2.png" class="rounded-full" width="40" alt="" />
                            <div class="size-4 absolute -right-1 -bottom-1 border border-neutral-0 dark:border-neutral-500 rounded-full bg-error-300"></div>
                          </div>
                          <div>
                            <p class="m-text font-medium mb-1">Receive money from</p>
                            <span class="text-xs">Floyd Miles</span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <div>
                          <p class="m-text font-medium mb-1">29 Jun 2024</p>
                          <span class="text-xs">7:01 AM</span>
                        </div>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span>$2,000</span>
                      </td>
                      <td class="px-6" :class="dense? 'py-1.5': 'py-3'">
                        <span class="py-2 px-5 rounded-3xl text-xs text-error-300 bg-error-300/20 border border-neutral-30 dark:border-neutral-500">Failed</span>
                      </td>
                      <td>
                        <div x-data="dropdown" class="relative flex justify-center" @click.away="close()">
  <i class="las la-ellipsis-v cursor-pointer text-2xl" @click="toggle()"></i>
  <ul class="horiz-option" :class="isOpen?'show':'hide'">
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-print text-xl"></i> Print </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-share-alt-square text-xl"></i> Share </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-download text-xl"></i> Download </span>
    </li>
    <li>
      <span class="flex items-center hover:text-primary-300 gap-2 cursor-pointer rounded px-3 py-1.5 text-sm leading-normal duration-300 hover:bg-primary-50"> <i class="las la-trash text-xl"></i> Delete </span>
    </li>
  </ul>
</div>

                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="mt-6 flex items-center gap-5 justify-center flex-col md:flex-row md:justify-between whitespace-nowrap">
                  <label for="switch" class="switch flex">
                    <input x-model="dense" id="switch" type="checkbox" />
                    <span class="inner primary"></span>
                    Dense
                  </label>
                  <div class="flex flex-col sm:flex-row justify-center sm:justify-between gap-5">
                    <div class="flex items-center gap-4">
                      <p>Rows per page :</p>
                      <select name="rows" id="rows" class="bg-transparent dark:bg-neutral-904">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div>
                    <div class="f-center gap-4">
                      <p>1-10 of 100</p>
                      <button><i class="las la-angle-left text-xl rtl:rotate-180"></i></button> <button><i class="las la-angle-right text-xl rtl:rotate-180"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Top rated applications -->
          <div class="col-span-12 xl:col-span-5 xxl:col-span-4 relative after:bg-warning-300/60 after:w-full after:h-[75%] after:absolute after:bottom-0 after:left-0 after:rounded-xl">
            <img src="assets/images/invite-friend.png" class="pt-8 px-8 mb-10 relative z-[2]" alt="" />
            <div class="p-4 lg:p-6 xxl:p-10 relative z-[2]">
              <div class="flex justify-between items-center mb-4">
                <h4>Invite friends and earn</h4>
                <h2>$50</h2>
              </div>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been...</p>
              <form class="w-full rounded-full bg-neutral-20 dark:bg-neutral-903 border mt-7 xl:mt-10 border-neutral-40 dark:border-neutral-500 p-1 flex">
                <input type="text" class="px-4 py-2 w-full bg-transparent" placeholder="Enter Your Email..." />
                <button class="size-10 shrink-0 rounded-full bg-primary-300 text-neutral-0 f-center">
                  <i class="las la-arrow-right text-xl"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
@endsection

