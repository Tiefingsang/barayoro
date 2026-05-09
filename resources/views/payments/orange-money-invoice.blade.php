@extends('layouts.app')

@section('title', 'Paiement Orange Money - Facture')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-8 text-white text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                    <i class="lab la-orange text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Orange Money</h2>
                <p class="text-orange-100">Paiement de la facture #{{ $invoice->invoice_number }}</p>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Facture</span>
                            <span class="font-medium">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Client</span>
                            <span class="font-medium">{{ $invoice->client->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Montant à payer</span>
                            <span class="text-2xl font-bold text-orange-600">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('payments.orange-money.initiate') }}">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro Orange Money
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" 
                                   name="phone_number" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="77 123 45 67"
                                   required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: 77 123 45 67</p>
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-orange-50 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-orange-500 mt-0.5"></i>
                            <div class="text-sm text-gray-600">
                                <p>Vous recevrez une demande de paiement sur votre téléphone Orange Money.</p>
                                <p class="mt-2">Suivez les instructions pour confirmer le paiement.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                        <i class="fab fa-orange mr-2"></i>
                        Payer {{ number_format($amount, 0, ',', ' ') }} FCFA
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-500 hover:text-gray-700 text-sm">
                        ← Retour à la facture
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection