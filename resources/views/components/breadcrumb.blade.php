<!-- resources/views/components/breadcrumb.blade.php -->
@props([
    'items' => [],
    'separator' => '<i class="fas fa-chevron-right text-xs"></i>',
    'class' => ''
])

<nav class="bg-gray-50 border-b border-gray-100 {{ $class }}" aria-label="Fil d'Ariane">
    <div class="container mx-auto px-4 md:px-6 py-3">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-orange-custom transition flex items-center gap-1">
                    <i class="fas fa-home text-xs"></i>
                    <span>Accueil</span>
                </a>
            </li>
            
            @foreach($items as $index => $item)
                <li class="flex items-center gap-2">
                    <span class="text-gray-400">{!! $separator !!}</span>
                    
                    @if(isset($item['url']) && $index < count($items) - 1)
                        <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-orange-custom transition">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-orange-custom font-medium">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>