@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Ajouter un client</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('clients.index') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-users shrink-0"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2 text-primary-300" href="#">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-user-plus shrink-0"></i>
                        <span>Ajouter</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Créer un nouveau client</h4>

        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="grid grid-cols-12 gap-4 lg:gap-6 lg:divide-x divide-neutral-30 divide-dashed dark:divide-neutral-500">

                <!-- Logo/Avatar du client -->
                <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
                    <div class="flex flex-col items-center">
                        <div class="size-40 rounded-full flex items-center justify-center cursor-pointer bg-neutral-20 dark:bg-neutral-903 border-4 border-neutral-0 dark:border-neutral-904 relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center avatar-img">
                                <i class="las la-building text-3xl text-neutral-100"></i>
                                <p class="text-sm">Logo du client</p>
                            </div>
                            <input type="file" name="logo" id="logo" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                        </div>
                        <p class="my-6 xxl:mb-8 text-center mx-auto max-w-[250px] text-xs text-gray-500">
                            Formats acceptés : *.jpeg, *.jpg, *.png, max 5 Mo
                        </p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
                    <div class="lg:px-4 xl:px-6">

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Informations principales -->
                        <div class="grid grid-cols-2 gap-4 xxl:gap-6 my-6">

                            <!-- Nom du client -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="name" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom du client *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Entrez le nom du client..." required />
                                </div>
                            </div>

                            <!-- Code client (auto-généré) -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="code" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Code client</label>
                                    <input type="text" id="code" name="code" value="{{ old('code') }}" readonly
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5 bg-gray-100 dark:bg-gray-800"
                                           placeholder="Auto-généré" />
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="email" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="client@exemple.com" />
                                </div>
                            </div>

                            <!-- Site web -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="website" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Site web</label>
                                    <input type="url" id="website" name="website" value="{{ old('website') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="https://www.exemple.com" />
                                </div>
                            </div>

                            <!-- Téléphone fixe -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="phone" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Téléphone fixe</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="33 123 45 67" />
                                </div>
                            </div>

                            <!-- Mobile -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="mobile" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Mobile</label>
                                    <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="77 123 45 67" />
                                </div>
                            </div>
                        </div>

                        <!-- Contact principal -->
                        <h4 class="text-lg font-semibold mt-6 mb-4">Contact principal</h4>
                        <div class="grid grid-cols-2 gap-4 xxl:gap-6">

                            <!-- Personne de contact -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="contact_person" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom du contact</label>
                                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Nom de la personne à contacter" />
                                </div>
                            </div>

                            <!-- Email contact -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="contact_email" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Email contact</label>
                                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="contact@exemple.com" />
                                </div>
                            </div>

                            <!-- Téléphone contact -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="contact_phone" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Téléphone contact</label>
                                    <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="77 123 45 67" />
                                </div>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <h4 class="text-lg font-semibold mt-6 mb-4">Adresse</h4>
                        <div class="grid grid-cols-2 gap-4 xxl:gap-6">

                            <!-- Adresse -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="address" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Adresse</label>
                                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Adresse complète" />
                                </div>
                            </div>

                            <!-- Ville -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="city" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Ville</label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Dakar" />
                                </div>
                            </div>

                            <!-- Pays -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{
                                    selectOpen: false,
                                    selectedItem: null,
                                    searchText: '',
                                    optionList: [],
                                    async fetchCountries() {
                                        try {
                                            const response = await fetch('https://restcountries.com/v3.1/all?fields=name,flags');
                                            const data = await response.json();
                                            this.optionList = data.map(country => ({
                                                title: country.name.common,
                                                flag: country.flags.svg
                                            }));
                                        } catch (error) {
                                            console.error('Error fetching countries:', error);
                                        }
                                    },
                                    filteredOptions() {
                                        if (this.searchText.trim() === '') {
                                            return this.optionList;
                                        } else {
                                            return this.optionList.filter(option => option.title.toLowerCase().includes(this.searchText.trim().toLowerCase()));
                                        }
                                    },
                                }" x-init="fetchCountries()" class="relative">
                                    <button @click="selectOpen=!selectOpen" class="py-3 px-3 sm:px-4 lg:px-6 rounded-xl flex items-center gap-3 w-full border bg-neutral-0 dark:bg-neutral-904 border-neutral-40 dark:border-neutral-500 relative">
                                        <img x-show="selectedItem && selectedItem?.title==searchText" class="size-6 rounded-full object-cover object-center" :src="selectedItem && selectedItem.flag" alt="flag" />
                                        <input type="text" placeholder="Sélectionner un pays" x-model="searchText" :value="selectedItem?.title" class="w-full bg-transparent capitalize" />
                                        <input type="hidden" name="country" :value="selectedItem?.title" />
                                        <span class="absolute inset-y-0 ltr:right-0 rtl:left-0 flex items-center ltr:pr-3 rtl:pl-3 rtl:xl:pl-6 ltr:xl:pr-6 pointer-events-none">
                                            <i class="las la-angle-down"></i>
                                        </span>
                                    </button>
                                    <ul x-show="selectOpen" @click.away="selectOpen=false" class="py-1 rounded-xl bg-neutral-0 dark:bg-neutral-904 absolute top-full left-0 right-0 w-full shadow-xl z-[3] max-h-[250px] overflow-y-auto">
                                        <template x-for="option in filteredOptions" :key="option.title">
                                            <li @click="selectedItem=option, selectOpen=false, searchText=option.title" :class="selectedItem==option ? 'bg-primary-300 text-neutral-0' : ''" class="py-2 ltr:pl-6 rtl:pr-6 hover:bg-primary-300 flex items-center gap-2 hover:text-neutral-0 duration-300 cursor-pointer capitalize">
                                                <img :src="option?.flag" alt="flag" class="size-6 shrink-0 rounded-full object-cover object-center" />
                                                <span x-text="option.title"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <!-- Code postal -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="postal_code" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Code postal</label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="BP 1234" />
                                </div>
                            </div>
                        </div>

                        <!-- Informations fiscales -->
                        <h4 class="text-lg font-semibold mt-6 mb-4">Informations fiscales</h4>
                        <div class="grid grid-cols-2 gap-4 xxl:gap-6">

                            <!-- NIF / Tax number -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="tax_number" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">NIF / N° d'impôt</label>
                                    <input type="text" id="tax_number" name="tax_number" value="{{ old('tax_number') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Numéro d'identification fiscale" />
                                </div>
                            </div>

                            <!-- Numéro de TVA -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="vat_number" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">N° TVA</label>
                                    <input type="text" id="vat_number" name="vat_number" value="{{ old('vat_number') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Numéro de TVA" />
                                </div>
                            </div>
                        </div>

                        <!-- Statut et notes -->
                        <h4 class="text-lg font-semibold mt-6 mb-4">Informations complémentaires</h4>
                        <div class="grid grid-cols-2 gap-4 xxl:gap-6">

                            <!-- Statut -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: 'active', items: ['active', 'inactive', 'lead'] }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'active' ? 'Actif' : (selected === 'inactive' ? 'Inactif' : 'Prospect')"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                                    </div>
                                    <input type="hidden" name="status" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='active', isOpen=false" :class="selected==='active' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">Actif</div>
                                        <div @click="selected='inactive', isOpen=false" :class="selected==='inactive' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">Inactif</div>
                                        <div @click="selected='lead', isOpen=false" :class="selected==='lead' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">Prospect</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="notes" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Notes</label>
                                    <textarea id="notes" name="notes" rows="3"
                                              class="w-full s-text bg-transparent py-2.5 xl:py-3.5 resize-none"
                                              placeholder="Informations supplémentaires sur le client...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 xxl:gap-6 mt-6 pb-6">
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Créer le client
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn-primary-outlined px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prévisualisation du logo
    document.getElementById('logo')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const avatarDiv = document.querySelector('.size-40');
                avatarDiv.style.backgroundImage = `url(${event.target.result})`;
                avatarDiv.style.backgroundSize = 'cover';
                avatarDiv.style.backgroundPosition = 'center';
                const avatarImg = document.querySelector('.avatar-img');
                if (avatarImg) avatarImg.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
