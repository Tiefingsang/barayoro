@extends('layouts.app')

@section('title', 'Gestion des factures')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Liste des factures</h2>
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
                  <i class="las la-file-invoice shrink-0"></i>
                  <span>Factures</span>
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

        <!-- Liste des factures -->
        <div class="white-box">
          <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des factures</h4>
            <div class="flex flex-wrap items-center gap-4">
              <a href="{{ route('invoices.create') }}" class="btn-primary-outlined py-2">
                <i class="las la-plus-circle text-sm"></i> Nouvelle facture
              </a>
              <div class="flex items-center gap-3">
                <span>Trier par : </span>
                <select name="sort" id="sort" class="nc-select n20" onchange="window.location.href = this.value">
                  <option value="{{ route('invoices.index', ['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récentes</option>
                  <option value="{{ route('invoices.index', ['sort' => 'due_date', 'order' => 'asc']) }}" {{ request('sort') == 'due_date' && request('order') == 'asc' ? 'selected' : '' }}>Échéance proche</option>
                  <option value="{{ route('invoices.index', ['sort' => 'total', 'order' => 'desc']) }}" {{ request('sort') == 'total' && request('order') == 'desc' ? 'selected' : '' }}>Montant (desc)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtres -->
          <div class="flex justify-between items-center gap-4 flex-wrap mb-4 xl:mb-6">
            <div class="flex gap-4 xxl:gap-6 flex-wrap">

              <!-- Filtre par statut -->
              <div x-data="{open:false, selected: '{{ request('status') }}', items: ['', 'draft', 'sent', 'pending', 'paid', 'overdue', 'cancelled'] }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected === 'draft' ? 'Brouillon' : (selected === 'sent' ? 'Envoyée' : (selected === 'pending' ? 'En attente' : (selected === 'paid' ? 'Payée' : (selected === 'overdue' ? 'En retard' : (selected === 'cancelled' ? 'Annulée' : 'Tous')))))"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('invoices.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Tous</div>
                  <div @click="selected='draft'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'draft']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Brouillon</div>
                  <div @click="selected='sent'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'sent']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Envoyée</div>
                  <div @click="selected='pending'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'pending']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">En attente</div>
                  <div @click="selected='paid'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'paid']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Payée</div>
                  <div @click="selected='overdue'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'overdue']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">En retard</div>
                  <div @click="selected='cancelled'; open=false; window.location.href='{{ route('invoices.index', ['status' => 'cancelled']) }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Annulée</div>
                </div>
              </div>

              <!-- Filtre par client -->
              <div x-data="{open:false, selected: '{{ request('client_id') }}', items: @json($clients) }" class="relative">
                <div @click="open=!open" class="flex cursor-pointer items-center justify-between rounded-full border border-neutral-30 dark:border-neutral-500 w-[250px] py-2 px-5">
                  <span x-text="selected ? items.find(i => i.id == selected)?.name || 'Tous les clients' : 'Tous les clients'"></span>
                  <i class="las la-angle-down text-lg"></i>
                </div>
                <div x-show="open" @click.away="open=false" class="absolute left-0 top-full z-10 flex max-h-[200px] w-full flex-col gap-1 overflow-y-auto rounded-lg bg-neutral-0 p-1 shadow-xl dark:bg-neutral-904">
                  <div @click="selected=''; open=false; window.location.href='{{ route('invoices.index') }}'" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50">Tous les clients</div>
                  <template x-for="client in items">
                    <div @click="selected=client.id; open=false; window.location.href='{{ route('invoices.index') }}?client_id=' + client.id" class="cursor-pointer rounded-md text-sm px-4 py-2 hover:bg-primary-50" x-text="client.name"></div>
                  </template>
                </div>
              </div>

              <!-- Recherche -->
              <form method="GET" action="{{ route('invoices.index') }}" class="flex items-center rounded-full border border-neutral-30 dark:border-neutral-500 max-w-[250px] w-full py-2 px-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full bg-transparent" />
                <button type="submit"><i class="las la-search text-lg"></i></button>
              </form>
            </div>
          </div>

          <!-- Tableau des factures -->
          <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <thead class="text-left">
                <tr class="bg-neutral-20 dark:bg-neutral-903">
                  <th class="px-6 py-4">N° Facture</th>
                  <th class="px-6 py-4">Client</th>
                  <th class="px-6 py-4">Date d'émission</th>
                  <th class="px-6 py-4">Échéance</th>
                  <th class="px-6 py-4">Montant</th>
                  <th class="px-6 py-4">Statut</th>
                  <th class="px-6 py-4">Actions</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($invoices as $invoice)
                <tr class="border-b border-neutral-30 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:hover:bg-neutral-903">
                  <td class="px-6 py-3">
                    <span class="font-mono text-sm">{{ $invoice->invoice_number }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <div>
                      <p class="font-medium">{{ $invoice->client->name }}</p>
                      <span class="text-xs text-gray-500">{{ $invoice->client->email ?? 'Pas d\'email' }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm">{{ $invoice->issue_date->format('d/m/Y') }}</span>
                  </td>
                  <td class="px-6 py-3">
                    <span class="text-sm @if($invoice->isOverdue()) text-red-600 font-semibold @endif">
                      {{ $invoice->due_date->format('d/m/Y') }}
                    </span>
                  </td>
                  <td class="px-6 py-3">
                    <span class="font-medium">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
                  </td>
                  <td class="px-6 py-3">
                    @if($invoice->status == 'draft')
                      <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                    @elseif($invoice->status == 'sent')
                      <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Envoyée</span>
                    @elseif($invoice->status == 'pending')
                      <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                    @elseif($invoice->status == 'paid')
                      <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Payée</span>
                    @elseif($invoice->status == 'overdue')
                      <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">En retard</span>
                    @else
                      <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Annulée</span>
                    @endif
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                      <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                        <i class="las la-eye text-xl"></i>
                      </a>
                      @if($invoice->status == 'draft')
                      <a href="{{ route('invoices.edit', $invoice) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                        <i class="las la-pen text-xl"></i>
                      </a>
                      <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette facture ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                          <i class="las la-trash text-xl"></i>
                        </button>
                      </form>
                      @endif
                      <a href="{{ route('invoices.pdf', $invoice) }}" class="text-purple-600 hover:text-purple-800" title="PDF" target="_blank">
                        <i class="las la-file-pdf text-xl"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="las la-file-invoice text-4xl mb-2 block"></i>
                    Aucune facture trouvée.
                    <div class="mt-2">
                      <a href="{{ route('invoices.create') }}" class="text-primary-300 hover:underline">Créer votre première facture</a>
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
                <option value="{{ route('invoices.index', array_merge(request()->query(), ['per_page' => 10])) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ route('invoices.index', array_merge(request()->query(), ['per_page' => 25])) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ route('invoices.index', array_merge(request()->query(), ['per_page' => 50])) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ route('invoices.index', array_merge(request()->query(), ['per_page' => 100])) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
              </select>
            </div>
            <div class="flex items-center gap-4">
              <p>{{ $invoices->firstItem() ?? 0 }}-{{ $invoices->lastItem() ?? 0 }} sur {{ $invoices->total() }}</p>
              @if($invoices->onFirstPage())
                <button disabled class="opacity-50"><i class="las la-angle-left text-xl"></i></button>
              @else
                <a href="{{ $invoices->previousPageUrl() }}"><i class="las la-angle-left text-xl"></i></a>
              @endif
              @if($invoices->hasMorePages())
                <a href="{{ $invoices->nextPageUrl() }}"><i class="las la-angle-right text-xl"></i></a>
              @else
                <button disabled class="opacity-50"><i class="las la-angle-right text-xl"></i></button>
              @endif
            </div>
          </div>
        </div>
      </div>
@endsection
