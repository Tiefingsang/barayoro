@extends('layouts.app')

@section('title', 'Paiement Orange Money en cours')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-spinner fa-pulse text-4xl text-orange-500"></i>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Paiement en cours</h1>
                <p class="text-gray-500 mb-6">
                    Veuillez vérifier votre téléphone Orange Money et confirmer le paiement.
                </p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Montant :</span>
                        <span class="font-bold text-gray-800">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Référence :</span>
                        <span class="font-mono text-sm">{{ $transaction->reference }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut :</span>
                        <span class="text-orange-500 font-semibold">En attente...</span>
                    </div>
                </div>
                
                <!-- Auto-check du statut -->
                <div class="text-center" x-data="{ 
                    checkStatus() {
                        fetch('{{ route('payments.orange-money.status', ['reference' => $transaction->reference]) }}')
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    window.location.href = '{{ route('payments.orange-money.success', ['reference' => $transaction->reference]) }}';
                                }
                            });
                    },
                    init() {
                        setInterval(() => this.checkStatus(), 3000);
                    }
                }" x-init="init">
                    <p class="text-sm text-gray-400 mb-4">
                        <i class="fas fa-info-circle"></i> Cette page se met à jour automatiquement
                    </p>
                </div>
                
                <a href="{{ route('invoices.index') }}" class="inline-block px-6 py-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i> Retour aux factures
                </a>
            </div>
        </div>
    </div>
</div>
@endsection