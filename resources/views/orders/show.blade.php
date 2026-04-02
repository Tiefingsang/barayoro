@extends('layouts.app')

@section('title', 'Détails de la commande')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Breadcrumb -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative">
            <h2 class="mb-3 xxxl:mb-5">Commande #{{ $order->order_number }}</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li><a class="flex items-center gap-2" href="{{ route('dashboard') }}"><i class="las la-home"></i><span>Accueil</span></a></li>
                <li class="text-sm text-neutral-100">•</li>
                <li><a class="flex items-center gap-2" href="{{ route('orders.index') }}"><i class="las la-shopping-cart"></i><span>Commandes</span></a></li>
                <li class="text-sm text-neutral-100">•</li>
                <li><a class="flex items-center gap-2 text-primary-300" href="#"><span>#{{ $order->order_number }}</span></a></li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 xxl:gap-6">

        <!-- Informations principales -->
        <div class="col-span-12 lg:col-span-8">
            <div class="white-box">
                <h4 class="bb-dashed-n30">Informations commande</h4>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500">N° Commande</p>
                        <p class="font-mono font-semibold">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date commande</p>
                        <p>{{ $order->order_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut</p>
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut paiement</p>
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->payment_status === 'paid' ? 'green' : ($order->payment_status === 'partial' ? 'yellow' : 'gray') }}-100">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Articles -->
            <div class="white-box mt-4">
                <h4 class="bb-dashed-n30">Articles</h4>
                <div class="overflow-x-auto mt-4">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-neutral-20">
                                <th class="px-4 py-2 text-left">Produit</th>
                                <th class="px-4 py-2 text-right">Qté</th>
                                <th class="px-4 py-2 text-right">Prix unitaire</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->product_name }}</td>
                                <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-2 text-right font-semibold">{{ number_format($item->total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between">
                                <span>Sous-total :</span>
                                <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>TVA :</span>
                                <span>{{ number_format($order->tax_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Frais livraison :</span>
                                <span>{{ number_format($order->shipping_cost, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                <span>Total :</span>
                                <span>{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">

            <!-- Client -->
            <div class="white-box">
                <h4 class="bb-dashed-n30">Client</h4>
                <div class="mt-4">
                    <p class="font-semibold">{{ $order->client->name }}</p>
                    @if($order->client->email)
                        <p class="text-sm">{{ $order->client->email }}</p>
                    @endif
                    @if($order->client->phone)
                        <p class="text-sm">{{ $order->client->phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Historique statut -->
            <div class="white-box mt-4">
                <h4 class="bb-dashed-n30">Historique</h4>
                <div class="space-y-3 mt-4">
                    @foreach($order->histories as $history)
                    <div class="text-sm">
                        <div class="flex justify-between">
                            <span class="font-semibold">{{ $history->status_to }}</span>
                            <span class="text-gray-500">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($history->notes)
                            <p class="text-gray-600 text-xs mt-1">{{ $history->notes }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="white-box mt-4">
                <h4 class="bb-dashed-n30">Actions</h4>
                <div class="space-y-2 mt-4">
                    @if($order->canBeModified())
                    <a href="{{ route('orders.edit', $order) }}" class="btn-primary w-full text-center block py-2">
                        <i class="las la-edit"></i> Modifier
                    </a>
                    @endif

                    @if(!$order->invoice_id)
                    <form action="{{ route('orders.generate-invoice', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary-outlined w-full py-2">
                            <i class="las la-file-invoice"></i> Générer facture
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Supprimer cette commande ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full py-2">
                            <i class="las la-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
