<!-- resources/views/partials/footer.blade.php -->
<footer class="bg-gray-900 pt-16 pb-8 border-t border-gray-800">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <!-- Colonne 1: Logo et description -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">B</span>
                    </div>
                    <span class="text-2xl font-bold text-white">Barayoro</span>
                </div>
                <p class="text-gray-400 text-sm mb-4 leading-relaxed">
                    La solution SaaS complète pour la gestion d'entreprise en Afrique. Gérez vos ventes, 
                    factures, stocks, projets et équipes en un seul endroit.
                </p>
                <div class="flex items-center gap-3 mt-4">
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-orange-custom hover:text-white transition">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-orange-custom hover:text-white transition">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-orange-custom hover:text-white transition">
                        <i class="fab fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:bg-orange-custom hover:text-white transition">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Colonne 2: Liens rapides -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Liens rapides</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="#accueil" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="#fonctionnalites" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Fonctionnalités</span>
                        </a>
                    </li>
                    <li>
                        <a href="#offres" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Offres d'emploi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tarifs" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Tarifs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>À propos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.list') }}" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 3: Support -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Support</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('help.center') }}" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Centre d'aide</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('faq') }}" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Contactez-nous</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Documentation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-orange-custom transition flex items-center gap-2">
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span>Statut du service</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 4: Contact et newsletter -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Contact</h4>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-3 text-gray-400">
                        <i class="fas fa-map-marker-alt mt-1 text-orange-custom"></i>
                        <span class="text-sm">Bamako, Mali</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400">
                        <i class="fas fa-envelope text-orange-custom"></i>
                        <a href="mailto:contact@barayoro.com" class="text-sm hover:text-orange-custom transition">masadigitale@gmail.com</a>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400">
                        <i class="fas fa-phone-alt text-orange-custom"></i>
                        <a href="tel:+221781234567" class="text-sm hover:text-orange-custom transition">+223 92 51 64 05</a>
                    </li>
                </ul>

                <!-- Newsletter -->
                <div>
                    <h5 class="text-white font-medium mb-3">Newsletter</h5>
                    <form id="newsletter-form" action="#" method="POST" class="flex">
                        @csrf
                        <input type="email" name="email" required 
                               placeholder="Votre email" 
                               class="flex-1 px-4 py-2 bg-gray-800 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-orange-custom text-gray-300 text-sm">
                        <button type="submit" 
                                class="px-4 py-2 gradient-bg text-white rounded-r-lg hover:opacity-90 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div id="newsletter-message" class="mt-2 text-xs hidden"></div>
                    <p class="text-gray-500 text-xs mt-3">
                        Recevez nos actualités et offres spéciales
                    </p>
                </div>
            </div>
        </div>

        <!-- Légaux et copyright -->
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Barayoro. Tous droits réservés.
                </p>
                <div class="flex flex-wrap gap-6">
                    <a href="{{ route('terms') }}" class="text-gray-500 text-sm hover:text-orange-custom transition">
                        Conditions d'utilisation
                    </a>
                    <a href="{{ route('privacy') }}" class="text-gray-500 text-sm hover:text-orange-custom transition">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-gray-500 text-sm hover:text-orange-custom transition">
                        Cookies
                    </a>
                    <a href="#" class="text-gray-500 text-sm hover:text-orange-custom transition">
                        Mentions légales
                    </a>
                </div>
            </div>
            <p class="text-center text-gray-600 text-xs mt-6">
                Barayoro est une marque déposée de <a href="https://masadigitale.com" target="_blank" class="text-orange-custom hover:underline">Masadigitale</a>. 
                Toutes les autres marques sont la propriété de leurs détenteurs respectifs.
            </p>
        </div>
    </div>
</footer>

<!-- Script pour la newsletter -->
@push('scripts')
<script>
    document.getElementById('newsletter-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('newsletter-message');
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageDiv.innerHTML = '<div class="text-green-500">' + data.message + '</div>';
                messageDiv.classList.remove('hidden');
                this.reset();
                setTimeout(() => messageDiv.classList.add('hidden'), 5000);
            } else {
                messageDiv.innerHTML = '<div class="text-red-500">' + (data.message || 'Une erreur est survenue') + '</div>';
                messageDiv.classList.remove('hidden');
            }
        } catch (error) {
            messageDiv.innerHTML = '<div class="text-red-500">Erreur de connexion</div>';
            messageDiv.classList.remove('hidden');
        }
    });
</script>
@endpush