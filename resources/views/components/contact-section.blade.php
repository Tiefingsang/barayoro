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
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm mb-2">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-2">Téléphone</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-2">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-custom"></textarea>
                    </div>
                    <div id="form-message" class="mb-4 hidden"></div>
                    <button type="submit" class="w-full py-3 gradient-bg text-white rounded-lg btn-primary font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('contact-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('form-message');
        const loading = document.getElementById('loading');
        
        loading.classList.add('active');
        
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
                messageDiv.innerHTML = '<div class="bg-green-500 text-white p-3 rounded-lg">' + data.message + '</div>';
                messageDiv.classList.remove('hidden');
                this.reset();
                setTimeout(() => messageDiv.classList.add('hidden'), 5000);
            } else {
                messageDiv.innerHTML = '<div class="bg-red-500 text-white p-3 rounded-lg">' + (data.message || 'Une erreur est survenue') + '</div>';
                messageDiv.classList.remove('hidden');
            }
        } catch (error) {
            messageDiv.innerHTML = '<div class="bg-red-500 text-white p-3 rounded-lg">Erreur de connexion</div>';
            messageDiv.classList.remove('hidden');
        } finally {
            loading.classList.remove('active');
        }
    });
</script>
@endpush