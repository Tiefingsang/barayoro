@extends('layouts.app')

@section('content')
    <div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6">
        <div x-data="chart" class="grid grid-cols-12 gap-4 xxl:gap-6">

          <!-- Statistiques -->
          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-primary-100">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <img src="assets/images/ada.png" width="48" alt="" />
                </span>
                <div class="analytics-stat-chart-1"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">714.52k</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Cardano ADA</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-primary-300 inline-flex py-2 px-4 items-center gap-1"> <i class="las la-arrow-up text-lg"></i> 75.8%</span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-secondary-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <img src="assets/images/usdt.png" width="48" alt="" />
                </span>
                <div class="analytics-stat-chart-2"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">$324.63</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Tether USDT</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-secondary-300 inline-flex py-2 px-4 items-center gap-1"> <i class="las la-arrow-up text-lg"></i> 44.8%</span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-warning-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <img src="assets/images/btc.png" width="48" alt="" />
                </span>
                <div class="analytics-stat-chart-3"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">$458.45</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Bitcoin BTC</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-warning-300 inline-flex py-2 px-4 items-center gap-1"> <i class="las la-arrow-up text-lg"></i> 75.8%</span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-error-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <img src="assets/images/eth.png" width="48" alt="" />
                </span>
                <div class="analytics-stat-chart-4"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">$657.12</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Ethereum ETC</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-error-300 inline-flex py-2 px-4 items-center gap-1"> <i class="las la-arrow-up text-lg"></i> 75.8%</span>
              </div>
            </div>
          </div>

          <!-- Graphique du marché -->
          <div class="col-span-12 xxl:col-span-8 space-y-4 xxl:space-y-6">
            <div class="white-box overflow-hidden">
              <div class="bb-dashed-n30 flex flex-col sm:flex-row justify-center sm:justify-between gap-4 items-center">
                <div>
                  <h4>Graphique du marché</h4>
                  <p class="m-text mt-2">(+43%) par rapport à l'année dernière</p>
                </div>
                <div class="flex items-center gap-3">
                  <span>Trier par : </span>
                  <select name="sort" class="nc-select n20">
                    <option value="day">15 derniers jours</option>
                    <option value="week">1 dernier mois</option>
                    <option value="year">6 derniers mois</option>
                  </select>
                </div>
              </div>
              <div x-ref="marketGraph"></div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="gap-4 lg:gap-6 grid grid-cols-2">
              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-wallet text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Solde total</p>
                    <h3 class="mb-4">45 717,25 €</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-arrow-up"></i>
                  75.8%
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-coins text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Investissement total</p>
                    <h3 class="mb-4">921,12 €</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-arrow-up"></i>
                  75.8%
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-exchange-alt text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Variation totale</p>
                    <h3 class="mb-4">3 645,12 €</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-arrow-up"></i>
                  75.8%
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-money-bill-alt text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Variation du jour</p>
                    <h3 class="mb-4">12,25 €</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-arrow-up"></i>
                  75.8%
                </span>
              </div>
            </div>
          </div>

          <!-- Mon portefeuille -->
          <div class="col-span-12 xxl:col-span-4 white-box overflow-hidden">
            <h4 class="bb-dashed-n30">Mon portefeuille</h4>
            <div x-ref="portfolio"></div>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              <!-- Liste des cryptomonnaies -->
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/btc.png" width="40" alt="Bitcoin" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Bitcoin</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">BTC</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>

              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/eth.png" width="40" alt="Ethereum" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Ethereum</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">ETH</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>

              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/ltc.png" width="40" alt="Litcoin" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Litcoin</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">LTC</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>

              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/ada.png" width="40" alt="Cardano" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Cardano</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">ADA</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>

              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/usdt.png" width="40" alt="Tether" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Tether</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">USDT</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Mes cryptomonnaies -->
          <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box">
            <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-4 bb-dashed-n30">
              <h4>Mes cryptomonnaies</h4>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" class="nc-select n20">
                  <option value="day">15 derniers jours</option>
                  <option value="week">1 dernier mois</option>
                  <option value="year">6 derniers mois</option>
                </select>
              </div>
            </div>
            <div x-data="{dense: false}" class="checkboxes-container overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <thead class="text-left">
                  <tr class="bg-neutral-20 dark:bg-neutral-903">
                    <th class="px-6 py-3 lg:py-5">Nom</th>
                    <th class="px-6 py-3 lg:py-5">Prix</th>
                    <th class="px-6 py-3 lg:py-5">Variation 24h</th>
                    <th class="px-6 py-3 lg:py-5">Solde total</th>
                    <th class="px-6 py-3 lg:py-5">Quantité totale</th>
                    <th class="px-6 py-3 lg:py-5">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                    <td class="px-6 py-3 lg:py-5">
                      <div class="flex items-center gap-2">
                        <img src="assets/images/btc.png" width="24" class="rounded-full" alt="Bitcoin" />
                        <span class="m-text font-medium">Bitcoin</span>
                      </div>
                    </td>
                    <td class="px-6 py-3 lg:py-5">854,08 €</td>
                    <td class="px-6 py-3 lg:py-5 flex items-center gap-2">
                      <i class="las la-level-down-alt text-error-300 text-xl"></i>
                      79.54
                    </td>
                    <td class="px-6 py-3 lg:py-5">91,83 €</td>
                    <td class="px-6 py-3 lg:py-5">11.93</td>
                    <td class="px-6 py-3 lg:py-5">
                      <a href="#" class="py-2 px-5 rounded-3xl s-text font-semibold text-primary-300 border border-primary-300">Échanger</a>
                    </td>
                  </tr>
                  <!-- Ajoutez les autres lignes avec les mêmes traductions -->
                </tbody>
              </table>
              <div class="mt-6 flex items-center gap-5 justify-center flex-col md:flex-row md:justify-end whitespace-nowrap">
                <div class="flex flex-col sm:flex-row justify-center sm:justify-between gap-5">
                  <div class="flex gap-4 items-center">
                    <p>Lignes par page :</p>
                    <select name="rows" id="rows" class="bg-transparent dark:bg-neutral-904">
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                  </div>
                  <div class="f-center gap-4">
                    <p>1-10 sur 100</p>
                    <button><i class="las la-angle-left text-xl rtl:rotate-180"></i></button>
                    <button><i class="las la-angle-right text-xl rtl:rotate-180"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Transfert rapide -->
          <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <div class="bb-dashed-n30 mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
              <div x-data="{type:'buy'}" class="flex rounded-lg border border-neutral-30 dark:border-neutral-500 bg-primary-5">
                <button class="px-4 py-2 rounded-lg" :class="type === 'buy' ? 'bg-primary-300 text-neutral-0' : 'bg-transparent'" @click="type = 'buy'">Acheter</button>
                <button class="px-4 py-2 rounded-lg" :class="type === 'sell' ? 'bg-primary-300 text-neutral-0' : 'bg-transparent'" @click="type = 'sell'">Vendre</button>
              </div>
            </div>

            <div class="bb-dashed-n30 mb-4 flex flex-col pb-4 lg:mb-6 lg:pb-6">
              <div class="primary5-box spend order-1 border border-n30 bg-primary/5 dark:border-n500 dark:bg-bg3 xl:p-3 xxl:p-4">
                <div class="bb-dashed-n30 mb-4 flex items-center justify-between gap-3 pb-4 text-sm">
                  <p>Dépenser</p>
                  <p>Solde : 10 000 USD</p>
                </div>
                <div class="flex items-center justify-between gap-4 text-xl font-medium">
                  <input type="number" class="w-20 border-none bg-transparent p-0" placeholder="0.00" />
                  <div class="shrink-0 flex gap-2 items-center">
                    <img src="assets/images/btc.png" width="28" alt="BTC" />
                    <p class="font-medium xl-text">BTC</p>
                  </div>
                </div>
              </div>
              <button class="relative z-[2] order-2 -my-4 text-xl text-primary-300 self-center rounded-lg border border-n30 bg-neutral-0 size-12 f-center text-primary dark:border-neutral-500 dark:bg-neutral-904">
                <i class="las la-exchange-alt rotate-90"></i>
              </button>
              <div class="primary5-box receive order-3 border border-n30 bg-primary/5 dark:border-n500 dark:bg-bg3 xl:p-3 xxl:p-4">
                <div class="bb-dashed-n30 mb-4 flex items-center justify-between gap-3 pb-4 text-sm">
                  <p>Recevoir</p>
                  <p>Solde : 10 000 USD</p>
                </div>
                <div class="flex items-center justify-between gap-4 text-xl font-medium">
                  <input type="number" class="w-20 border-none bg-transparent p-0" placeholder="0.00" />
                  <div class="shrink-0 flex gap-2 items-center">
                    <img src="assets/images/ada.png" width="28" alt="ADA" />
                    <p class="font-medium xl-text">ADA</p>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="mb-6 text-lg font-medium">Méthode de paiement</p>
              <div class="mb-6 flex items-center gap-4 rounded-lg border border-dashed border-primary bg-primary-50 dark:border-neutral-500 p-3 lg:mb-8">
                <img src="assets/images/card.png" width="60" height="40" alt="carte" />
                <div>
                  <p class="mb-1 font-medium">John Snow - Metal</p>
                  <span class="text-xs">**4291 - Exp: 12/26</span>
                </div>
              </div>
              <a href="#" class="btn-primary flex w-full justify-center"> Acheter </a>
            </div>
          </div>

          <!-- Meilleurs performeurs -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Meilleurs performeurs</h4>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              <!-- Liste des meilleurs performeurs -->
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <img src="assets/images/btc.png" width="40" alt="Bitcoin" />
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Bitcoin</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">BTC</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">BTC 0.00584875</p>
                  <p class="text-primary-300 text-sm">19 405,12 €</p>
                </div>
              </div>
              <!-- Ajoutez les autres lignes -->
            </div>
          </div>

          <!-- Activité récente -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Activité récente</h4>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                  <div class="size-10 rounded-full border border-neutral-30 bg-neutral-20 f-center text-xl text-primary-300">
                    <i class="las la-arrow-circle-down"></i>
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">Achat de Bitcoin</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">Carte Visa Débit ***6</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 m-text">+0.04025745 BTC</p>
                  <p class="text-primary-300 text-sm">+878.52 USD</p>
                </div>
              </div>
              <!-- Ajoutez les autres activités -->
            </div>
          </div>

          <!-- Transfert rapide -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Transfert rapide</h4>
            <div class="flex justify-between items-center mb-6">
              <p class="l-text font-medium">Récent</p>
              <a href="#" class="h6 text-primary-300 font-semibold">Voir tout <i class="las la-arrow-right text-lg"></i></a>
            </div>

            <!-- Sélection de la banque -->
            <div x-data="{isOpen:false,selected:'Banque X', items:['Banque X','Banque Y','Banque Z']}" class="relative mb-6">
              <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-30 px-6 py-3 dark:border-neutral-500">
                <span x-text="selected"></span>
                <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom de la banque</span>
              </div>
              <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                <template x-for="item in items">
                  <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300 text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item"></div>
                </template>
              </div>
            </div>

            <div class="flex gap-6">
              <div x-data="{isOpen:false,selected:'€', items:['€','$','£','¥']}" class="relative w-28">
                <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-30 px-5 py-3.5 dark:border-neutral-500">
                  <span x-text="selected"></span>
                  <i class="las la-angle-down duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                  <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Devise</span>
                </div>
                <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <template x-for="item in items">
                    <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300 text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item"></div>
                  </template>
                </div>
              </div>
              <div class="form-input">
                <input type="number" class="!py-3" id="amount" placeholder="Saisir le montant" />
                <label for="amount">Montant</label>
              </div>
            </div>

            <div class="flex justify-between items-center my-5 xl:my-8">
              <p class="font-medium l-text">Votre solde</p>
              <p class="l-text">99,24 €</p>
            </div>
            <button class="w-full btn-primary">Transférer maintenant</button>
          </div>
        </div>
    </div>
@endsection
