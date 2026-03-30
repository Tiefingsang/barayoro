@extends('layouts.app')

@section('title', 'Modifier le projet')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Modifier le projet</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('projects.index') }}">
                        <i class="las la-project-diagram shrink-0"></i>
                        <span>Projets</span>
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

    <!-- Formulaire de modification -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Modifier le projet #{{ $project->code }}</h4>

        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4 lg:gap-6">

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

                            <!-- Nom du projet -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="name" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Nom du projet *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Ex: Refonte du site web" required />
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="description" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="w-full s-text bg-transparent py-2.5 xl:py-3.5 resize-none"
                                              placeholder="Description du projet...">{{ old('description', $project->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Client -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="client_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Client</label>
                                    <select name="client_id" id="client_id" class="w-full s-text bg-transparent py-2.5 xl:py-3.5">
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Département -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="department_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Département</label>
                                    <select name="department_id" id="department_id" class="w-full s-text bg-transparent py-2.5 xl:py-3.5">
                                        <option value="">Sélectionner un département</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $project->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Chef de projet -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="project_manager_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Chef de projet</label>
                                    <select name="project_manager_id" id="project_manager_id" class="w-full s-text bg-transparent py-2.5 xl:py-3.5">
                                        <option value="">Sélectionner un manager</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('project_manager_id', $project->project_manager_id) == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }} ({{ $manager->position ?? 'Employé' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: '{{ old('status', $project->status) }}' }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'draft' ? 'Brouillon' : (selected === 'planned' ? 'Planifié' : (selected === 'in_progress' ? 'En cours' : (selected === 'on_hold' ? 'En attente' : (selected === 'completed' ? 'Terminé' : 'Annulé'))))"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                                    </div>
                                    <input type="hidden" name="status" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='draft', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Brouillon</div>
                                        <div @click="selected='planned', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Planifié</div>
                                        <div @click="selected='in_progress', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">En cours</div>
                                        <div @click="selected='on_hold', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">En attente</div>
                                        <div @click="selected='completed', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Terminé</div>
                                        <div @click="selected='cancelled', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Annulé</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: '{{ old('priority', $project->priority) }}' }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'low' ? 'Basse' : (selected === 'medium' ? 'Moyenne' : (selected === 'high' ? 'Haute' : 'Critique'))"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Priorité</span>
                                    </div>
                                    <input type="hidden" name="priority" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='low', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Basse</div>
                                        <div @click="selected='medium', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Moyenne</div>
                                        <div @click="selected='high', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Haute</div>
                                        <div @click="selected='critical', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Critique</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date de début -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="start_date" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Date de début</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5" />
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="due_date" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Date de fin</label>
                                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $project->due_date ? $project->due_date->format('Y-m-d') : '') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5" />
                                </div>
                            </div>

                            <!-- Budget -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="budget" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Budget (FCFA)</label>
                                    <i class="las la-dollar-sign text-lg"></i>
                                    <input type="number" step="1" id="budget" name="budget" value="{{ old('budget', $project->budget) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" />
                                </div>
                            </div>

                            <!-- Coût réel -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="actual_cost" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Coût réel (FCFA)</label>
                                    <i class="las la-dollar-sign text-lg"></i>
                                    <input type="number" step="1" id="actual_cost" name="actual_cost" value="{{ old('actual_cost', $project->actual_cost) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" />
                                </div>
                            </div>

                            <!-- Progression -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="progress" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Progression (%)</label>
                                    <i class="las la-percent text-lg"></i>
                                    <input type="number" step="1" id="progress" name="progress" value="{{ old('progress', $project->progress) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" min="0" max="100" />
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="tags" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Tags</label>
                                    <input type="text" id="tags" name="tags" value="{{ old('tags', is_array($project->tags) ? implode(', ', $project->tags) : $project->tags) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Séparer les tags par des virgules (ex: urgent, important, web)" />
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 xxl:gap-6 mt-6">
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn-primary-outlined px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
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
