@extends('layouts.app')

@section('title', 'Gestion des clients')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des clients</h2>
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
                  <i class="las la-users shrink-0"></i>
                  <span>Clients</span>
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

        <!-- Liste des clients -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des clients</h4>
            <div class="flex flex-wrap items-center gap-4">
              <a href="{{ route('clients.create') }}" class="btn-primary-outlined py-2">
                <i class="las la-plus-circle text-sm"></i> Nouveau client
              </a>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" id="sort" class="nc-select n20" onchange="window.location.href = this.value">
                  <option value="{{ route('clients.index', ['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récents</option>
                  <option value="{{ route('clients.index', ['sort' => 'name', 'order' => 'asc']) }}" {{ request('sort') == 'name' && request('order') == 'asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                  <option value="{{ route('clients.index', ['sort' => 'name', 'order' => 'desc']) }}" {{ request('sort') == 'name' && request('order') == 'desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtres -->
          <div class="flex justify-between items-center gap-4 flex-wrap mb-4 xl:mb-6">
            <div class="flex gap-4 xxl:gap-6 flex-wrap">

              <!-- Filtre par statut -->
              <div x-data="{open:false, selected: '{{ request('status') }}', items: ['', 'active', 'inactive', 'lead'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected === 'active' ? 'Actifs' : (selected === 'inactive' ? 'Inactifs' : (selected === 'lead' ? 'Prospects' : 'Tous'))"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('clients.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300 hover:bg-primary-50">Tous</div>
                  <div @click="selected='active'; open=false; window.location.href='{{ route('clients.index', ['status' => 'active']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300 hover:bg-primary-50">Actifs</div>
                  <div @click="selected='inactive'; open=false; window.location.href='{{ route('clients.index', ['status' => 'inactive']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300 hover:bg-primary-50">Inactifs</div>
                  <div @click="selected='lead'; open=false; window.location.href='{{ route('clients.index', ['status' => 'lead']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300 hover:bg-primary-50">Prospects</div>
                </div>
              </div>

              <!-- Recherche -->
              <form method="GET" action="{{ route('clients.index') }}" class="flex items-center rounded-full border border-neutral-30 dark:border-neutral-500 max-w-[250px] w-full py-2 px-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full bg-transparent" />
                <button type="submit"><i class="las la-search text-lg"></i></button>
              </form>
            </div>
          </div>

          <!-- Tableau des clients -->
          <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <thead class="text-left">
                <tr class="bg-neutral-20 dark:bg-neutral-903">
                  <th class="px-6 py-4">Code</th>
                  <th class="px-6 py-4">Client</th>
                  <th class="px-6 py-4">Contact</th>
                  <th class="px-6 py-4">Téléphone</th>
                  <th class="px-6 py-4">Ville/Pays</th>
                  <th class="px-6 py-4">Statut</th>
                  <th class="px-6 py-4">Actions</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($clients as $client)
                <tr class="border-b border-neutral-30 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:hover:bg-neutral-903">
                  <td class="px-6 py-3">
                    <span class="text-sm font-mono">{{ $client->code }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div>
                      <p class="font-medium">{{ $client->name }}</p>
                      <span class="text-xs text-gray-500">{{ $client->email ?? 'Pas d\'email' }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $client->contact_person ?? '-' }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $client->phone ?? '-' }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $client->city ?? '-' }}{{ $client->city && $client->country ? ', ' : '' }}{{ $client->country ?? '' }}</span>
                  </td>
                  <td class="px-6 py-3">
                    @if($client->status == 'active')
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                    @elseif($client->status == 'inactive')
                      <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                    @else
                      <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Prospect</span>
                    @endif
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      <a href="{{ route('clients.edit', $client) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce client ?')">
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
                  <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="las la-users-slash text-4xl mb-2 block"></i>
                    Aucun client trouvé.
                    <div class="mt-2">
                      <a href="{{ route('clients.create') }}" class="text-primary-300 hover:underline">Créer votre premier client</a>
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
                <option value="{{ route('clients.index', array_merge(request()->query(), ['per_page' => 10])) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('clients.index', array_merge(request()->query(), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('clients.index', array_merge(request()->query(), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('clients.index', array_merge(request()->query(), ['per_page' => 100])) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p>{{ $clients->firstItem() ?? 0 }}-{{ $clients->lastItem() ?? 0 }} sur {{ $clients->total() }}</p>
              @if($clients->onFirstPage())
                <button disabled class="opacity-50"><i class="las la-angle-left text-xl"></i></button>
              @else
                <a href="{{ $clients->previousPageUrl() }}"><i class="las la-angle-left text-xl"></i></a>
              @endif
              @if($clients->hasMorePages())
                <a href="{{ $clients->nextPageUrl() }}"><i class="las la-angle-right text-xl"></i></a>
              @else
                <button disabled class="opacity-50"><i class="las la-angle-right text-xl"></i></button>
              @endif
            </div>
          </div>
        </div>
      </div>
@endsection
