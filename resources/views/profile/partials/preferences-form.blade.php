<form method="POST" action="{{ route('profile.preferences') }}" class="space-y-4">
    @csrf
    @method('PUT')
    
    <div class="space-y-3">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Notifications</h3>
        
        <label class="flex items-center justify-between">
            <span class="text-gray-600 dark:text-gray-400">Notifications par email</span>
            <input type="checkbox" 
                   name="notifications_email" 
                   value="1"
                   {{ ($user->preferences['notifications_email'] ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
        </label>
        
        <label class="flex items-center justify-between">
            <span class="text-gray-600 dark:text-gray-400">Notifications push</span>
            <input type="checkbox" 
                   name="notifications_push" 
                   value="1"
                   {{ ($user->preferences['notifications_push'] ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
        </label>
        
        <label class="flex items-center justify-between">
            <span class="text-gray-600 dark:text-gray-400">Sons de notification</span>
            <input type="checkbox" 
                   name="notifications_sound" 
                   value="1"
                   {{ ($user->preferences['notifications_sound'] ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
        </label>
    </div>
    
    <div class="space-y-3 pt-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Affichage</h3>
        
        <div>
            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-2">
                Disposition du tableau de bord
            </label>
            <select name="dashboard_layout" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="grid" {{ ($user->preferences['dashboard_layout'] ?? 'grid') == 'grid' ? 'selected' : '' }}>Grille</option>
                <option value="list" {{ ($user->preferences['dashboard_layout'] ?? 'grid') == 'list' ? 'selected' : '' }}>Liste</option>
                <option value="compact" {{ ($user->preferences['dashboard_layout'] ?? 'grid') == 'compact' ? 'selected' : '' }}>Compact</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-2">
                Éléments par page
            </label>
            <select name="items_per_page" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="10" {{ ($user->preferences['items_per_page'] ?? 25) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ ($user->preferences['items_per_page'] ?? 25) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ ($user->preferences['items_per_page'] ?? 25) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ ($user->preferences['items_per_page'] ?? 25) == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </div>
    
    <div class="flex justify-end pt-4">
        <button type="submit" 
                class="bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-6 rounded-lg transition">
            Enregistrer les préférences
        </button>
    </div>
</form>