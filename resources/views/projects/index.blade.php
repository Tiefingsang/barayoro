@extends('layouts.app')

@section('title', 'Gestion des projets')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des projets</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="#">
                  <i class="las la-project-diagram shrink-0"></i>
                  <span>Projets</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-list shrink-0"></i>
                  <span>Liste</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Liste des projets -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des projets</h4>
            <div class="flex flex-wrap items-center gap-4">
              <a href="{{ route('projects.create') }}" class="btn-primary-outlined py-2">
                <i class="las la-plus-circle text-sm"></i> Nouveau projet
              </a>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" id="sort" class="nc-select n20" onchange="window.location.href = this.value">
                  <option value="{{ route('projects.index', ['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récents</option>
                  <option value="{{ route('projects.index', ['sort' => 'name', 'order' => 'asc']) }}" {{ request('sort') == 'name' && request('order') == 'asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                  <option value="{{ route('projects.index', ['sort' => 'due_date', 'order' => 'asc']) }}" {{ request('sort') == 'due_date' && request('order') == 'asc' ? 'selected' : '' }}>Échéance proche</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtres -->
          <div class="flex justify-between items-center gap-4 flex-wrap mb-4 xl:mb-6">
            <div class="flex gap-4 xxl:gap-6 flex-wrap">

              <!-- Filtre par statut -->
              <div x-data="{open:false, selected: '{{ request('status') }}', items: ['', 'draft', 'planned', 'in_progress', 'on_hold', 'completed', 'cancelled'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected === 'draft' ? 'Brouillon' : (selected === 'planned' ? 'Planifié' : (selected === 'in_progress' ? 'En cours' : (selected === 'on_hold' ? 'En attente' : (selected === 'completed' ? 'Terminé' : (selected === 'cancelled' ? 'Annulé' : 'Tous')))))"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('projects.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Tous</div>
                  <div @click="selected='draft'; open=false; window.location.href='{{ route('projects.index', ['status' => 'draft']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Brouillon</div>
                  <div @click="selected='planned'; open=false; window.location.href='{{ route('projects.index', ['status' => 'planned']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Planifié</div>
                  <div @click="selected='in_progress'; open=false; window.location.href='{{ route('projects.index', ['status' => 'in_progress']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">En cours</div>
                  <div @click="selected='on_hold'; open=false; window.location.href='{{ route('projects.index', ['status' => 'on_hold']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">En attente</div>
                  <div @click="selected='completed'; open=false; window.location.href='{{ route('projects.index', ['status' => 'completed']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Terminé</div>
                  <div @click="selected='cancelled'; open=false; window.location.href='{{ route('projects.index', ['status' => 'cancelled']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Annulé</div>
                </div>
              </div>

              <!-- Filtre par priorité -->
              <div x-data="{open:false, selected: '{{ request('priority') }}', items: ['', 'low', 'medium', 'high', 'critical'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected === 'low' ? 'Basse' : (selected === 'medium' ? 'Moyenne' : (selected === 'high' ? 'Haute' : (selected === 'critical' ? 'Critique' : 'Priorité')))"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('projects.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Tous</div>
                  <div @click="selected='low'; open=false; window.location.href='{{ route('projects.index', ['priority' => 'low']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Basse</div>
                  <div @click="selected='medium'; open=false; window.location.href='{{ route('projects.index', ['priority' => 'medium']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Moyenne</div>
                  <div @click="selected='high'; open=false; window.location.href='{{ route('projects.index', ['priority' => 'high']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Haute</div>
                  <div @click="selected='critical'; open=false; window.location.href='{{ route('projects.index', ['priority' => 'critical']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Critique</div>
                </div>
              </div>

              <!-- Recherche -->
              <form method="GET" action="{{ route('projects.index') }}" class="flex items-center rounded-full border border-neutral-30 dark:border-neutral-500 max-w-[250px] w-full py-2 px-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full bg-transparent" />
                <button type="submit"><i class="las la-search text-lg"></i></button>
              </form>
            </div>
          </div>

          <!-- Tableau des projets -->
          <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <thead class="text-left">
                <tr class="bg-neutral-20 dark:bg-neutral-903">
                  <th class="px-6 py-4">Code</th>
                  <th class="px-6 py-4">Nom</th>
                  <th class="px-6 py-4">Client</th>
                  <th class="px-6 py-4">Manager</th>
                  <th class="px-6 py-4">Statut</th>
                  <th class="px-6 py-4">Priorité</th>
                  <th class="px-6 py-4">Progression</th>
                  <th class="px-6 py-4">Actions</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($projects as $project)
                <tr class="border-b border-neutral-30 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:hover:bg-neutral-903">
                  <td class="px-6 py-3">
                    <span class="text-sm font-mono">{{ $project->code }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div>
                      <p class="font-medium">{{ $project->name }}</p>
                      <span class="text-xs text-gray-500">{{ Str::limit($project->description, 50) }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $project->client->name ?? '-' }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      @if($project->manager)
                        <img src="{{ $project->manager->avatar_url }}" class="w-6 h-6 rounded-full">
                        <span class="text-sm">{{ $project->manager->name }}</span>
                      @else
                        <span class="text-sm text-gray-400">Non assigné</span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    @php
                      $statusColors = [
                        'draft' => 'gray',
                        'planned' => 'blue',
                        'in_progress' => 'orange',
                        'on_hold' => 'yellow',
                        'completed' => 'green',
                        'cancelled' => 'red',
                      ];
                      $statusLabels = [
                        'draft' => 'Brouillon',
                        'planned' => 'Planifié',
                        'in_progress' => 'En cours',
                        'on_hold' => 'En attente',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                      ];
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$project->status] }}-100 text-{{ $statusColors[$project->status] }}-800">
                      {{ $statusLabels[$project->status] }}
                    </span>
                  </td>
                  <td class="px-6 py-3">
                    @php
                      $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'critical' => 'red'];
                      $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'critical' => 'Critique'];
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$project->priority] }}-100 text-{{ $priorityColors[$project->priority] }}-800">
                      {{ $priorityLabels[$project->priority] }}
                    </span>
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <div class="w-16 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress }}%"></div>
                      </div>
                      <span class="text-xs">{{ $project->progress }}%</span>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      <a href="{{ route('projects.edit', $project) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce projet ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                          <i class="las la-trash text-xl"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <i class="las la-project-diagram text-4xl mb-2 block"></i>
                    Aucun projet trouvé.
                    <div class="mt-2">
                      <a href="{{ route('projects.create') }}" class="text-primary-300 hover:underline">Créer votre premier projet</a>
                    </div>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex items-center gap-5 justify-center flex-col md:flex-row md:justify-between whitespace-nowrap">
            <div class="flex gap-4 items-center">
              <p>Lignes par page :</p>
              <select name="per_page" class="bg-transparent dark:bg-neutral-904 border rounded-lg px-3 py-1" onchange="window.location.href = this.value">
                <option value="{{ route('projects.index', array_merge(request()->query(), ['per_page' => 10])) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('projects.index', array_merge(request()->query(), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('projects.index', array_merge(request()->query(), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('projects.index', array_merge(request()->query(), ['per_page' => 100])) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p>{{ $projects->firstItem() ?? 0 }}-{{ $projects->lastItem() ?? 0 }} sur {{ $projects->total() }}</p>
              @if($projects->onFirstPage())
                <button disabled class="opacity-50"><i class="las la-angle-left text-xl"></i></button>
              @else
                <a href="{{ $projects->previousPageUrl() }}"><i class="las la-angle-left text-xl"></i></a>
              @endif
              @if($projects->hasMorePages())
                <a href="{{ $projects->nextPageUrl() }}"><i class="las la-angle-right text-xl"></i></a>
              @else
                <button disabled class="opacity-50"><i class="las la-angle-right text-xl"></i></button>
              @endif
            </div>
          </div>
        </div>
      </div>
@endsection
