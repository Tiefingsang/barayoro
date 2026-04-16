<form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nom complet *
            </label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $user->name) }}" 
                   required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Email *
            </label>
            <input type="email" 
                   name="email" 
                   value="{{ old('email', $user->email) }}" 
                   required
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Téléphone
            </label>
            <input type="tel" 
                   name="phone" 
                   value="{{ old('phone', $user->phone) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Poste
            </label>
            <input type="text" 
                   name="position" 
                   value="{{ old('position', $user->position) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                ID Employé
            </label>
            <input type="text" 
                   name="employee_id" 
                   value="{{ old('employee_id', $user->employee_id) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Date d'embauche
            </label>
            <input type="date" 
                   name="hire_date" 
                   value="{{ old('hire_date', $user->hire_date?->format('Y-m-d')) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Type d'emploi
            </label>
            <select name="employment_type" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                <option value="full_time" {{ $user->employment_type == 'full_time' ? 'selected' : '' }}>Temps plein</option>
                <option value="part_time" {{ $user->employment_type == 'part_time' ? 'selected' : '' }}>Temps partiel</option>
                <option value="contract" {{ $user->employment_type == 'contract' ? 'selected' : '' }}>Contrat</option>
                <option value="intern" {{ $user->employment_type == 'intern' ? 'selected' : '' }}>Stagiaire</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Fuseau horaire
            </label>
            <select name="timezone" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                @foreach(timezone_identifiers_list() as $tz)
                    <option value="{{ $tz }}" {{ $user->timezone == $tz ? 'selected' : '' }}>
                        {{ $tz }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Langue
            </label>
            <select name="language" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                <option value="fr" {{ $user->language == 'fr' ? 'selected' : '' }}>Français</option>
                <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                <option value="es" {{ $user->language == 'es' ? 'selected' : '' }}>Español</option>
                <option value="de" {{ $user->language == 'de' ? 'selected' : '' }}>Deutsch</option>
                <option value="it" {{ $user->language == 'it' ? 'selected' : '' }}>Italiano</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Thème
            </label>
            <select name="theme" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                <option value="light" {{ $user->theme == 'light' ? 'selected' : '' }}>Clair</option>
                <option value="dark" {{ $user->theme == 'dark' ? 'selected' : '' }}>Sombre</option>
                <option value="system" {{ $user->theme == 'system' ? 'selected' : '' }}>Système</option>
            </select>
        </div>
    </div>
    
    <div class="flex justify-end pt-4">
        <button type="submit" 
                class="bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-6 rounded-lg transition">
            Enregistrer les modifications
        </button>
    </div>
</form>