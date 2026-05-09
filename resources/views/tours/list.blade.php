@extends('layouts.master')

@section('title', 'Nos Tours - Barayoro')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Nos Tours</h1>
            <p class="text-xl text-gray-600">Découvrez nos destinations et vivez des expériences uniques</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($tours as $tour)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                @if($tour->image)
                <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 gradient-bg flex items-center justify-center">
                    <i class="las la-map-marked-alt text-5xl text-white opacity-50"></i>
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-orange-custom font-semibold">{{ $tour->category }}</span>
                        <span class="text-sm text-gray-500">{{ $tour->duration_days }} jours</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $tour->title }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($tour->description, 100) }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-bold text-orange-custom">{{ number_format($tour->price, 0, ',', ' ') }}€</span>
                            <span class="text-gray-500">/pers</span>
                        </div>
                        <a href="{{ route('tours.details', $tour->id) }}" class="px-4 py-2 btn-outline rounded-lg">Découvrir</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="las la-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucun tour disponible pour le moment.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $tours->links() }}
        </div>
    </div>
</div>
@endsection