@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messagerie</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Discutez avec vos collègues</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Liste des conversations -->
                    <div class="lg:col-span-1 border-r border-gray-200 dark:border-gray-700">
                        <div class="mb-4">
                            <input type="text" 
                                   placeholder="Rechercher..." 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2">
                        </div>
                        
                        <div class="space-y-3">
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p>Fonctionnalité de chat à venir</p>
                                <p class="text-sm mt-2">Cette section est en cours de développement</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Zone de chat -->
                    <div class="lg:col-span-2">
                        <div class="flex flex-col h-[500px]">
                            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    Sélectionnez une conversation pour commencer à discuter
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                <div class="flex gap-2">
                                    <input type="text" 
                                           placeholder="Tapez votre message..." 
                                           disabled
                                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 opacity-50">
                                    <button disabled 
                                            class="bg-primary-500 text-white px-6 py-2 rounded-lg opacity-50 cursor-not-allowed">
                                        Envoyer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection