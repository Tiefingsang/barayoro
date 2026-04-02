@extends('layouts.app')

@section('title', 'Gestionnaire de fichiers')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Breadcrumb -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h2 class="mb-3 xxxl:mb-5">Gestionnaire de fichiers</h2>
                    <ul class="flex flex-wrap gap-2 items-center">
                        <li><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Accueil</a></li>
                        <li class="text-neutral-100">•</li>
                        <li><a href="{{ route('files.index') }}" class="text-primary-300">Fichiers</a></li>
                        @foreach($breadcrumbs as $crumb)
                        <li class="text-neutral-100">•</li>
                        <li>
                            <a href="{{ route('files.index', ['folder' => $crumb->id]) }}">{{ $crumb->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleView()" class="btn-secondary px-3 py-2">
                        <i class="las la-{{ $view === 'grid' ? 'list' : 'th' }}"></i>
                    </button>
                    <button onclick="openCreateFolderModal()" class="btn-primary-outlined px-3 py-2">
                        <i class="las la-folder-plus"></i> Nouveau dossier
                    </button>
                    <button onclick="openUploadModal()" class="btn-primary px-3 py-2">
                        <i class="las la-upload"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue Grid -->
    @if($view === 'grid')
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @foreach($items as $item)
        <div class="file-item white-box p-3 text-center hover:shadow-lg transition cursor-pointer"
             data-id="{{ $item->id }}"
             data-type="{{ $item->type }}"
             data-name="{{ $item->name }}"
             onclick="openFileModal({{ $item->id }}, '{{ $item->type }}')">
            <i class="fas {{ $item->icon }} text-4xl {{ $item->color }}"></i>
            <p class="mt-2 text-sm font-medium truncate">{{ $item->name }}</p>
            @if($item->type === 'file')
            <p class="text-xs text-gray-500">{{ $item->formatted_size }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <!-- Vue Liste -->
    <div class="white-box">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Taille</th>
                        <th class="px-4 py-3 text-left">Modifié le</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 cursor-pointer" onclick="openFileModal({{ $item->id }}, '{{ $item->type }}')">
                                <i class="fas {{ $item->icon }} {{ $item->color }}"></i>
                                <span>{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $item->type === 'folder' ? 'Dossier' : strtoupper($item->extension) }}</td>
                        <td class="px-4 py-3">{{ $item->type === 'file' ? $item->formatted_size : '-' }}</td>
                        <td class="px-4 py-3">{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @if($item->type === 'file')
                                <a href="{{ route('files.download', $item) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                                <button onclick="renameFile({{ $item->id }}, '{{ $item->name }}')" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteFile({{ $item->id }}, '{{ $item->name }}')" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

<!-- Modal Nouveau Dossier -->
<div id="createFolderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Créer un nouveau dossier</h3>
        <form action="{{ route('files.create-folder') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nom du dossier</label>
                <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCreateFolderModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Uploader un fichier</h3>
        <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Fichier</label>
                <input type="file" name="file" required class="w-full border rounded-lg px-4 py-2">
                <p class="text-xs text-gray-500 mt-1">Max: 100MB</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Uploader</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Actions Fichier -->
<div id="fileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 id="fileModalTitle" class="text-lg font-semibold mb-4">Actions</h3>
        <div id="fileModalContent" class="space-y-2">
            <!-- Contenu dynamique -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleView() {
    const currentView = '{{ $view }}';
    const newView = currentView === 'grid' ? 'list' : 'grid';
    window.location.href = '{{ route("files.set-view") }}?view=' + newView;
}

function openCreateFolderModal() {
    document.getElementById('createFolderModal').classList.add('flex');
    document.getElementById('createFolderModal').classList.remove('hidden');
}

function closeCreateFolderModal() {
    document.getElementById('createFolderModal').classList.add('hidden');
    document.getElementById('createFolderModal').classList.remove('flex');
}

function openUploadModal() {
    document.getElementById('uploadModal').classList.add('flex');
    document.getElementById('uploadModal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
}

function openFileModal(fileId, type) {
    const modal = document.getElementById('fileModal');
    const title = document.getElementById('fileModalTitle');
    const content = document.getElementById('fileModalContent');

    if (type === 'folder') {
        title.textContent = 'Actions du dossier';
        content.innerHTML = `
            <a href="{{ route('files.index') }}?folder=${fileId}" class="block w-full text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-folder-open"></i> Ouvrir
            </a>
            <button onclick="renameFile(${fileId})" class="block w-full text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                <i class="fas fa-edit"></i> Renommer
            </button>
            <button onclick="deleteFile(${fileId})" class="block w-full text-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        `;
    } else {
        title.textContent = 'Actions du fichier';
        content.innerHTML = `
            <a href="{{ route('files.download', '') }}/${fileId}" class="block w-full text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-download"></i> Télécharger
            </a>
            <a href="{{ route('files.show', '') }}/${fileId}" class="block w-full text-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                <i class="fas fa-eye"></i> Prévisualiser
            </a>
            <button onclick="renameFile(${fileId})" class="block w-full text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                <i class="fas fa-edit"></i> Renommer
            </button>
            <button onclick="deleteFile(${fileId})" class="block w-full text-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        `;
    }

    modal.classList.add('flex');
    modal.classList.remove('hidden');
}

function closeFileModal() {
    document.getElementById('fileModal').classList.add('hidden');
    document.getElementById('fileModal').classList.remove('flex');
}

function renameFile(fileId, currentName) {
    const newName = prompt('Nouveau nom:', currentName);
    if (newName && newName !== currentName) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('files.rename', '') }}/${fileId}`;
        form.innerHTML = `
            @csrf
            @method('PUT')
            <input type="text" name="name" value="${newName}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteFile(fileId, fileName) {
    if (confirm(`Supprimer "${fileName}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('files.destroy', '') }}/${fileId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Fermer le modal en cliquant en dehors
document.getElementById('fileModal').addEventListener('click', function(e) {
    if (e.target === this) closeFileModal();
});
document.getElementById('createFolderModal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateFolderModal();
});
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) closeUploadModal();
});
</script>
@endpush
