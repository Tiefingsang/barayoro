@extends('layouts.app')

@section('title', 'Modifier la commande')

@section('content')
<div class="p-3 md:p-4 xxl:p-6">
    <div class="white-box">
        <div class="flex justify-between items-center bb-dashed-n30 pb-4">
            <h4>Modifier la commande #{{ $order->order_number }}</h4>
            <a href="{{ route('orders.show', $order) }}" class="btn-secondary-outlined py-2 px-4">
                <i class="las la-arrow-left"></i> Retour
            </a>
        </div>

        <form method="POST" action="{{ route('orders.update', $order) }}" id="orderForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4 lg:gap-6">
                <div class="col-span-12 lg:col-span-8">

                    <!-- Informations client -->
                    <div class="mb-6">
                        <h5 class="font-semibold mb-4">Informations client</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium mb-2">Client *</label>
                                <select name="client_id" class="w-full border rounded-lg px-4 py-2" required>
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $order->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} - {{ $client->email ?? $client->phone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Type et statut -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Type de commande</label>
                            <select name="type" class="w-full border rounded-lg px-4 py-2" {{ $order->invoice_id ? 'disabled' : '' }}>
                                <option value="order" {{ $order->type == 'order' ? 'selected' : '' }}>Commande</option>
                                <option value="estimate" {{ $order->type == 'estimate' ? 'selected' : '' }}>Devis</option>
                            </select>
                            @if($order->invoice_id)
                                <p class="text-xs text-amber-600 mt-1">Le type ne peut pas être modifié car une facture a été générée.</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Statut *</label>
                            <select name="status" class="w-full border rounded-lg px-4 py-2" required>
                                <option value="draft" {{ $order->status == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ En attente</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Confirmée</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ En traitement</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Expédiée</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>📦 Livrée</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Date de commande *</label>
                            <input type="date" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}" class="w-full border rounded-lg px-4 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Date de livraison estimée</label>
                            <input type="date" name="estimated_delivery_date" value="{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('Y-m-d') : '' }}" class="w-full border rounded-lg px-4 py-2">
                        </div>
                    </div>

                    <!-- Articles -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-semibold">Articles</h5>
                            <button type="button" onclick="addItem()" class="text-primary-300 hover:text-primary-400 text-sm">
                                <i class="las la-plus-circle"></i> Ajouter un article
                            </button>
                        </div>

                        <div id="items-container">
                            @foreach($order->items as $index => $item)
                            <div class="item-row grid grid-cols-12 gap-2 mb-3" data-index="{{ $index }}">
                                <div class="col-span-12 md:col-span-5">
                                    <select name="items[{{ $index }}][product_id]" class="w-full border rounded-lg px-3 py-2" onchange="updateProductPrice(this, {{ $index }})">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-tax="{{ $product->tax_rate ?? 0 }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - {{ number_format($product->selling_price, 0) }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-3 md:col-span-2">
                                    <input type="number" name="items[{{ $index }}][quantity]" class="w-full border rounded-lg px-3 py-2" placeholder="Qté" value="{{ $item->quantity }}" min="1" onchange="updateItemTotal({{ $index }})" required>
                                </div>
                                <div class="col-span-3 md:col-span-2">
                                    <input type="number" name="items[{{ $index }}][unit_price]" class="unit-price w-full border rounded-lg px-3 py-2" placeholder="Prix unitaire" value="{{ $item->unit_price }}" step="1" onchange="updateItemTotal({{ $index }})" required>
                                </div>
                                <div class="col-span-3 md:col-span-2">
                                    <span class="item-total block text-right font-semibold py-2">{{ number_format($item->total, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="col-span-1 md:col-span-1">
                                    <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                                        <i class="las la-trash text-xl"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="items[{{ $index }}][tax_rate]" class="tax-rate" value="{{ $item->tax_rate }}">
                                <input type="hidden" name="items[{{ $index }}][discount_amount]" class="discount-amount" value="{{ $item->discount_amount }}">
                                <input type="hidden" name="items[{{ $index }}][notes]" class="item-notes" value="{{ $item->notes }}">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-semibold mb-4">Résumé</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Sous-total:</span>
                                <span id="subtotal">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>TVA:</span>
                                <span id="tax_total">{{ number_format($order->tax_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Remise:</span>
                                <span id="discount_total">{{ number_format($order->discount_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Frais de livraison:</span>
                                <input type="number" name="shipping_cost" id="shipping_cost" value="{{ $order->shipping_cost }}" class="w-32 border rounded px-2 py-1 text-right" onchange="updateTotals()">
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                <span>Total:</span>
                                <span id="total">{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">
                            <i class="las la-save"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('orders.show', $order) }}" class="btn-secondary px-6 py-2 rounded-lg">Annuler</a>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Numéro de commande</label>
                            <input type="text" value="{{ $order->order_number }}" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly disabled>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Date de création</label>
                            <input type="text" value="{{ $order->created_at->format('d/m/Y H:i') }}" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly disabled>
                        </div>

                        @if($order->invoice_id)
                        <div>
                            <label class="block text-sm font-medium mb-2">Facture associée</label>
                            <a href="{{ route('invoices.show', $order->invoice_id) }}" class="text-primary-300 hover:underline block">
                                Voir la facture #{{ $order->invoice->invoice_number ?? 'N/A' }}
                            </a>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-2">Notes</label>
                            <textarea name="notes" rows="4" class="w-full border rounded-lg px-4 py-2" placeholder="Notes supplémentaires...">{{ $order->notes }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Adresse de livraison</label>
                            <textarea name="shipping_address" rows="3" class="w-full border rounded-lg px-4 py-2" placeholder="Adresse de livraison...">{{ $order->shipping_address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = {{ $order->items->count() }};

function addItem() {
    const container = document.getElementById('items-container');
    const template = `
        <div class="item-row grid grid-cols-12 gap-2 mb-3" data-index="${itemIndex}">
            <div class="col-span-12 md:col-span-5">
                <select name="items[${itemIndex}][product_id]" class="w-full border rounded-lg px-3 py-2" onchange="updateProductPrice(this, ${itemIndex})">
                    <option value="">Sélectionner un produit</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-tax="{{ $product->tax_rate ?? 0 }}">
                            {{ $product->name }} - {{ number_format($product->selling_price, 0) }} FCFA
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3 md:col-span-2">
                <input type="number" name="items[${itemIndex}][quantity]" class="w-full border rounded-lg px-3 py-2" placeholder="Qté" value="1" min="1" onchange="updateItemTotal(${itemIndex})" required>
            </div>
            <div class="col-span-3 md:col-span-2">
                <input type="number" name="items[${itemIndex}][unit_price]" class="unit-price w-full border rounded-lg px-3 py-2" placeholder="Prix unitaire" step="1" onchange="updateItemTotal(${itemIndex})" required>
            </div>
            <div class="col-span-3 md:col-span-2">
                <span class="item-total block text-right font-semibold py-2">0 FCFA</span>
            </div>
            <div class="col-span-1 md:col-span-1">
                <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                    <i class="las la-trash text-xl"></i>
                </button>
            </div>
            <input type="hidden" name="items[${itemIndex}][tax_rate]" class="tax-rate" value="0">
            <input type="hidden" name="items[${itemIndex}][discount_amount]" class="discount-amount" value="0">
            <input type="hidden" name="items[${itemIndex}][notes]" class="item-notes" value="">
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    itemIndex++;
}

function removeItem(button) {
    const container = document.getElementById('items-container');
    if (container.children.length > 1) {
        button.closest('.item-row').remove();
        updateTotals();
    }
}

function updateProductPrice(select, index) {
    const selected = select.options[select.selectedIndex];
    const price = selected.getAttribute('data-price');
    const tax = selected.getAttribute('data-tax');
    const row = select.closest('.item-row');

    row.querySelector('.unit-price').value = price;
    row.querySelector('.tax-rate').value = tax;
    updateItemTotal(index);
}

function updateItemTotal(index) {
    const row = document.querySelector(`.item-row[data-index="${index}"]`);
    if (!row) return;

    const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discount = parseFloat(row.querySelector('.discount-amount').value) || 0;
    const taxRate = parseFloat(row.querySelector('.tax-rate').value) || 0;

    const subtotal = quantity * unitPrice;
    const discountAmount = subtotal * (discount / 100);
    const afterDiscount = subtotal - discountAmount;
    const taxAmount = afterDiscount * (taxRate / 100);
    const total = afterDiscount + taxAmount;

    row.querySelector('.item-total').innerHTML = total.toLocaleString('fr-FR') + ' FCFA';
    updateTotals();
}

function updateTotals() {
    let subtotal = 0;
    let taxTotal = 0;
    let discountTotal = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discount = parseFloat(row.querySelector('.discount-amount').value) || 0;
        const taxRate = parseFloat(row.querySelector('.tax-rate').value) || 0;

        const itemSubtotal = quantity * unitPrice;
        const discountAmount = itemSubtotal * (discount / 100);
        const afterDiscount = itemSubtotal - discountAmount;
        const taxAmount = afterDiscount * (taxRate / 100);

        subtotal += itemSubtotal;
        taxTotal += taxAmount;
        discountTotal += discountAmount;
    });

    const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
    const total = subtotal - discountTotal + taxTotal + shippingCost;

    document.getElementById('subtotal').innerHTML = subtotal.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('tax_total').innerHTML = taxTotal.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('discount_total').innerHTML = discountTotal.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('total').innerHTML = total.toLocaleString('fr-FR') + ' FCFA';
}

// Initialiser les totaux au chargement
document.addEventListener('DOMContentLoaded', function() {
    updateTotals();
});
</script>
@endsection
