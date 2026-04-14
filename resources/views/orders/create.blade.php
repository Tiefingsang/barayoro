{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nouvelle commande')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">
    <div class="white-box">
        <h4 class="bb-dashed-n30">Créer une nouvelle commande</h4>

        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf

            <div class="grid grid-cols-12 gap-4 lg:gap-6">
                <div class="col-span-12 lg:col-span-8">

                    <!-- Type de client -->
                    <div class="mb-6">
                        <h5 class="font-semibold mb-4">Informations client</h5>
                        <div class="mb-4">
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="client_type" value="existing" checked class="mr-2" onchange="toggleClientType()">
                                Client existant
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="client_type" value="new" class="mr-2" onchange="toggleClientType()">
                                Nouveau client
                            </label>
                        </div>

                        <!-- Client existant -->
                        <div id="existing_client_section">
                            <select name="client_id" class="w-full border rounded-lg px-4 py-2">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} - {{ $client->email ?? $client->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nouveau client -->
                        <div id="new_client_section" style="display: none;">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <input type="text" name="new_client_name" placeholder="Nom complet *" class="w-full border rounded-lg px-4 py-2">
                                </div>
                                <div>
                                    <input type="email" name="new_client_email" placeholder="Email" class="w-full border rounded-lg px-4 py-2">
                                </div>
                                <div>
                                    <input type="tel" name="new_client_phone" placeholder="Téléphone" class="w-full border rounded-lg px-4 py-2">
                                </div>
                                <div class="col-span-2">
                                    <textarea name="new_client_address" placeholder="Adresse" rows="2" class="w-full border rounded-lg px-4 py-2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                    <!-- Type de commande -->
<div class="mb-6">
    <label class="block text-sm font-medium mb-2">Type de commande *</label>
    <select name="type" required class="w-full border rounded-lg px-4 py-2">
        <option value="order" {{ old('type') == 'order' ? 'selected' : '' }}>Commande (génère une facture)</option>
        <option value="estimate" {{ old('type') == 'estimate' ? 'selected' : '' }}>Devis (sans facture)</option>
    </select>
    <p class="text-xs text-gray-500 mt-1">Les commandes génèrent automatiquement une facture.</p>
</div>

<!-- Statut de la commande - NOUVEAU -->
<div class="mb-6">
    <label class="block text-sm font-medium mb-2">Statut initial *</label>
    <select name="status" required class="w-full border rounded-lg px-4 py-2">
        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ En attente</option>
        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>✅ Confirmée</option>
        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>⚙️ En traitement</option>
        <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>🚚 Expédiée</option>
        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>📦 Livrée</option>
    </select>
    <p class="text-xs text-gray-500 mt-1">Définissez le statut initial de la commande.</p>
</div>

                    <!-- Articles -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-semibold">Articles</h5>
                            <button type="button" onclick="addItem()" class="text-primary-300 hover:text-primary-400">
                                <i class="las la-plus-circle"></i> Ajouter un article
                            </button>
                        </div>

                        <div id="items-container">
                            <!-- Template d'article -->
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-semibold mb-4">Résumé</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Sous-total:</span>
                                <span id="subtotal">0 FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>TVA:</span>
                                <span id="tax_total">0 FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Remise:</span>
                                <span id="discount_total">0 FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Frais de livraison:</span>
                                <input type="number" name="shipping_cost" id="shipping_cost" value="0" class="w-32 border rounded px-2 py-1 text-right" onchange="updateTotals()">
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                <span>Total:</span>
                                <span id="total">0 FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Créer la commande</button>
                        <a href="{{ route('orders.index') }}" class="btn-secondary px-6 py-2 rounded-lg">Annuler</a>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Date de commande *</label>
                            <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Date de livraison estimée</label>
                            <input type="date" name="estimated_delivery_date" value="{{ old('estimated_delivery_date') }}" class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleClientType() {
    const type = document.querySelector('input[name="client_type"]:checked').value;
    const existingSection = document.getElementById('existing_client_section');
    const newSection = document.getElementById('new_client_section');

    if (type === 'existing') {
        existingSection.style.display = 'block';
        newSection.style.display = 'none';
    } else {
        existingSection.style.display = 'none';
        newSection.style.display = 'block';
    }
}

let itemIndex = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const template = `
        <div class="item-row grid grid-cols-12 gap-2 mb-3" data-index="${itemIndex}">
            <div class="col-span-12 md:col-span-5">
                <select name="items[${itemIndex}][product_id]" class="w-full border rounded-lg px-3 py-2" onchange="updateProductPrice(this, ${itemIndex})" required>
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

// Initialiser avec un article
addItem();
</script>
@endsection
