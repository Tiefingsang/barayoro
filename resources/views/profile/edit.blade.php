@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mon Profil</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Gérez vos informations personnelles et préférences</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar - Avatar et infos rapides -->
            <div class="lg:col-span-1">
                @include('profile.partials.sidebar', ['user' => $user, 'stats' => $stats])
            </div>

            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <!-- Informations personnelles -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informations personnelles</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.info-form', ['user' => $user])
                    </div>
                </div>

                <!-- Changer le mot de passe -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Changer le mot de passe</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.password-form')
                    </div>
                </div>

                <!-- Préférences -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Préférences</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.preferences-form', ['user' => $user])
                    </div>
                </div>

                <!-- Activités récentes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Activités récentes</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.activities', ['activities' => $recentActivities])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection