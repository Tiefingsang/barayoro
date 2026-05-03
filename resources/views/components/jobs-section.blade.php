<section id="offres" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-orange-custom font-semibold uppercase tracking-wide">Carrières</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mt-2 mb-4">
                Rejoignez notre équipe
            </h2>
            <p class="text-gray-600">
                Nous recherchons des talents passionnés pour nous aider à révolutionner la gestion d'entreprise
            </p>
        </div>

        @if($companyTypes->isNotEmpty())
        <div class="max-w-3xl mx-auto mb-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <button class="filter-btn px-4 py-2 rounded-full bg-white text-gray-600 hover:bg-orange-custom hover:text-white transition" data-type="all">Toutes</button>
                @foreach($companyTypes as $type)
                <button class="filter-btn px-4 py-2 rounded-full bg-white text-gray-600 hover:bg-orange-custom hover:text-white transition" data-type="{{ $type->slug }}">
                    {{ $type->name }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <div id="jobs-container" class="max-w-3xl mx-auto space-y-4">
            @foreach($jobOffers as $job)
            <div class="job-item bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition border-l-4 border-orange-custom" data-company-type="{{ $job->company->type_slug ?? 'general' }}">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-xl font-bold text-gray-800">{{ $job->title }}</h3>
                            <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-600">{{ $job->contract_type }}</span>
                            @if($job->is_urgent)
                            <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">Urgent</span>
                            @endif
                        </div>
                        <p class="text-gray-500">
                            <i class="fas fa-building mr-1"></i>{{ $job->company->name ?? 'Entreprise' }} | 
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location }} | 
                            <i class="fas fa-calendar mr-1"></i>Publiée le {{ $job->created_at->format('d/m/Y') }}
                        </p>
                        <p class="text-gray-600 mt-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($job->description, 150) }}</p>
                    </div>
                    <a href="{{ route('jobs.details', $job->id) }}" class="px-5 py-2 btn-outline rounded-lg transition font-semibold">
                        Postuler
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($jobOffers->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-briefcase text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune offre d'emploi disponible pour le moment.</p>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-orange-custom', 'text-white');
                b.classList.add('bg-white', 'text-gray-600');
            });
            this.classList.remove('bg-white', 'text-gray-600');
            this.classList.add('bg-orange-custom', 'text-white');
            
            document.querySelectorAll('.job-item').forEach(job => {
                if (type === 'all' || job.dataset.companyType === type) {
                    job.style.display = 'block';
                } else {
                    job.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush