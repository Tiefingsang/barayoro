@extends('layouts.app')

@section('title', 'Gestion des départements')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des départements</h2>
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
                  <i class="las la-building shrink-0"></i>
                  <span>Départements</span>
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

        <!-- Liste des départements -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des départements</h4>
            <div class="flex flex-wrap items-center gap-4">
              <a href="{{ route('departments.create') }}" class="btn-primary-outlined py-2">
                <i class="las la-plus-circle text-sm"></i> Nouveau département
              </a>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" id="sort" class="nc-select n20" onchange="window.location.href = this.value">
                  <option value="{{ route('departments.index', ['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récents</option>
                  <option value="{{ route('departments.index', ['sort' => 'name', 'order' => 'asc']) }}" {{ request('sort') == 'name' && request('order') == 'asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                  <option value="{{ route('departments.index', ['sort' => 'name', 'order' => 'desc']) }}" {{ request('sort') == 'name' && request('order') == 'desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtres -->
          <div class="flex justify-between items-center gap-4 flex-wrap mb-4 xl:mb-6">
            <div class="flex gap-4 xxl:gap-6 flex-wrap">

              <!-- Filtre par statut -->
              <div x-data="{open:false, selected: '{{ request('status') }}', items: ['', 'active', 'inactive'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected === 'active' ? 'Actifs' : (selected === 'inactive' ? 'Inactifs' : 'Tous')"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('departments.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Tous</div>
                  <div @click="selected='active'; open=false; window.location.href='{{ route('departments.index', ['status' => 'active']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Actifs</div>
                  <div @click="selected='inactive'; open=false; window.location.href='{{ route('departments.index', ['status' => 'inactive']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Inactifs</div>
                </div>
              </div>

              <!-- Recherche -->
              <form method="GET" action="{{ route('departments.index') }}" class="flex items-center rounded-full border border-neutral-30 dark:border-neutral-500 max-w-[250px] w-full py-2 px-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full bg-transparent" />
                <button type="submit"><i class="las la-search text-lg"></i></button>
              </form>
            </div>
          </div>

          <!-- Tableau des départements -->
          <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <thead class="text-left">
                <tr class="bg-neutral-20 dark:bg-neutral-903">
                  <th class="px-6 py-4">Code</th>
                  <th class="px-6 py-4">Nom</th>
                  <th class="px-6 py-4">Manager</th>
                  <th class="px-6 py-4">Employés</th>
                  <th class="px-6 py-4">Statut</th>
                  <th class="px-6 py-4">Actions</th>
                  </tr>
              </thead>
              <tbody>
                @forelse($departments as $department)
                <tr class="border-b border-neutral-30 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:hover:bg-neutral-903">
                  <td class="px-6 py-3">
                    <span class="text-sm font-mono">{{ $department->code }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div>
                      <p class="font-medium">{{ $department->name }}</p>
                      <span class="text-xs text-gray-500">{{ Str::limit($department->description, 50) }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      @if($department->manager)
                        <img src="{{ $department->manager->avatar_url }}" class="w-6 h-6 rounded-full">
                        <span class="text-sm">{{ $department->manager->name }}</span>
                      @else
                        <span class="text-sm text-gray-400">Non assigné</span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $department->users()->count() }}</span>
                  </td>
                  <td class="px-6 py-3">
                    @if($department->is_active)
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                    @else
                      <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                    @endif
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('departments.show', $department) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      <a href="{{ route('departments.edit', $department) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce département ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                          <i class="las la-trash text-xl"></i>
                        </button>
                      </form>
                      <a href="{{ route('departments.toggle-status', $department) }}" class="text-yellow-600 hover:text-yellow-800" title="{{ $department->is_active ? 'Désactiver' : 'Activer' }}">
                        <i class="las la-{{ $department->is_active ? 'ban' : 'check-circle' }} text-xl"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <i class="las la-building text-4xl mb-2 block"></i>
                    Aucun département trouvé.
                    <div class="mt-2">
                      <a href="{{ route('departments.create') }}" class="text-primary-300 hover:underline">Créer votre premier département</a>
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
                <option value="{{ route('departments.index', array_merge(request()->query(), ['per_page' => 10])) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('departments.index', array_merge(request()->query(), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('departments.index', array_merge(request()->query(), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('departments.index', array_merge(request()->query(), ['per_page' => 100])) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p>{{ $departments->firstItem() ?? 0 }}-{{ $departments->lastItem() ?? 0 }} sur {{ $departments->total() }}</p>
              @if($departments->onFirstPage())
                <button disabled class="opacity-50"><i class="las la-angle-left text-xl"></i></button>
              @else
                <a href="{{ $departments->previousPageUrl() }}"><i class="las la-angle-left text-xl"></i></a>
              @endif
              @if($departments->hasMorePages())
                <a href="{{ $departments->nextPageUrl() }}"><i class="las la-angle-right text-xl"></i></a>
              @else
                <button disabled class="opacity-50"><i class="las la-angle-right text-xl"></i></button>
              @endif
            </div>
          </div>
        </div>
      </div>
@endsection
