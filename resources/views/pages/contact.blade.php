{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.master')

@section('title', 'Contact - Barayoro')
@section('description', 'Contactez notre équipe pour toute question, demande d\'information ou support.')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Contact']
    ]" />

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Contact Info -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        Contactez-nous
                    </h1>
                    <p class="text-gray-600 mb-8 text-lg">
                        Une question ? Un projet ? N'hésitez pas à nous contacter. Notre équipe vous répond dans les plus brefs délais.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Adresse</h3>
                                <p class="text-gray-600">Bamako, Mali</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                <p class="text-gray-600">masadigitale@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Téléphone</h3>
                                <p class="text-gray-600">+223 92 51 64 05</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Horaires</h3>
                                <p class="text-gray-600">Lundi - Vendredi: 8h - 18h</p>
                                <p class="text-gray-600">Samedi: 9h - 13h</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Links -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-gray-800 mb-3">Suivez-nous</h3>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-orange-custom hover:text-white transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-orange-custom hover:text-white transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-orange-custom hover:text-white transition">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-orange-custom hover:text-white transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Envoyez-nous un message</h2>
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                  
<form 
    action="{{ route('contact.send') }}" 
    method="POST"
    autocomplete="off"
    id="contactForm"
>
    @csrf

    {{-- Honeypot anti-spam --}}
    <div class="hidden">
        <input 
            type="text" 
            name="website" 
            tabindex="-1" 
            autocomplete="off"
        >
    </div>

    {{-- Timestamp anti-bot --}}
    <input type="hidden" name="form_time" value="{{ time() }}">

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">
            Nom complet *
        </label>

        <input 
            type="text" 
            name="name"
            maxlength="100"
            minlength="2"
            value="{{ old('name') }}"
            required
            autocomplete="name"
            spellcheck="false"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom focus:border-orange-custom"
        >
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">
            Email *
        </label>

        <input 
            type="email"
            name="email"
            maxlength="150"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            spellcheck="false"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom focus:border-orange-custom"
        >
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">
            Téléphone
        </label>

        <input 
            type="tel"
            name="phone"
            maxlength="20"
            value="{{ old('phone') }}"
            autocomplete="tel"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom focus:border-orange-custom"
        >
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 mb-2">
            Message *
        </label>

        <textarea
            name="message"
            rows="5"
            minlength="10"
            maxlength="5000"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-custom focus:border-orange-custom"
        >{{ old('message') }}</textarea>
    </div>

    <button 
        type="submit"
        id="submitBtn"
        class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
    >
        Envoyer le message
        <i class="fas fa-paper-plane ml-2"></i>
    </button>
</form>

<script>
document.getElementById('contactForm').addEventListener('submit', function () {

    const btn = document.getElementById('submitBtn');

    btn.disabled = true;

    btn.innerHTML = 'Envoi en cours...';
});
</script>


                </div>
            </div>
        </div>
    </section>
@endsection