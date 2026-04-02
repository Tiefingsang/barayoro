@extends('layouts.app')

@section('title', 'Modifier la commande')

@section('content')
<div class="p-3 md:p-4 xxl:p-6">
    <div class="white-box">
        <h4>Modifier la commande #{{ $order->order_number }}</h4>

        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">Statut</label>
                <select name="status" class="w-full border rounded-lg px-4 py-2">
                    <option value="draft" {{ $order->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-4 py-2">{{ $order->notes }}</textarea>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="btn-primary px-6 py-2">Enregistrer</button>
                <a href="{{ route('orders.show', $order) }}" class="btn-secondary px-6 py-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
