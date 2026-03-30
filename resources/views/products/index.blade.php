@extends('layouts.app')

@section('title', 'Gestion des produits')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des produits</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="#">
                  <i class="las la-box shrink-0"></i>
                  <span>Produits</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-list shrink-0"></i>
                  <span>Liste</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- product list -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des produits</h4>
            <div class="flex flex-wrap items-center gap-4">
              <a href="{{ route('products.create') }}" class="btn-primary-outlined py-2">
                <i class="las la-plus-circle text-sm"></i> Nouveau produit
              </a>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" id="sort" class="nc-select n20" onchange="window.location.href = this.value">
                  <option value="{{ route('products.index', ['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récents</option>
                  <option value="{{ route('products.index', ['sort' => 'created_at', 'order' => 'asc']) }}" {{ request('sort') == 'created_at' && request('order') == 'asc' ? 'selected' : '' }}>Plus anciens</option>
                  <option value="{{ route('products.index', ['sort' => 'name', 'order' => 'asc']) }}" {{ request('sort') == 'name' && request('order') == 'asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                  <option value="{{ route('products.index', ['sort' => 'name', 'order' => 'desc']) }}" {{ request('sort') == 'name' && request('order') == 'desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                  <option value="{{ route('products.index', ['sort' => 'selling_price', 'order' => 'asc']) }}" {{ request('sort') == 'selling_price' && request('order') == 'asc' ? 'selected' : '' }}>Prix (croissant)</option>
                  <option value="{{ route('products.index', ['sort' => 'selling_price', 'order' => 'desc']) }}" {{ request('sort') == 'selling_price' && request('order') == 'desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtres -->
          <div class="flex justify-between items-center gap-4 flex-wrap mb-4 xl:mb-6">
            <div class="flex gap-4 xxl:gap-6 flex-wrap">

              <!-- Filtre par catégorie -->
              <div x-data="{open:false, selected: '{{ request('category') }}', items: @json(['Toutes', ...$categories->toArray()]) }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected ? selected : 'Catégorie'"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <input type="hidden" id="category_filter" name="category" :value="selected == 'Toutes' ? '' : selected">
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <template x-for="item in items">
                    <div @click="selected=item; open=false; window.location.href = '{{ route('products.index') }}?category=' + (item == 'Toutes' ? '' : item)"
                         :class="selected===item?'bg-primary-300 text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'"
                         class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300" x-text="item"></div>
                  </template>
                </div>
              </div>

              <!-- Filtre par statut -->
              <div x-data="{open:false, selected: '{{ request('status') == 'active' ? 'Actif' : (request('status') == 'inactive' ? 'Inactif' : 'Tous') }}', items: ['Tous', 'Actif', 'Inactif'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected ? selected : 'Statut'"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <template x-for="item in items">
                    <div @click="selected=item; open=false; window.location.href = '{{ route('products.index') }}?status=' + (item == 'Tous' ? '' : (item == 'Actif' ? 'active' : 'inactive'))"
                         :class="selected===item?'bg-primary-300 text-neutral-0':'hover:bg-primary-50 dark:hover:bg-neutral-903'"
                         class="cursor-pointer rounded-md text-sm px-4 py-2 duration-300" x-text="item"></div>
                  </template>
                </div>
              </div>

              <!-- Recherche -->
              <form method="GET" action="{{ route('products.index') }}" class="flex items-center rounded-full border border-neutral-30 dark:border-neutral-500 max-w-[250px] w-full py-2 px-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full bg-transparent" />
                <button type="submit"><i class="las la-search text-lg"></i></button>
              </form>
            </div>
          </div>

          <!-- Tableau des produits -->
          <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <thead class="text-left">
                <tr class="bg-neutral-20 dark:bg-neutral-903">
                  <th class="px-6 py-4">Code</th>
                  <th class="px-6 py-4">Produit</th>
                  <th class="px-6 py-4">Catégorie</th>
                  <th class="px-6 py-4">Stock</th>
                  <th class="px-6 py-4">Prix de vente</th>
                  <th class="px-6 py-4">Statut</th>
                  <th class="px-6 py-4">Actions</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($products as $product)
                <tr class="border-b border-neutral-30 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:hover:bg-neutral-903">
                  <td class="px-6 py-3">
                    <span class="text-sm font-mono">{{ $product->code }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-3">
                      @if($product->images && count($product->images) > 0)
                        <img src="{{ $product->images[0]['url'] ?? Storage::url($product->images[0]['path']) }}" width="50" class="rounded-lg object-cover" alt="{{ $product->name }}" />
                      @else
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                          <i class="las la-box text-2xl text-gray-400"></i>
                        </div>
                      @endif
                      <div>
                        <p class="font-medium mb-1">{{ $product->name }}</p>
                        <span class="text-xs text-gray-500">{{ $product->sku ?? 'Pas de SKU' }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $product->category ?? '-' }}</span>
                  </td>
                  <td class="px-6 py-3">
                    @if($product->isLowStock())
                      <span class="text-orange-600 font-semibold">{{ $product->stock_quantity }}</span>
                    @elseif($product->isOutOfStock())
                      <span class="text-red-600 font-semibold">0</span>
                    @else
                      <span class="text-green-600">{{ $product->stock_quantity }}</span>
                    @endif
                  </td>
                  <td class="px-6 py-3">
                    <span class="font-medium">{{ number_format($product->selling_price, 2) }} €</span>
                  </td>
                  <td class="px-6 py-3">
                    @if($product->is_active)
                      <span class="py-1 px-3 rounded-full text-xs bg-green-100 text-green-800">Actif</span>
                    @else
                      <span class="py-1 px-3 rounded-full text-xs bg-red-100 text-red-800">Inactif</span>
                    @endif
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      <a href="{{ route('products.edit', $product) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce produit ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                          <i class="las la-trash text-xl"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="las la-box-open text-4xl mb-2 block"></i>
                    Aucun produit trouvé.
                    <div class="mt-2">
                      <a href="{{ route('products.create') }}" class="text-primary-300 hover:underline">Créer votre premier produit</a>
                    </div>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex items-center gap-5 justify-center flex-col md:flex-row md:justify-between whitespace-nowrap">
            <div class="flex gap-4 items-center">
              <p>Lignes par page :</p>
              <select name="per_page" class="bg-transparent dark:bg-neutral-904 border rounded-lg px-3 py-1" onchange="window.location.href = this.value">
                <option value="{{ route('products.index', array_merge(request()->query(), ['per_page' => 10])) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['per_page' => 100])) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} sur {{ $products->total() }}</p>
              @if($products->onFirstPage())
                <button disabled class="opacity-50"><i class="las la-angle-left text-xl"></i></button>
              @else
                <a href="{{ $products->previousPageUrl() }}"><i class="las la-angle-left text-xl"></i></a>
              @endif
              @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"><i class="las la-angle-right text-xl"></i></a>
              @else
                <button disabled class="opacity-50"><i class="las la-angle-right text-xl"></i></button>
              @endif
            </div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
    // Mettre à jour les filtres quand la page change
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            window.location.href = this.value;
        });
    });
</script>
@endpush
