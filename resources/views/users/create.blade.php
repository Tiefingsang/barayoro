@extends('layouts.app')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Ajouter un utilisateur</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('users.index') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-users shrink-0"></i>
                        <span>Utilisateurs</span>
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
        <h4 class="bb-dashed-n30">Créer un nouvel utilisateur</h4>

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-12 gap-4 lg:gap-6 lg:divide-x divide-neutral-30 divide-dashed dark:divide-neutral-500">

                <!-- Avatar -->
                <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
                    <div class="flex flex-col items-center">
                        <div class="size-40 rounded-full flex items-center justify-center cursor-pointer bg-neutral-20 dark:bg-neutral-903 border-4 border-neutral-0 dark:border-neutral-904 relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center avatar-img">
                                <i class="las la-camera text-3xl text-neutral-100"></i>
                                <p class="text-sm">Photo de profil</p>
                            </div>
                            <input type="file" name="avatar" id="avatar" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                        </div>
                        <p class="my-6 xxl:mb-8 text-center mx-auto max-w-[250px] text-xs text-gray-500">
                            Formats acceptés : *.jpeg, *.jpg, *.png, max 5 Mo
                        </p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
                    <div class="lg:px-4 xl:px-6">

                        <!-- Message d'information sur le mot de passe par défaut -->
                        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm">Le mot de passe par défaut est <strong>12345678</strong>. L'utilisateur pourra le modifier après sa première connexion.</span>
                        </div>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 xxl:gap-6 my-6">

                            <!-- Nom complet -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="name" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom complet *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Entrez le nom complet..." required />
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="email" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Entrez l'email..." required />
                                </div>
                            </div>

                            <!-- Fonction/Poste -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="position" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Fonction</label>
                                    <input type="text" id="position" name="position" value="{{ old('position') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Ex: Développeur, Manager..." />
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="phone" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Téléphone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Entrez le numéro de téléphone..." />
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

                            <!-- Rôle -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: 'employee', items: ['admin', 'manager', 'employee'] }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'admin' ? 'Administrateur' : (selected === 'manager' ? 'Gestionnaire' : 'Employé')"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Rôle *</span>
                                    </div>
                                    <input type="hidden" name="role" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='admin', isOpen=false" :class="selected==='admin' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">
                                            <span class="font-medium">Administrateur</span>
                                            <p class="text-xs text-gray-500">Tous les droits</p>
                                        </div>
                                        <div @click="selected='manager', isOpen=false" :class="selected==='manager' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">
                                            <span class="font-medium">Gestionnaire</span>
                                            <p class="text-xs text-gray-500">Peut gérer les utilisateurs, projets, tâches</p>
                                        </div>
                                        <div @click="selected='employee', isOpen=false" :class="selected==='employee' ? 'bg-primary-300 text-neutral-0' : 'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300">
                                            <span class="font-medium">Employé</span>
                                            <p class="text-xs text-gray-500">Accès limité à ses tâches</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: 'active', items: ['active', 'inactive'] }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'active' ? 'Actif' : 'Inactif'"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                                    </div>
                                    <input type="hidden" name="status" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <template x-for="item in items">
                                            <div @click="selected=item, isOpen=false" :class="selected===item?'bg-primary-300 text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'" class="cursor-pointer rounded-md px-4 py-2 duration-300" x-text="item === 'active' ? 'Actif' : 'Inactif'"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe par défaut (champ caché) -->
                        <input type="hidden" name="password" value="12345678">
                        <input type="hidden" name="password_confirmation" value="12345678">

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 xxl:gap-6 mt-6">
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Créer l'utilisateur
                            </button>
                            <a href="{{ route('users.index') }}" class="btn-primary-outlined px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
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
    // Prévisualisation de l'avatar
    document.getElementById('avatar')?.addEventListener('change', function(e) {
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
