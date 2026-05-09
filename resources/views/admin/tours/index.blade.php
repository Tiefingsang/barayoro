@extends('layouts.app')

@section('title', 'Gestion des tours')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestion des tours</h1>
                <p class="text-gray-500 mt-1">Gérez les tours et circuits proposés</p>
            </div>
            <a href="{{ route('admin.tours.create') }}" class="px-4 py-2 gradient-bg text-white rounded-lg btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Nouveau tour
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <form method="GET" action="{{ route('admin.tours.index') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, lieu..." class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Table des tours -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Places</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tours as $tour)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            @if($tour->image)
                                <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $tour->title }}</div>
                            <div class="text-sm text-gray-500">{{ $tour->location }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $tour->category }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($tour->price, 0, ',', ' ') }} €</td>
                        <td class="px-6 py-4">{{ $tour->duration_days }} jour(s)</td>
                        <td class="px-6 py-4">{{ $tour->max_participants }}</td>
                        <td class="px-6 py-4">
                            @if($tour->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('tours.show', $tour->id) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.tours.edit', $tour->id) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tours.destroy', $tour->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce tour ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-map-marked-alt text-4xl mb-2 block"></i>
                            Aucun tour trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tours->links() }}
        </div>
    </div>
</div>
@endsection