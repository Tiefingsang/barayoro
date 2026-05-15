{{-- resources/views/profile/partials/sidebar.blade.php --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <div class="p-6 text-center">
        <!-- Avatar -->
        <div class="relative inline-block">
            @php
                // Utiliser l'accesseur avatar_url au lieu de l'attribut direct
                $avatarUrl = $user->avatar_url;
            @endphp
            
            <img src="{{ $avatarUrl }}" 
                 alt="{{ $user->name }}" 
                 id="profileAvatar"
                 class="w-32 h-32 rounded-full object-cover border-4 border-orange-300">
            
            <button type="button" 
                    onclick="document.getElementById('avatarInput').click()"
                    class="absolute bottom-0 right-0 bg-orange-custom text-white p-2 rounded-full hover:bg-orange-700 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </button>
            
           <form id="avatarForm" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden">
</form>
        </div>
        
        <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
        <p class="text-gray-600 dark:text-gray-400">{{ $user->position ?? 'Sans poste' }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ $user->email }}</p>
        
        <!-- Badge rôle -->
        <div class="mt-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                {{ ucfirst($user->main_role ?? 'Utilisateur') }}
            </span>
        </div>
        
        <!-- Message de confirmation -->
        <div id="avatarMessage" class="mt-3 text-sm hidden"></div>
    </div>
    
    <!-- Statistiques rapides -->
    <div class="border-t border-gray-200 dark:border-gray-700 p-6">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Statistiques</h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 dark:text-gray-400">Tâches créées</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['total_tasks_created'] ?? 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600 dark:text-gray-400">Tâches assignées</span>
                <span class="font-semibold text-green-600">{{ $stats['total_tasks_assigned'] ?? 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600 dark:text-gray-400">Projets</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['total_projects'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <!-- Informations employé -->
    <div class="border-t border-gray-200 dark:border-gray-700 p-6">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Informations employé</h4>
        <div class="space-y-2 text-sm">
            @if($user->employee_id)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">ID Employé</span>
                    <span class="text-gray-900 dark:text-white">{{ $user->employee_id }}</span>
                </div>
            @endif
            @if($user->hire_date)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Date d'embauche</span>
                    <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') }}</span>
                </div>
            @endif
            @if($user->employment_type)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Type d'emploi</span>
                    <span class="text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $user->employment_type)) }}</span>
                </div>
            @endif
            @if($user->department)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Département</span>
                    <span class="text-gray-900 dark:text-white">{{ $user->department->name ?? 'Non défini' }}</span>
                </div>
            @endif
            @if($user->manager)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Manager</span>
                    <span class="text-gray-900 dark:text-white">{{ $user->manager->name ?? 'Non défini' }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatarInput');
    const avatarForm = document.getElementById('avatarForm');
    const profileAvatar = document.getElementById('profileAvatar');
    const avatarMessage = document.getElementById('avatarMessage');

    if (!avatarInput || !avatarForm || !profileAvatar) return;

    avatarInput.addEventListener('change', async function () {
        const file = this.files[0];

        if (!file) return;

        const formData = new FormData();
        formData.append('avatar', file);

        avatarMessage.className = 'mt-3 text-sm text-gray-500';
        avatarMessage.textContent = 'Upload en cours...';
        avatarMessage.classList.remove('hidden');

        try {
            const response = await fetch(avatarForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': avatarForm.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Erreur lors de l’upload.');
            }

            profileAvatar.src = data.avatar_url + '?t=' + new Date().getTime();

            avatarMessage.className = 'mt-3 text-sm text-green-600';
            avatarMessage.textContent = data.message || 'Avatar mis à jour avec succès.';

        } catch (error) {
            avatarMessage.className = 'mt-3 text-sm text-red-600';
            avatarMessage.textContent = error.message;
        }

        avatarInput.value = '';
    });
});
</script>