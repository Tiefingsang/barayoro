@if($activities->count() > 0)
    <div class="space-y-4">
        @foreach($activities as $activity)
            <div class="flex items-start gap-3 pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $activity->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-center text-gray-500 dark:text-gray-400 py-8">
        Aucune activité récente
    </p>
@endif