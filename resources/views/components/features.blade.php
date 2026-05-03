<section id="fonctionnalites" class="py-20">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-orange-custom font-semibold uppercase tracking-wide">Fonctionnalités</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                {{ $featuresTitle }}
            </h2>
            <p class="text-gray-600">
                {{ $featuresSubtitle }}
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $feature)
            <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mb-4">
                    <i class="{{ $feature->icon }} text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $feature->title }}</h3>
                <p class="text-gray-600">{{ $feature->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>