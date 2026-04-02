@extends('layouts.app')

@section('content')
    <div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6">
        <div class="grid grid-cols-12 gap-4 xxl:gap-6">

          <!-- Cartes de statistiques -->
          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-primary-100">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <i class="las la-chart-line text-3xl text-primary-300"></i>
                </span>
                <div class="analytics-stat-chart-1"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">{{ number_format($stats['total_sales'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Chiffre d'affaires</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-primary-300 inline-flex py-2 px-4 items-center gap-1">
                  <i class="las la-arrow-up text-lg"></i>
                  {{ $stats['sales_growth'] ?? 0 }}%
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-secondary-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <i class="las la-file-invoice-dollar text-3xl text-secondary-300"></i>
                </span>
                <div class="analytics-stat-chart-2"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">{{ number_format($stats['pending_invoices'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Factures impayées</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-secondary-300 inline-flex py-2 px-4 items-center gap-1">
                  <i class="las la-exclamation-triangle text-lg"></i>
                  {{ $stats['pending_count'] ?? 0 }} factures
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-warning-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <i class="las la-tasks text-3xl text-warning-300"></i>
                </span>
                <div class="analytics-stat-chart-3"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">{{ $stats['active_projects'] ?? 0 }}</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Projets en cours</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-warning-300 inline-flex py-2 px-4 items-center gap-1">
                  <i class="las la-calendar-check text-lg"></i>
                  {{ $stats['total_projects'] ?? 0 }} total
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-12 md:col-span-6 xxxl:col-span-3">
            <div class="p-3 md:p-4 lg:p-6 xxl:p-8 rounded-xl bg-error-300/40">
              <div class="flex justify-between items-start">
                <span class="bg-neutral-0 dark:bg-neutral-904 rounded-lg size-14 shrink-0 xxl:size-16 f-center">
                  <i class="las la-boxes text-3xl text-error-300"></i>
                </span>
                <div class="analytics-stat-chart-4"></div>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <h3 class="mb-4">{{ $stats['low_stock_count'] ?? 0 }}</h3>
                  <p class="text-xs shrink-0 xxl:m-text font-medium">Produits stock faible</p>
                </div>
                <span class="m-text rounded-full bg-neutral-0/50 dark:bg-neutral-904/50 text-error-300 inline-flex py-2 px-4 items-center gap-1">
                  <i class="las la-exclamation-circle text-lg"></i>
                  Alerte
                </span>
              </div>
            </div>
          </div>

          <!-- Graphique des ventes -->
          <div class="col-span-12 xxl:col-span-8 space-y-4 xxl:space-y-6">
            <div class="white-box overflow-hidden">
              <div class="bb-dashed-n30 flex flex-col sm:flex-row justify-center sm:justify-between gap-4 items-center">
                <div>
                  <h4>Évolution des ventes {{ date('Y') }}</h4>
                  <p class="m-text mt-2">({{ $stats['sales_growth'] ?? 0 }}%) par rapport au mois dernier</p>
                </div>
                <div class="flex items-center gap-3">
                  <span>Période : </span>
                  <select name="year" id="yearSelect" class="nc-select n20" onchange="window.location.href='?year='+this.value">
                    <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                    <option value="{{ date('Y')-1 }}">{{ date('Y')-1 }}</option>
                    <option value="{{ date('Y')-2 }}">{{ date('Y')-2 }}</option>
                  </select>
                </div>
              </div>
              <div id="salesChart" style="height: 380px;"></div>
            </div>

            <!-- Cartes de statistiques détaillées -->
            <div class="gap-4 lg:gap-6 grid grid-cols-2">
              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-users text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Utilisateurs actifs</p>
                    <h3 class="mb-4">{{ $stats['total_users'] ?? 0 }}</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-user-plus"></i>
                  +{{ $stats['new_users'] ?? 0 }}
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-hand-holding-usd text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Dépenses mensuelles</p>
                    <h3 class="mb-4">{{ number_format($stats['monthly_expenses'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-chart-line"></i>
                  {{ $stats['expenses_vs_prev'] ?? 0 }}%
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-check-circle text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Tâches terminées</p>
                    <h3 class="mb-4">{{ $stats['completed_tasks'] ?? 0 }}/{{ $stats['total_tasks'] ?? 0 }}</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-percent"></i>
                  {{ $stats['tasks_completion_rate'] ?? 0 }}%
                </span>
              </div>

              <div class="col-span-2 md:col-span-1 white-box flex justify-between items-end gap-4 xxxl:gap-6">
                <div class="flex items-start gap-4">
                  <div class="f-center rounded-lg size-12 xxl:size-14 bg-primary-5 border border-neutral-30 dark:border-neutral-500 text-primary-300">
                    <i class="las la-clock text-3xl"></i>
                  </div>
                  <div>
                    <p class="m-text mb-4">Factures en retard</p>
                    <h3 class="mb-4">{{ number_format($stats['overdue_invoices'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                  </div>
                </div>
                <span class="rounded-full py-2 px-4 bg-primary-5 text-primary-300 flex items-center gap-1">
                  <i class="las la-exclamation-triangle"></i>
                  {{ $stats['overdue_count'] ?? 0 }} factures
                </span>
              </div>
            </div>
          </div>

          <!-- Projets récents -->
          <div class="col-span-12 xxl:col-span-4 white-box overflow-hidden">
            <h4 class="bb-dashed-n30">Projets en cours</h4>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              @forelse($activeProjects ?? [] as $project)
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 xxxl:gap-6">
                  <div class="size-14 rounded-lg border border-primary-100 bg-primary-50/5 f-center">
                    <i class="las la-project-diagram text-2xl text-primary-300"></i>
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">{{ $project->name }}</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">{{ $project->client->name ?? 'Sans client' }}</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 s-text">Progression</p>
                  <p class="text-primary-300 text-sm">{{ $project->progress ?? 0 }}%</p>
                </div>
              </div>
              @empty
              <div class="text-center py-8">
                <i class="las la-folder-open text-4xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Aucun projet en cours</p>
              </div>
              @endforelse
            </div>
          </div>

          <!-- Liste des produits -->
          <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box">
            <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-4 bb-dashed-n30">
              <h4>Produits les plus vendus</h4>
              <div class="flex items-center gap-3">
                <span>Filtrer : </span>
                <select class="nc-select n20" id="productFilter">
                  <option value="all">Tous</option>
                  <option value="low_stock">Stock faible</option>
                  <option value="out_stock">Rupture</option>
                </select>
              </div>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <thead class="text-left">
                  <tr class="bg-neutral-20 dark:bg-neutral-903">
                    <th class="px-6 py-3 lg:py-5">Produit</th>
                    <th class="px-6 py-3 lg:py-5">Catégorie</th>
                    <th class="px-6 py-3 lg:py-5">Prix</th>
                    <th class="px-6 py-3 lg:py-5">Stock</th>
                    <th class="px-6 py-3 lg:py-5">Statut</th>
                    <th class="px-6 py-3 lg:py-5">Actions</th>
                   </tr>
                </thead>
                <tbody>
                  @forelse($topProducts ?? [] as $product)
                  <tr class="border-b border-neutral-30 bg-neutral-0 duration-300 hover:bg-neutral-20 dark:border-neutral-500 dark:bg-neutral-904 dark:hover:bg-neutral-903">
                    <td class="px-6 py-3 lg:py-5">
                      <div class="flex items-center gap-2">
                        <i class="las la-box text-xl text-primary-300"></i>
                        <span class="m-text font-medium">{{ $product->name }}</span>
                      </div>
                    </td>
                    <td class="px-6 py-3 lg:py-5">{{ $product->category ?? 'Non catégorisé' }}</td>
                    <td class="px-6 py-3 lg:py-5">{{ number_format($product->selling_price, 0, ',', ' ') }} FCFA</td>
                    <td class="px-6 py-3 lg:py-5">{{ $product->stock_quantity }} {{ $product->unit ?? 'pièces' }}</td>
                    <td class="px-6 py-3 lg:py-5">
                      @if($product->stock_quantity <= 0)
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Rupture</span>
                      @elseif($product->stock_quantity <= $product->min_stock_quantity)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Stock faible</span>
                      @else
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">En stock</span>
                      @endif
                    </td>
                    <td class="px-6 py-3 lg:py-5">
                      <a href="{{ route('products.edit', $product) }}" class="py-2 px-5 rounded-3xl s-text font-semibold text-primary-300 border border-primary-300">Modifier</a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">Aucun produit trouvé</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Transfert rapide / Création rapide -->
          <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <div class="bb-dashed-n30 mb-4 flex items-center justify-between pb-4 lg:mb-6 lg:pb-6">
              <div class="flex rounded-lg border border-neutral-30 dark:border-neutral-500 bg-primary-5 w-full">
                <a href="{{ route('invoices.create') }}" class="flex-1 px-4 py-2 rounded-lg text-center bg-primary-300 text-neutral-0">Nouvelle facture</a>
                <a href="{{ route('projects.create') }}" class="flex-1 px-4 py-2 rounded-lg text-center bg-transparent hover:bg-primary-50">Nouveau projet</a>
              </div>
            </div>

            <div class="bb-dashed-n30 mb-4 pb-4 lg:mb-6 lg:pb-6">
              <p class="mb-4 text-lg font-medium">Création rapide de facture</p>
              <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                  <div class="form-input">
                    <select name="client_id" class="w-full p-3 border rounded-lg" required>
                      <option value="">Sélectionner un client</option>
                      @foreach($recentClients ?? [] as $client)
                      <option value="{{ $client->id }}">{{ $client->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-input">
                    <input type="number" name="amount" class="w-full p-3 border rounded-lg" placeholder="Montant" required>
                  </div>
                  <button type="submit" class="btn-primary w-full py-3">Créer la facture</button>
                </div>
              </form>
            </div>

            <div>
              <p class="mb-6 text-lg font-medium">Méthodes de paiement acceptées</p>
              <div class="grid grid-cols-3 gap-3">
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                </div>
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                </div>
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                </div>
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fab fa-paypal text-2xl text-blue-500"></i>
                </div>
                <div class="flex items-center justify-center p-3 rounded-lg border border-neutral-30">
                  <i class="fas fa-mobile-alt text-2xl text-orange-500"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Tâches récentes -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Tâches récentes</h4>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              @forelse($recentTasks ?? [] as $task)
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                  <div class="size-10 rounded-full border border-neutral-30 bg-neutral-20 f-center text-xl text-primary-300">
                    <i class="las la-tasks"></i>
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">{{ $task->title }}</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">Assigné à: {{ $task->assignee->name ?? 'Non assigné' }}</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 m-text">{{ $task->status }}</p>
                  <p class="text-primary-300 text-sm">{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Sans échéance' }}</p>
                </div>
              </div>
              @empty
              <div class="text-center py-8">
                <i class="las la-check-circle text-4xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Aucune tâche récente</p>
              </div>
              @endforelse
            </div>
          </div>

          <!-- Activité récente -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Activité récente</h4>
            <div class="space-y-5 xxl:space-y-8 mt-4 xxl:mt-6">
              @forelse($recentActivities ?? [] as $activity)
              <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                  <div class="size-10 rounded-full border border-neutral-30 bg-neutral-20 f-center text-xl {{ $activity->icon_color ?? 'text-primary-300' }}">
                    <i class="{{ $activity->icon ?? 'las la-bell' }}"></i>
                  </div>
                  <div>
                    <p class="font-medium l-text mb-2">{{ $activity->title }}</p>
                    <div class="flex items-center gap-2">
                      <span class="block size-2 rounded-full bg-primary-300"></span>
                      <span class="s-text">{{ $activity->description }}</span>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <p class="mb-2 m-text">{{ $activity->created_at->diffForHumans() }}</p>
                  @if(isset($activity->amount))
                  <p class="text-primary-300 text-sm">{{ number_format($activity->amount, 0, ',', ' ') }} FCFA</p>
                  @endif
                </div>
              </div>
              @empty
              <div class="text-center py-8">
                <i class="las la-history text-4xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Aucune activité récente</p>
              </div>
              @endforelse
            </div>
          </div>

          <!-- Factures en attente -->
          <div class="col-span-12 lg:col-span-6 xxxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Factures en attente</h4>
            <div class="flex justify-between items-center mb-6">
              <p class="l-text font-medium">À régler</p>
              <a href="{{ route('invoices.index') }}" class="h6 text-primary-300 font-semibold">Voir tout <i class="las la-arrow-right text-lg"></i></a>
            </div>

            <div class="space-y-4">
              @forelse($pendingInvoicesList ?? [] as $invoice)
              <div class="flex justify-between items-center p-3 rounded-lg border border-neutral-30">
                <div>
                  <p class="font-medium">{{ $invoice->invoice_number }}</p>
                  <p class="text-sm text-gray-500">{{ $invoice->client->name ?? 'Client' }}</p>
                </div>
                <div class="text-end">
                  <p class="font-medium text-primary-300">{{ number_format($invoice->balance, 0, ',', ' ') }} FCFA</p>
                  <p class="text-xs text-gray-500">Échéance: {{ $invoice->due_date->format('d/m/Y') }}</p>
                </div>
              </div>
              @empty
              <div class="text-center py-8">
                <i class="las la-check-circle text-4xl text-green-500"></i>
                <p class="text-gray-500 mt-2">Aucune facture en attente</p>
              </div>
              @endforelse
            </div>

            @if(($pendingInvoicesList ?? [])->count() > 0)
            <div class="mt-6 pt-4 border-t border-neutral-30">
              <a href="{{ route('invoices.index') }}" class="btn-primary w-full text-center block py-2">Gérer les factures</a>
            </div>
            @endif
          </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des ventes
    var salesData = {!! json_encode($monthlySalesData ?? []) !!};
    var months = {!! json_encode($months ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']) !!};

    var options = {
        series: [{
            name: 'Ventes',
            data: salesData
        }],
        chart: {
            type: 'line',
            height: 380,
            toolbar: {
                show: false
            }
        },
        colors: ['#2C7BE5'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: months,
            labels: {
                style: {
                    colors: '#6B7280'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Montant (FCFA)'
            },
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('fr-FR');
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value.toLocaleString('fr-FR') + ' FCFA';
                }
            }
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 5
        }
    };

    var chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();

    // Filtre des produits
    document.getElementById('productFilter')?.addEventListener('change', function(e) {
        console.log('Filtre:', e.target.value);
    });
});
</script>
@endpush
