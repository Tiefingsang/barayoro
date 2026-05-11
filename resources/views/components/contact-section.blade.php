<section id="contact" class="py-20 bg-gray-900 text-white">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Prêt à transformer votre entreprise ?</h2>
                <p class="text-gray-300 mb-6 leading-relaxed">
                    Rejoignez plus de {{ $totalCompaniesCount }} entreprises qui utilisent déjà Barayoro pour gérer leurs opérations quotidiennes.
                </p>
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex -space-x-2">
                        <div class="w-12 h-12 rounded-full bg-orange-custom flex items-center justify-center text-white font-bold">
                            +{{ number_format($totalCompaniesCount/1000, 1) }}k
                        </div>
                    </div>
                    <p class="text-gray-300">Entreprises nous font confiance</p>
                </div>
            </div>
            <div>
                <form id="contact-form" action="{{ route('contact.send') }}" method="POST" class="bg-gray-800 rounded-2xl p-6">
                    @csrf
                    
                    <!-- ⭐ CHAMP HONEYPOT (Anti-bot) - Caché pour les humains, visible pour les bots ⭐ -->
                    <div style="position: absolute; left: -9999px; top: -9999px;" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" name="_website" id="website" tabindex="-1" autocomplete="off" value="">
                    </div>
                    
                    <!-- Temps de chargement du formulaire (détection des soumissions trop rapides) -->
                    <input type="hidden" name="form_load_time" id="form_load_time" value="">
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm mb-2">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required 
                                   maxlength="100"
                                   pattern="[A-Za-zÀ-ÿ\s\-']+"
                                   title="Le nom ne doit contenir que des lettres, espaces et tirets"
                                   class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required 
                                   maxlength="255"
                                   class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-2">Téléphone</label>
                        <input type="tel" name="phone" 
                               maxlength="20"
                               pattern="[0-9+\-\s]+"
                               title="Format: 77123456 ou +22377123456"
                               class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-2">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="4" required 
                                  maxlength="5000"
                                  class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom"></textarea>
                        <p class="text-xs text-gray-400 mt-1">Maximum 5000 caractères</p>
                    </div>
                    
                    <!-- Message de statut -->
                    <div id="form-message" class="mb-4 hidden"></div>
                    
                    <!-- Indicateur de chargement -->
                    <div id="loading" class="loading">
                        <div class="spinner"></div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: rgba(0,0,0,0.7);
        padding: 20px;
        border-radius: 10px;
    }
    .loading.active {
        display: block;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #fff;
        border-top-color: #ff6c00;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

@push('scripts')
<script>
    // Ajouter le temps de chargement du formulaire
    document.getElementById('form_load_time').value = Math.floor(Date.now() / 1000);
    
    document.getElementById('contact-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('form-message');
        const loading = document.getElementById('loading');
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Validation côté client supplémentaire
        const name = this.querySelector('input[name="name"]').value.trim();
        const email = this.querySelector('input[name="email"]').value.trim();
        const message = this.querySelector('textarea[name="message"]').value.trim();
        
        if (name.length < 2) {
            showMessage(messageDiv, 'Veuillez entrer un nom valide (minimum 2 caractères)', 'error');
            return;
        }
        
        if (!isValidEmail(email)) {
            showMessage(messageDiv, 'Veuillez entrer une adresse email valide', 'error');
            return;
        }
        
        if (message.length < 10) {
            showMessage(messageDiv, 'Veuillez entrer un message d\'au moins 10 caractères', 'error');
            return;
        }
        
        // Désactiver le bouton pendant l'envoi
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
        
        if (loading) loading.classList.add('active');
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showMessage(messageDiv, data.message, 'success');
                this.reset();
                // Réinitialiser le temps de chargement
                document.getElementById('form_load_time').value = Math.floor(Date.now() / 1000);
                
                // Effacer le message après 5 secondes
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                }, 5000);
            } else {
                showMessage(messageDiv, data.message || 'Une erreur est survenue. Veuillez réessayer.', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showMessage(messageDiv, 'Erreur de connexion. Veuillez vérifier votre connexion internet et réessayer.', 'error');
        } finally {
            if (loading) loading.classList.remove('active');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Envoyer le message';
        }
    });
    
    function showMessage(container, message, type) {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        container.innerHTML = '<div class="' + bgColor + ' text-white p-3 rounded-lg">' + message + '</div>';
        container.classList.remove('hidden');
        
        // Auto-cacher après 5 secondes
        setTimeout(() => {
            if (container && !container.classList.contains('hidden')) {
                container.classList.add('hidden');
            }
        }, 5000);
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }
    
    // Validation en temps réel du téléphone
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Ne garder que les chiffres, +, espaces et tirets
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });
    }
    
    // Validation en temps réel du nom
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        nameInput.addEventListener('input', function(e) {
            // Ne garder que les lettres, espaces, apostrophes et tirets
            this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s\-\']/g, '');
        });
    }
</script>
@endpush