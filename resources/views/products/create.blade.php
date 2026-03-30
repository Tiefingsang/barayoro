@extends('layouts.app')

@section('title', 'Créer un produit')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Créer un produit</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('products.index') }}">
                  <i class="las la-box shrink-0"></i>
                  <span>Produits</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-plus-circle shrink-0"></i>
                  <span>Créer</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Formulaire de création -->
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="white-box">
              <h4 class="bb-dashed-n30">Créer un nouveau produit</h4>
              <div class="flex flex-col gap-4 xxl:gap-6">

                <!-- Détails -->
                <div>
                  <h5 class="mb-2">Détails du produit</h5>
                  <p class="s-text mb-5 xl:mb-8">Titre, description, image...</p>

                  <!-- Nom du produit -->
                  <div class="mb-4 xl:mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du produit *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           required />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <!-- Description -->
                  <div class="mb-4 xl:mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="description" name="description" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                              style="pointer-events: auto; position: relative; z-index: 10;">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <!-- Images -->
                  <p class="font-medium mb-4">Images</p>
                  <div class="mb-6">
                    <div class="flex flex-col items-center text-center border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 dark:bg-gray-800 hover:border-blue-500 transition">
                      <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      <p class="text-sm text-gray-600 dark:text-gray-400">Cliquez pour sélectionner des images</p>
                      <p class="text-xs text-gray-500 mt-1">Formats acceptés : JPG, PNG, GIF (max 5 Mo)</p>
                      <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" />
                      <button type="button" onclick="document.getElementById('images').click()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                        Parcourir
                      </button>
                    </div>
                    <div id="image-preview" class="grid grid-cols-4 gap-2 mt-4"></div>
                    @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>
                </div>

                <!-- Propriétés -->
                <div>
                  <h5 class="mb-2">Propriétés</h5>
                  <p class="s-text mb-5 xl:mb-8">Fonctions et attributs supplémentaires...</p>
                  <div class="grid grid-cols-2 gap-4 xxl:gap-6 mb-6">

                    <!-- Code produit -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code produit</label>
                      <input type="text" id="code" name="code" value="{{ old('code') }}" readonly
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500" />
                    </div>

                    <!-- SKU -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                      <input type="text" id="sku" name="sku" value="{{ old('sku') }}"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                      @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Code-barres -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="barcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code-barres</label>
                      <input type="text" id="barcode" name="barcode" value="{{ old('barcode') }}"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Quantité en stock -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantité en stock</label>
                      <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                      @error('stock_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Quantité minimale -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="min_stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock minimum (alerte)</label>
                      <input type="number" id="min_stock_quantity" name="min_stock_quantity" value="{{ old('min_stock_quantity', 0) }}" min="0"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Quantité maximale -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="max_stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock maximum</label>
                      <input type="number" id="max_stock_quantity" name="max_stock_quantity" value="{{ old('max_stock_quantity') }}" min="0"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Catégorie -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catégorie</label>
                      <input type="text" id="category" name="category" value="{{ old('category') }}"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Marque -->
                    <div class="col-span-2 md:col-span-1">
                      <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marque</label>
                      <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                             class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Type de produit -->
                    <div class="col-span-2 md:col-span-1">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                      <select name="type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="product" {{ old('type', 'product') == 'product' ? 'selected' : '' }}>Produit</option>
                        <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
                      </select>
                    </div>

                    <!-- Unité -->
                    <div class="col-span-2 md:col-span-1">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unité</label>
                      <select name="unit" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="piece" {{ old('unit', 'piece') == 'piece' ? 'selected' : '' }}>Pièce</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogramme</option>
                        <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Mètre</option>
                        <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Litre</option>
                        <option value="hour" {{ old('unit') == 'hour' ? 'selected' : '' }}>Heure</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Prix -->
                <div>
                  <h5 class="mb-2">Prix</h5>
                  <p class="s-text mb-5 xl:mb-8">Informations de tarification...</p>
                  <div x-data="{ includeTax: false }" class="grid grid-cols-2 gap-4 xxl:gap-6 mb-6">

                    <!-- Prix d'achat -->
                    <div class="col-span-2 md:col-span-1">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix d'achat</label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                        <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                      </div>
                    </div>

                    <!-- Prix de vente -->
                    <div class="col-span-2 md:col-span-1">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix de vente *</label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                        <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price') }}" required
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                      </div>
                      @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Taxe incluse -->
                    <div class="col-span-2">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input x-model="includeTax" type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                        <span class="text-gray-700 dark:text-gray-300">Le prix inclut la taxe</span>
                      </label>
                    </div>

                    <!-- Taux de taxe -->
                    <div x-show="!includeTax" class="col-span-2">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Taxe (%)</label>
                      <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                        <input type="number" step="0.01" name="tax_rate" value="{{ old('tax_rate', 0) }}"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                      </div>
                    </div>

                    <!-- Options supplémentaires -->
                    <div class="col-span-2 mt-4 space-y-3">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_taxable" value="1" {{ old('is_taxable', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                        <span class="text-gray-700 dark:text-gray-300">Produit taxable</span>
                      </label>

                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                        <span class="text-gray-700 dark:text-gray-300">Publier (actif)</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-4 mt-4">
                  <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                    <i class="las la-save mr-2"></i> Créer le produit
                  </button>
                  <a href="{{ route('products.index') }}" class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition dark:border-gray-600 dark:hover:bg-gray-700">
                    <i class="las la-times mr-2"></i> Annuler
                  </a>
                </div>
              </div>
            </div>
        </form>
      </div>
@endsection

@push('scripts')
<script>
    // Prévisualisation des images
    document.getElementById('images')?.addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        const files = Array.from(e.target.files);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('w-24', 'h-24', 'object-cover', 'rounded-lg', 'border', 'border-gray-300');
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
