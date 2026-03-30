@extends('layouts.app')

@section('title', 'Ajouter un département')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Ajouter un département</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('departments.index') }}">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-building shrink-0"></i>
                        <span>Départements</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2 text-primary-300" href="#">
                        <i class="las text-lg xl:text-xl xxl:text-2xl la-plus-circle shrink-0"></i>
                        <span>Ajouter</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Créer un nouveau département</h4>

        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div class="grid grid-cols-12 gap-4 lg:gap-6">

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

                        <div class="grid grid-cols-2 gap-4 xxl:gap-6 my-6">

                            <!-- Code du département -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="code" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Code *</label>
                                    <input type="text" id="code" name="code" value="{{ old('code') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Ex: RH, IT, FIN" required />
                                </div>
                            </div>

                            <!-- Nom du département -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="name" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Ex: Ressources Humaines" required />
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="description" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="w-full s-text bg-transparent py-2.5 xl:py-3.5 resize-none"
                                              placeholder="Description du département...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Manager -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="manager_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Manager</label>
                                    <select name="manager_id" id="manager_id" class="w-full s-text bg-transparent py-2.5 xl:py-3.5">
                                        <option value="">Sélectionner un manager</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }} ({{ $manager->position ?? 'Employé' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Statut -->
                           
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: true }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected ? 'Actif' : 'Inactif'"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                                    </div>
                                    <input type="hidden" name="is_active" :value="selected ? 1 : 0">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected = true; isOpen=false" class="cursor-pointer rounded-md px-4 py-2 duration-300 hover:bg-primary-50">Actif</div>
                                        <div @click="selected = false; isOpen=false" class="cursor-pointer rounded-md px-4 py-2 duration-300 hover:bg-primary-50">Inactif</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 xxl:gap-6 mt-6">
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Créer le département
                            </button>
                            <a href="{{ route('departments.index') }}" class="btn-primary-outlined px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
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
