<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <div class="p-6 text-center">
        <!-- Avatar -->
        <div class="relative inline-block">
            <img src="{{ $user->avatar_url }}" 
                 alt="{{ $user->name }}" 
                 class="w-32 h-32 rounded-full object-cover border-4 border-primary-300">
            
            <button type="button" 
                    onclick="document.getElementById('avatarInput').click()"
                    class="absolute bottom-0 right-0 bg-primary-500 text-white p-2 rounded-full hover:bg-primary-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </button>
            
            <input type="file" id="avatarInput" accept="image/*" class="hidden">
        </div>
        
        <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
        <p class="text-gray-600 dark:text-gray-400">{{ $user->position ?? 'Sans poste' }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ $user->email }}</p>
        
        <!-- Badge rôle -->
        <div class="mt-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                {{ ucfirst($user->main_role) }}
            </span>
        </div>
    </div>
    
    <!-- Statistiques rapides - CORRIGÉ avec les bonnes clés -->
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
                    <span class="text-gray-900 dark:text-white">{{ $user->department->name }}</span>
                </div>
            @endif
            @if($user->manager)
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Manager</span>
                    <span class="text-gray-900 dark:text-white">{{ $user->manager->name }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('avatar', file);
    
    fetch('{{ route("profile.avatar.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
@endpush