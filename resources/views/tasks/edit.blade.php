@extends('layouts.app')

@section('title', 'Modifier la tâche')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Modifier la tâche</h2>
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
                @if($task->project)
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('projects.show', $task->project) }}">
                        <i class="las la-eye shrink-0"></i>
                        <span>{{ $task->project->name }}</span>
                    </a>
                </li>
                @endif
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
        <h4 class="bb-dashed-n30">Modifier la tâche #{{ $task->code }}</h4>

        <form method="POST" action="{{ route('tasks.update', $task) }}">
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

                            <!-- Projet -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="project_id" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Projet *</label>
                                    <select name="project_id" id="project_id" class="w-full s-text bg-transparent py-2.5 xl:py-3.5" required>
                                        <option value="">Sélectionner un projet</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }} ({{ $project->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Titre -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="title" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Titre *</label>
                                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="Ex: Développer la page d'accueil" required />
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="description" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                              class="w-full s-text bg-transparent py-2.5 xl:py-3.5 resize-none"
                                              placeholder="Description détaillée de la tâche...">{{ old('description', $task->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Assigné à -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="assigned_to" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Assigner à</label>
                                    <select name="assigned_to" id="assigned_to" class="w-full s-text bg-transparent py-2.5 xl:py-3.5">
                                        <option value="">Sélectionner un utilisateur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->position ?? 'Employé' }})
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
                                            <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: '{{ old('status', $task->status) }}' }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'pending' ? 'En attente' : (selected === 'in_progress' ? 'En cours' : (selected === 'review' ? 'En révision' : (selected === 'completed' ? 'Terminé' : 'Annulé')))"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Statut</span>
                                    </div>
                                    <input type="hidden" name="status" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='pending', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">En attente</div>
                                        <div @click="selected='in_progress', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">En cours</div>
                                        <div @click="selected='review', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">En révision</div>
                                        <div @click="selected='completed', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Terminé</div>
                                        <div @click="selected='cancelled', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Annulé</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="col-span-2 md:col-span-1">
                                <div x-data="{ isOpen: false, selected: '{{ old('priority', $task->priority) }}' }" class="relative">
                                    <div @click="isOpen=!isOpen" class="relative flex cursor-pointer items-center justify-between rounded-xl border border-neutral-40 px-6 py-3 dark:border-neutral-500">
                                        <span x-text="selected === 'low' ? 'Basse' : (selected === 'medium' ? 'Moyenne' : (selected === 'high' ? 'Haute' : 'Urgente'))"></span>
                                        <i class="las la-angle-down text-lg duration-300" :class="isOpen?'rotate-180':'rotate-0'"></i>
                                        <span class="absolute -top-2 left-5 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Priorité</span>
                                    </div>
                                    <input type="hidden" name="priority" x-model="selected">
                                    <div x-show="isOpen" @click.away="isOpen=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                                        <div @click="selected='low', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Basse</div>
                                        <div @click="selected='medium', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Moyenne</div>
                                        <div @click="selected='high', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Haute</div>
                                        <div @click="selected='urgent', isOpen=false" class="cursor-pointer rounded-md px-4 py-2 hover:bg-primary-50">Urgente</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date de début -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="start_date" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Date de début</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5" />
                                </div>
                            </div>

                            <!-- Date d'échéance -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-4 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="due_date" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Date d'échéance</label>
                                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5" />
                                </div>
                            </div>

                            <!-- Heures estimées -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="estimated_hours" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Heures estimées</label>
                                    <i class="las la-clock text-lg"></i>
                                    <input type="number" id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours', $task->estimated_hours) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" min="0" max="168" />
                                </div>
                            </div>

                            <!-- Heures réelles -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="actual_hours" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Heures réelles</label>
                                    <i class="las la-clock text-lg"></i>
                                    <input type="number" id="actual_hours" name="actual_hours" value="{{ old('actual_hours', $task->actual_hours) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" min="0" />
                                </div>
                            </div>

                            <!-- Progression -->
                            <div class="col-span-2 md:col-span-1">
                                <div class="relative flex items-center gap-2 rounded-xl border border-neutral-40 w-full px-4 dark:border-neutral-500 lg:px-6">
                                    <label for="progress" class="absolute -top-2 bg-neutral-0 px-2 text-xs dark:bg-neutral-904">Progression (%)</label>
                                    <i class="las la-percent text-lg"></i>
                                    <input type="number" id="progress" name="progress" value="{{ old('progress', $task->progress) }}"
                                           class="w-full s-text bg-transparent py-2.5 xl:py-3.5"
                                           placeholder="0" min="0" max="100" />
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 xxl:gap-6 mt-6">
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('projects.show', $task->project) }}" class="btn-primary-outlined px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
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
