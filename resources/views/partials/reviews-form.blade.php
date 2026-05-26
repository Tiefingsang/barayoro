{{-- resources/views/partials/reviews-form.blade.php --}}
<div class="bg-gray-50 rounded-2xl p-6 md:p-8 mt-12 border border-gray-100">
    <h3 class="text-2xl font-bold text-gray-800 mb-2">Laissez-nous votre avis</h3>
    <p class="text-sm text-gray-500 mb-6">Votre adresse email ne sera pas publiée. Les champs obligatoires sont indiqués avec *</p>

    @auth
        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="reviewable_type" value="{{ $reviewableType }}">
            <input type="hidden" name="reviewable_id" value="{{ $reviewableId }}">

            <div x-data="{ hoverRating: 0, selectedRating: 0 }">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Votre note *</label>
                <input type="hidden" name="rating" :value="selectedRating" required>
                <div class="flex gap-1 text-2xl">
                    <template x-for="i in 5">
                        <button type="button" 
                                @click="selectedRating = i" 
                                @mouseover="hoverRating = i" 
                                @mouseleave="hoverRating = 0"
                                class="focus:outline-none transition-colors duration-150">
                            <i class="las la-star" 
                               :class="(hoverRating >= i || selectedRating >= i) ? 'text-yellow-400 font-bold' : 'text-gray-300'"></i>
                        </button>
                    </template>
                </div>
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Titre de votre avis *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Ex: Excellent service, Très satisfait..." 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-transparent bg-white">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Votre commentaire * (10 caractères min.)</label>
                <textarea name="content" rows="5" required placeholder="Partagez votre expérience avec notre communauté..." 
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-transparent bg-white">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 gradient-bg text-white font-semibold rounded-xl btn-primary transition shadow-md">
                    Soumettre l'avis
                </button>
            </div>
        </form>
    @else
        <div class="text-center py-6 bg-white rounded-xl border border-dashed border-gray-200">
            <i class="las la-lock text-3xl text-gray-400 mb-2"></i>
            <p class="text-gray-600">Vous devez être connecté pour laisser un avis.</p>
            <a href="{{ route('login') }}" class="text-orange-custom font-semibold hover:underline mt-1 inline-block">
                Se connecter à mon compte
            </a>
        </div>
    @endauth
</div>