@extends('layouts.app')

@section('title', 'Modifier la facture')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">
        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Modifier la facture</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}"> <i class="las la-home shrink-0"></i> <span>Accueil</span></a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('invoices.index') }}"> <i class="las la-file-invoice shrink-0"></i> <span>Factures</span></a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#"> <i class="las la-edit shrink-0"></i> <span>Modifier</span></a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Formulaire de modification -->
        <form method="POST" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <div class="white-box">
              <h4 class="bb-dashed-n30">Modifier la facture #{{ $invoice->invoice_number }}</h4>
              <div
                x-data="{
                    invoiceProducts: {{ json_encode($invoice->items) }},
                    nextId: {{ count($invoice->items) + 1 }},
                    shipping: {{ $invoice->shipping ?? 0 }},
                    discount: {{ $invoice->discount ?? 0 }},
                    selectedClient: {{ json_encode($invoice->client) }},
                    clients: {{ json_encode($clients) }},
                    products: {{ json_encode($products) }},
                    getTotal(){
                        return this.invoiceProducts.reduce((total,product)=>total+product.unit_price*product.quantity,0);
                    },
                    addItem(){
                        this.invoiceProducts.push({
                            id: this.nextId++,
                            product_id: null,
                            description: '',
                            quantity: 1,
                            unit_price: 0,
                            discount: 0,
                            tax_rate: 0,
                            total: 0
                        });
                    },
                    removeItem(id){
                        this.invoiceProducts = this.invoiceProducts.filter((item)=>item.id!=id)
                    },
                    selectProduct(item, product){
                        item.product_id = product.id;
                        item.description = product.name;
                        item.unit_price = product.selling_price;
                        item.tax_rate = product.tax_rate;
                        item.total = item.quantity * item.unit_price;
                    },
                    updateItemTotal(item){
                        item.total = item.quantity * item.unit_price;
                    }
                }"
                class="white-box xxl:p-[60px]"
              >
                <!-- Expéditeur et Destinataire -->
                <div class="mb-7 xxl:mb-10 grid sm:grid-cols-2 max-sm:gap-5 sm:divide-x divide-dashed divide-neutral-30 dark:divide-neutral-500">

                  <!-- Expéditeur (Utilisateur connecté) -->
                  <div class="ltr:sm:pr-5 rtl:sm:pl-5 xxl:ltr:pr-10 xxl:rtl:pl-10">
                    <div class="flex justify-between items-center mb-4">
                      <p class="xl-text font-medium text-neutral-100">Expéditeur :</p>
                    </div>
                    <p class="m-text font-medium mb-4">{{ auth()->user()->name }}</p>
                    <span class="m-text">{{ auth()->user()->company->name ?? 'Barayoro' }}</span>
                    <p class="text-sm mt-2">{{ auth()->user()->email }}</p>
                    <p class="text-sm">{{ auth()->user()->phone ?? '' }}</p>
                  </div>

                  <!-- Destinataire (Sélection client) -->
                  <div class="ltr:sm:pl-5 rtl:sm:pr-5 xxl:ltr:pl-10 xxl:rtl:pr-10">
                    <div x-data="{ open: false }">
                      <div @click="open = !open" class="cursor-pointer p-3 border border-gray-300 rounded-lg">
                        <p x-show="!selectedClient" class="text-gray-500">Sélectionner un client</p>
                        <div x-show="selectedClient">
                          <p class="font-medium" x-text="selectedClient.name"></p>
                          <p class="text-sm" x-text="selectedClient.address"></p>
                          <p class="text-sm" x-text="selectedClient.email"></p>
                          <p class="text-sm" x-text="selectedClient.phone"></p>
                        </div>
                      </div>
                      <input type="hidden" name="client_id" :value="selectedClient ? selectedClient.id : ''">
                      <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full max-w-md bg-white border rounded-lg shadow-lg">
                        <div class="p-2">
                          <input type="text" placeholder="Rechercher..." class="w-full p-2 border rounded-lg" x-model="clientSearch">
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                          <template x-for="client in clients.filter(c => c.name.toLowerCase().includes((clientSearch || '').toLowerCase()))" :key="client.id">
                            <div @click="selectedClient = client; open = false" class="p-3 hover:bg-gray-100 cursor-pointer">
                              <p class="font-medium" x-text="client.name"></p>
                              <p class="text-xs text-gray-500" x-text="client.email"></p>
                            </div>
                          </template>
                        </div>
                      </div>
                    </div>
                    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>
                </div>

                <div class="n20-box xl:p-6 grid grid-cols-12 gap-4 xxl:gap-6 mb-7 xxl:mb-10">
                  <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <div class="relative flex items-center gap-4 rounded-full border border-neutral-40 px-4 dark:border-neutral-500 lg:px-6">
                      <label class="absolute -top-2 bg-neutral-20 px-2 text-xs dark:bg-neutral-903">N° Facture</label>
                      <input type="text" value="{{ $invoice->invoice_number }}" class="w-full bg-transparent py-2.5 lg:py-3.5" disabled />
                    </div>
                  </div>
                  <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <div x-data="{isOpen:false,selected:'{{ $invoice->status }}', items:['draft','sent','pending','paid']}" class="relative">
                      <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-full border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                        <span x-text="selected === 'draft' ? 'Brouillon' : (selected === 'sent' ? 'Envoyée' : (selected === 'pending' ? 'En attente' : 'Payée'))"></span>
                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                        <span class="absolute -top-2 left-5 bg-neutral-20 px-2 text-xs dark:bg-neutral-903">Statut</span>
                      </div>
                      <input type="hidden" name="status" x-model="selected">
                      <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                        <template x-for="item in items">
                          <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300  text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item === 'draft' ? 'Brouillon' : (item === 'sent' ? 'Envoyée' : (item === 'pending' ? 'En attente' : 'Payée'))"></div>
                        </template>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <div class="relative flex items-center gap-4 rounded-full border border-neutral-40 px-4 dark:border-neutral-500 lg:px-6">
                      <label class="absolute -top-2 bg-neutral-20 px-2 text-xs dark:bg-neutral-903">Date d'émission</label>
                      <input type="date" name="issue_date" value="{{ $invoice->issue_date->format('Y-m-d') }}" class="w-full bg-transparent py-2.5 lg:py-3.5" required />
                    </div>
                  </div>
                  <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <div class="relative flex items-center gap-4 rounded-full border border-neutral-40 px-4 dark:border-neutral-500 lg:px-6">
                      <label class="absolute -top-2 bg-neutral-20 px-2 text-xs dark:bg-neutral-903">Date d'échéance</label>
                      <input type="date" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" class="w-full bg-transparent py-2.5 lg:py-3.5" required />
                    </div>
                  </div>
                </div>

                <!-- Liste des produits -->
                <div class="bb-dashed-n30 xxl:mb-10 xxl:pb-10">
                  <p class="xl-text font-medium text-neutral-100 mb-6">Produits / Services</p>
                  <template x-for="(item, index) in invoiceProducts" :key="item.id">
                    <div>
                      <div class="grid grid-cols-12 gap-4 xxl:gap-6 mb-6">
                        <!-- Sélection du produit -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-4 xxl:col-span-3 4xl:col-span-2">
                          <div x-data="{ open: false, productSearch: '' }" class="relative">
                            <div @click="open = !open" class="cursor-pointer relative flex items-center gap-4 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6 py-2.5">
                              <span x-text="item.description ? item.description : 'Sélectionner un produit'"></span>
                              <i class="las la-angle-down text-lg absolute right-4"></i>
                            </div>
                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow-lg">
                              <div class="p-2">
                                <input type="text" placeholder="Rechercher..." class="w-full p-2 border rounded-lg" x-model="productSearch">
                              </div>
                              <div class="max-h-60 overflow-y-auto">
                                <template x-for="product in products.filter(p => p.name.toLowerCase().includes(productSearch.toLowerCase()))" :key="product.id">
                                  <div @click="selectProduct(item, product); open = false" class="p-3 hover:bg-gray-100 cursor-pointer">
                                    <p class="font-medium" x-text="product.name"></p>
                                    <p class="text-xs text-gray-500" x-text="product.selling_price + ' FCFA'"></p>
                                  </div>
                                </template>
                              </div>
                            </div>
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                            <input type="hidden" :name="'items[' + index + '][description]'" :value="item.description">
                          </div>
                        </div>
                        <!-- Quantité -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2">
                          <div class="relative flex items-center gap-4 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                            <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Quantité</label>
                            <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" @input="updateItemTotal(item)" class="w-full bg-transparent py-3 lg:py-3.5" required />
                          </div>
                        </div>
                        <!-- Prix unitaire -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2">
                          <div class="relative flex items-center gap-2 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                            <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Prix unit.</label>
                            <i class="las la-dollar-sign text-lg"></i>
                            <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" @input="updateItemTotal(item)" class="w-full bg-transparent py-3 lg:py-3.5" required />
                          </div>
                        </div>
                        <!-- Remise -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2">
                          <div class="relative flex items-center gap-2 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                            <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Remise (%)</label>
                            <i class="las la-percent text-lg"></i>
                            <input type="number" :name="'items[' + index + '][discount]'" x-model="item.discount" class="w-full bg-transparent py-3 lg:py-3.5" />
                          </div>
                        </div>
                        <!-- Taxe -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2">
                          <div class="relative flex items-center gap-2 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                            <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Taxe (%)</label>
                            <i class="las la-percent text-lg"></i>
                            <input type="number" :name="'items[' + index + '][tax_rate]'" x-model="item.tax_rate" class="w-full bg-transparent py-3 lg:py-3.5" />
                          </div>
                        </div>
                        <!-- Total -->
                        <div class="col-span-12 sm:col-span-6 lg:col-span-1">
                          <div class="relative flex items-center gap-2 rounded-full border border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                            <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Total</label>
                            <i class="las la-dollar-sign text-lg"></i>
                            <input type="text" :value="item.total.toFixed(2)" class="w-full bg-transparent py-3 lg:py-3.5" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="flex justify-end mb-6">
                        <button type="button" @click="removeItem(item.id)" class="flex items-center gap-2 text-red-600 font-semibold">
                          <i class="las la-trash text-xl"></i> Supprimer
                        </button>
                      </div>
                    </div>
                  </template>
                  <button type="button" @click="addItem()" class="flex items-center gap-2 font-semibold text-primary-300">
                    <i class="las la-plus-circle"></i> Ajouter un produit
                  </button>
                </div>

                <div class="flex justify-between flex-wrap gap-4 items-center mb-6">
                  <div></div>
                  <div class="flex items-center flex-wrap gap-4 xxl:gap-6">
                    <div class="relative flex items-center gap-4 rounded-full border w-[160px] border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                      <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Frais de port (FCFA)</label>
                      <input type="number" name="shipping" x-model="shipping" placeholder="0" class="w-full bg-transparent py-3 lg:py-3.5" />
                    </div>
                    <div class="relative flex items-center gap-4 rounded-full border w-[160px] border-neutral-30 px-4 dark:border-neutral-500 lg:px-6">
                      <label class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Remise (FCFA)</label>
                      <input type="number" name="discount" x-model="discount" placeholder="0" class="w-full bg-transparent py-3 lg:py-3.5" />
                    </div>
                  </div>
                </div>

                <div class="flex justify-end bb-dashed-n30 xxl:mb-10 xxl:pb-10">
                  <div class="w-[300px] flex flex-col divide-y divide-neutral-30 dark:divide-neutral-500">
                    <div class="flex justify-between items-center">
                      <p class="py-3 xl:py-4 px-5">Sous-total</p>
                      <p x-text="getTotal().toFixed(2)"></p>
                    </div>
                    <div class="flex justify-between items-center">
                      <p class="py-3 xl:py-4 px-5">Frais de port</p>
                      <p x-text="shipping"></p>
                    </div>
                    <div class="flex justify-between items-center">
                      <p class="py-3 xl:py-4 px-5">Remise</p>
                      <p x-text="discount"></p>
                    </div>
                    <div class="flex justify-between items-center">
                      <p class="py-3 xl:py-4 px-5 font-semibold">Total</p>
                      <p x-text="(getTotal() + Number(shipping) - Number(discount)).toFixed(2)"></p>
                    </div>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                  <div class="relative rounded-xl border border-neutral-30 px-4 py-3 dark:border-neutral-500">
                    <label class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Notes</label>
                    <textarea name="notes" rows="3" class="w-full bg-transparent py-2 resize-none" placeholder="Notes supplémentaires...">{{ $invoice->notes }}</textarea>
                  </div>
                  <div class="relative rounded-xl border border-neutral-30 px-4 py-3 dark:border-neutral-500">
                    <label class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Conditions</label>
                    <textarea name="terms" rows="3" class="w-full bg-transparent py-2 resize-none" placeholder="Conditions de paiement...">{{ $invoice->terms }}</textarea>
                  </div>
                </div>

                <div class="flex gap-4 flex-wrap xxl:gap-6">
                  <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                  <a href="{{ route('invoices.show', $invoice) }}" class="btn-primary-outlined">Annuler</a>
                </div>
              </div>
            </div>
        </form>
      </div>
@endsection
