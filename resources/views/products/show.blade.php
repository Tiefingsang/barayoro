@extends('layouts.app')

@section('title', 'Détails du produit')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails du produit</h2>
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
                  <i class="las la-eye shrink-0"></i>
                  <span>Détails</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Détails du produit -->
        <div class="white-box">
          <div class="grid grid-cols-12 gap-6">

            <!-- Colonne gauche - Images -->
            <div class="col-span-12 lg:col-span-4">
              <div class="space-y-4">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden">
                  @if($product->images && count($product->images) > 0)
                    <img id="main-image" src="{{ $product->images[0]['url'] ?? Storage::url($product->images[0]['path']) }}"
                         alt="{{ $product->name }}" class="w-full h-64 object-cover">
                  @else
                    <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <i class="las la-box-open text-6xl text-gray-400"></i>
                    </div>
                  @endif
                </div>

                @if($product->images && count($product->images) > 1)
                <div class="grid grid-cols-4 gap-2">
                  @foreach($product->images as $index => $image)
                    <div class="cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-primary-300 transition"
                         onclick="document.getElementById('main-image').src = '{{ $image['url'] ?? Storage::url($image['path']) }}'">
                      <img src="{{ $image['url'] ?? Storage::url($image['path']) }}"
                           alt="{{ $product->name }}" class="w-full h-20 object-cover">
                    </div>
                  @endforeach
                </div>
                @endif
              </div>
            </div>

            <!-- Colonne droite - Informations -->
            <div class="col-span-12 lg:col-span-8">
              <div class="flex justify-between items-start">
                <div>
                  <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h1>
                  <div class="flex items-center gap-3 mb-4">
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800">
                      {{ $product->code }}
                    </span>
                    @if($product->sku)
                      <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-800">
                        SKU: {{ $product->sku }}
                      </span>
                    @endif
                  </div>
                </div>
                <div>
                  @if($product->is_active)
                    <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">Actif</span>
                  @else
                    <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">Inactif</span>
                  @endif
                </div>
              </div>

              <!-- Prix -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl">
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Prix de vente</p>
                  <p class="text-2xl font-bold text-green-600">{{ number_format($product->selling_price, 0, ',', ' ') }} FCFA</p>
                </div>
                @if($product->purchase_price)
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl">
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Prix d'achat</p>
                  <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ number_format($product->purchase_price, 0, ',', ' ') }} FCFA</p>
                </div>
                @endif
              </div>

              <!-- Description -->
              @if($product->description)
              <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Description</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $product->description }}</p>
              </div>
              @endif

              <!-- Informations détaillées -->
              <div class="grid grid-cols-2 gap-4">
                <div class="flex items-start gap-3">
                  <i class="las la-tag text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Catégorie</p>
                    <p class="font-medium">{{ $product->category ?? '-' }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-building text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Marque</p>
                    <p class="font-medium">{{ $product->brand ?? '-' }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-cube text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Type</p>
                    <p class="font-medium">{{ $product->type == 'product' ? 'Produit' : 'Service' }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-ruler-combined text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Unité</p>
                    <p class="font-medium">
                      @if($product->unit == 'piece') Pièce
                      @elseif($product->unit == 'kg') Kilogramme
                      @elseif($product->unit == 'meter') Mètre
                      @elseif($product->unit == 'liter') Litre
                      @elseif($product->unit == 'hour') Heure
                      @else {{ $product->unit }}
                      @endif
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-boxes text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Stock</p>
                    <p class="font-medium @if($product->isLowStock()) text-orange-600 @elseif($product->isOutOfStock()) text-red-600 @else text-green-600 @endif">
                      {{ number_format($product->stock_quantity, 0, ',', ' ') }}
                      @if($product->min_stock_quantity > 0)
                        <span class="text-xs text-gray-500">(Min: {{ number_format($product->min_stock_quantity, 0, ',', ' ') }})</span>
                      @endif
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-percent text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Taxe</p>
                    <p class="font-medium">{{ number_format($product->tax_rate, 2, ',', ' ') }}%</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-barcode text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Code-barres</p>
                    <p class="font-medium">{{ $product->barcode ?? '-' }}</p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <i class="las la-calendar-alt text-xl text-gray-400"></i>
                  <div>
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="font-medium">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                  <i class="las la-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition dark:border-gray-600 dark:hover:bg-gray-700">
                  <i class="las la-arrow-left mr-2"></i> Retour
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
