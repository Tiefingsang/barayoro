@extends('layouts.app')

@section('title', 'Liste des utilisateurs')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Fil d'Ariane -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des utilisateurs</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm">•</li>
              <li>
                <a class="flex items-center gap-2" href="#">
                  <i class="las la-users shrink-0"></i>
                  <span>Utilisateurs</span>
                </a>
              </li>
              <li class="text-sm">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-list shrink-0"></i>
                  <span>Liste</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des utilisateurs</h4>
            <div class="flex flex-wrap items-center gap-4">
              @can('create_users')
              <a href="{{ route('users.create') }}" class="btn-primary-outlined">
                <i class="las la-plus-circle text-sm"></i> Nouvel utilisateur
              </a>
              @endcan
            </div>
          </div>

          <!-- Tableau -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                  <th class="px-4 py-3 text-left text-sm font-semibold">Utilisateur</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Téléphone</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Fonction</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Rôle</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Statut</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($usersData as $user)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                      <img src="{{ $user['avatar_url'] }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $user['name'] }}">
                      <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $user['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user['email'] }}</td>
                  <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user['phone'] ?? '-' }}</td>
                  <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user['position'] ?? '-' }}</td>
                  <td class="px-4 py-3">
                    @if($user['role_name'] == 'admin')
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Administrateur</span>
                    @elseif($user['role_name'] == 'manager')
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Gestionnaire</span>
                    @else
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Employé</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    @if($user['is_active'])
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Actif</span>
                    @else
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Inactif</span>
                    @endif
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('users.show', $user['id']) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      @can('edit_users')
                      <a href="{{ route('users.edit', $user['id']) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      @endcan
                      @can('delete_users')
                        @if($user['id'] != auth()->id())
                          <form action="{{ route('users.destroy', $user['id']) }}" method="POST" class="inline" onsubmit="return confirm('Désactiver cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Désactiver">
                              <i class="las la-ban text-xl"></i>
                            </button>
                          </form>
                        @endif
                      @endcan
                      @if(!$user['is_active'])
                        <form action="{{ route('users.activate', $user['id']) }}" method="POST" class="inline" onsubmit="return confirm('Activer cet utilisateur ?')">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="text-green-600 hover:text-green-800" title="Activer">
                            <i class="las la-check-circle text-xl"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    Aucun utilisateur trouvé.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-4">
              <p class="text-sm text-gray-600">Lignes par page :</p>
              <select onchange="window.location.href = this.value" class="border rounded-lg px-3 py-1 text-sm">
                <option value="{{ route('users.index', ['per_page' => 10]) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('users.index', ['per_page' => 25]) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('users.index', ['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('users.index', ['per_page' => 100]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p class="text-sm text-gray-600">{{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }}</p>
              <div class="flex gap-2">
                @if($users->onFirstPage())
                  <button disabled class="px-3 py-1 border rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="las la-angle-left"></i>
                  </button>
                @else
                  <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 border rounded-lg hover:bg-gray-100">
                    <i class="las la-angle-left"></i>
                  </a>
                @endif
                @if($users->hasMorePages())
                  <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 border rounded-lg hover:bg-gray-100">
                    <i class="las la-angle-right"></i>
                  </a>
                @else
                  <button disabled class="px-3 py-1 border rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="las la-angle-right"></i>
                  </button>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
