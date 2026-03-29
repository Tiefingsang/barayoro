@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Modifier l'utilisateur</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('users.index') }}">
                  <i class="las la-users shrink-0"></i>
                  <span>Utilisateurs</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-edit shrink-0"></i>
                  <span>Modifier</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div x-data="{
                isActive: {{ $user->is_active ? 'true' : 'false' }},
                userRole: '{{ $userRole }}',
                employmentType: '{{ $user->employment_type ?? 'full_time' }}'
            }" class="white-box">
              <h4 class="bb-dashed-n30">Informations de l'utilisateur</h4>
              <div class="grid grid-cols-12 gap-4 xxl:gap-6 lg:divide-x lg:divide-dashed divide-neutral-30 dark:divide-neutral-500">

                <!-- Colonne Avatar -->
                <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
                  <div class="flex flex-col items-center relative pt-4 lg:pt-6">
                    <span x-show="isActive" class="py-1 px-2 font-medium rounded-md bg-green-100 text-green-600 text-xs absolute right-5 top-5">Actif</span>
                    <span x-show="!isActive" class="py-1 px-2 font-medium rounded-md bg-red-100 text-red-600 text-xs absolute right-5 top-5">Inactif</span>

                    <div class="mb-6">
                      <div class="size-40 group rounded-full flex items-center relative justify-center cursor-pointer bg-neutral-20 dark:bg-neutral-903 border-4 border-neutral-30 dark:border-neutral-500 overflow-hidden">
                        <img id="avatar-preview" src="{{ $user->avatar_url }}" width="200" alt="Avatar" class="w-full h-full object-cover" />
                        <div class="flex flex-col duration-300 opacity-0 group-hover:opacity-100 items-center justify-center text-neutral-20 text-center absolute z-[2] bg-opacity-60 bg-neutral-900 rounded-full w-full h-full">
                          <i class="las la-camera text-3xl text-white"></i>
                          <p class="text-white text-sm">Changer photo</p>
                        </div>
                        <input type="file" name="avatar" id="avatar" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewAvatar(this)">
                      </div>
                    </div>
                    <p class="mb-6 text-center mx-auto max-w-[250px] text-xs text-gray-500">Formats acceptés : *.jpeg, *.jpg, *.png, max 5 Mo</p>
                  </div>
                </div>

                <!-- Colonne Formulaire -->
                <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
                  <div class="px-4 lg:px-6 xxl:px-8">

                    <!-- Message si compte en attente -->
                    @if(!$user->email_verified_at)
                    <div class="mb-4">
                      <div class="py-3 px-4 rounded-xl border border-yellow-300 bg-yellow-50 flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                          <i class="las la-info-circle text-2xl text-yellow-600"></i>
                          <span class="text-yellow-700">Le compte est en attente de vérification email</span>
                        </div>
                      </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 xxl:gap-6 my-6">

                      <!-- Nom complet -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="name" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom complet *</label>
                          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Entrez le nom complet..." required />
                        </div>
                        @error('name') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                      </div>

                      <!-- Email -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="email" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Email *</label>
                          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Entrez l'email..." required />
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                      </div>

                      <!-- Téléphone -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="phone" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Téléphone</label>
                          <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Entrez le numéro..." />
                        </div>
                      </div>

                      <!-- Fonction -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="position" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Fonction</label>
                          <input type="text" id="position" name="position" value="{{ old('position', $user->position) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Ex: Développeur, Manager..." />
                        </div>
                      </div>

                      <!-- Matricule employé -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="employee_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Matricule employé</label>
                          <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Ex: EMP-001" />
                        </div>
                      </div>

                      <!-- Date d'embauche -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="hire_date" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Date d'embauche</label>
                          <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', $user->hire_date ? $user->hire_date->format('Y-m-d') : '') }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5" />
                        </div>
                      </div>

                      <!-- Type d'emploi -->
                      <div class="col-span-2 md:col-span-1">
                        <div x-data="{ isOpen: false, selected: employmentType }" class="relative">
                          <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                            <span x-text="selected === 'full_time' ? 'Temps plein' : (selected === 'part_time' ? 'Temps partiel' : (selected === 'contract' ? 'Contractuel' : 'Stagiaire'))"></span>
                            <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                            <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Type d'emploi</span>
                          </div>
                          <input type="hidden" name="employment_type" x-model="selected">
                          <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 w-full bg-neutral-0 rounded-lg shadow-xl dark:bg-neutral-904">
                            <div @click="selected='full_time', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Temps plein</div>
                            <div @click="selected='part_time', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Temps partiel</div>
                            <div @click="selected='contract', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Contractuel</div>
                            <div @click="selected='intern', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Stagiaire</div>
                          </div>
                        </div>
                      </div>

                      <!-- Taux horaire -->
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="hourly_rate" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Taux horaire (cfa)</label>
                          <input type="number" step="0.01" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $user->hourly_rate) }}"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="0.00" />
                        </div>
                      </div>

                      <!-- Rôle -->
                      <div class="col-span-2 md:col-span-1">
                        <div x-data="{ isOpen: false, selected: userRole }" class="relative">
                          <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                            <span x-text="selected === 'admin' ? 'Administrateur' : (selected === 'manager' ? 'Gestionnaire' : 'Employé')"></span>
                            <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                            <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Rôle *</span>
                          </div>
                          <input type="hidden" name="role" x-model="selected">
                          <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 w-full bg-neutral-0 rounded-lg shadow-xl dark:bg-neutral-904">
                            <div @click="selected='admin', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Administrateur</div>
                            <div @click="selected='manager', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Gestionnaire</div>
                            <div @click="selected='employee', isOpen=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Employé</div>
                          </div>
                        </div>
                        @error('role') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                      </div>

                      <!-- Statut -->
                      <div class="col-span-2 md:col-span-1">
                        <div x-data="{ isOpen: false, selected: isActive ? 'active' : 'inactive' }" class="relative">
                          <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                            <span x-text="selected === 'active' ? 'Actif' : 'Inactif'"></span>
                            <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                            <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                          </div>
                          <input type="hidden" name="status" x-model="selected">
                          <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 w-full bg-neutral-0 rounded-lg shadow-xl dark:bg-neutral-904">
                            <div @click="selected='active', isOpen=false, isActive=true" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Actif</div>
                            <div @click="selected='inactive', isOpen=false, isActive=false" class="cursor-pointer px-4 py-2 hover:bg-gray-100">Inactif</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Options de mot de passe -->
                    <div class="border-t border-gray-200 my-6"></div>
                    <h4 class="text-lg font-semibold mb-4">Modifier le mot de passe</h4>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="password" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nouveau mot de passe</label>
                          <input type="password" id="password" name="password"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Laisser vide pour ne pas modifier" />
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                      </div>
                      <div class="col-span-2 md:col-span-1">
                        <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                          <label for="password_confirmation" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Confirmer le mot de passe</label>
                          <input type="password" id="password_confirmation" name="password_confirmation"
                                 class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                 placeholder="Confirmer le nouveau mot de passe" />
                        </div>
                      </div>
                    </div>

                    <!-- Options supplémentaires -->
                    <div class="flex flex-col gap-5 xxl:gap-8 my-6">
                      <div class="flex justify-between items-center">
                        <div>
                          <p class="font-medium mb-2">Email vérifié</p>
                          <p class="text-sm text-gray-500">Désactiver pour envoyer un email de vérification</p>
                        </div>
                        <label class="switch flex justify-center">
                          <input type="checkbox" name="email_verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }} />
                          <span class="inner primary"></span>
                        </label>
                      </div>

                      <div class="flex justify-between items-center">
                        <div>
                          <p class="font-medium mb-2">Double authentification (2FA)</p>
                          <p class="text-sm text-gray-500">Renforce la sécurité du compte</p>
                        </div>
                        <label class="switch flex justify-center">
                          <input type="checkbox" name="two_factor_enabled" value="1" {{ $user->two_factor_enabled ? 'checked' : '' }} />
                          <span class="inner primary"></span>
                        </label>
                      </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 xxl:gap-6 mt-6 pb-6">
                      <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        <i class="las la-save mr-2"></i> Enregistrer les modifications
                      </button>
                      <a href="{{ route('users.index') }}" class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                        <i class="las la-times mr-2"></i> Annuler
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
      </div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
