<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6">
        <p class="text-center text-gray-500 mb-8">Ils nous font confiance</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-60">
            @foreach($partnerLogos as $logo)
                <img src="{{ asset($logo->path) }}" alt="{{ $logo->alt }}" class="h-8 grayscale hover:grayscale-0 transition">
            @endforeach
        </div>
    </div>
</section>