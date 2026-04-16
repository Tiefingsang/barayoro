<form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
    @csrf
    @method('PUT')
    
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Mot de passe actuel *
        </label>
        <input type="password" 
               name="current_password" 
               required
               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nouveau mot de passe *
        </label>
        <input type="password" 
               name="password" 
               required
               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères, incluant majuscule, minuscule, chiffre et caractère spécial.</p>
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Confirmer le mot de passe *
        </label>
        <input type="password" 
               name="password_confirmation" 
               required
               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
    </div>
    
    <div class="flex justify-end">
        <button type="submit" 
                class="bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-6 rounded-lg transition">
            Changer le mot de passe
        </button>
    </div>
</form>