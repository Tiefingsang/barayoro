@extends('layouts.app')

@section('title', 'Détails du paiement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Paiement #{{ $payment->payment_number }}
            </h1>
            <div>
                <a href="{{ route('payments.index') }}" class="text-orange-500 hover:text-orange-600">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- En-tête avec statut -->
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($payment->status === 'completed') bg-green-100 text-green-700
                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($payment->status === 'failed') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            
            <!-- Détails -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Montant</p>
                        <p class="text-2xl font-bold text-orange-600">
                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Méthode</p>
                        <p class="font-semibold">
                            @if($payment->method === 'orange_money')
                                <i class="fas fa-mobile-alt text-orange-500 mr-2"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Date de paiement</p>
                        <p class="font-semibold">{{ $payment->payment_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Référence transaction</p>
                        <p class="font-mono text-sm">{{ $payment->transaction_id ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($payment->mobile_number)
                <div>
                    <p class="text-sm text-gray-500">Numéro mobile</p>
                    <p class="font-semibold">{{ $payment->mobile_number }}</p>
                </div>
                @endif
                
                @if($payment->notes)
                <div>
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
                @endif
                
                @if($payment->invoice)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-2">Facture associée</p>
                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-orange-500 hover:underline">
                        Facture #{{ $payment->invoice->invoice_number }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection