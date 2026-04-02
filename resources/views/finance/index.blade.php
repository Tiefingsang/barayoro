@extends('layouts.app')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6">
    <div class="grid grid-cols-12 gap-4 xxl:gap-6">

        <!-- Revenus -->
        <div class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl bg-primary-300/30 overflow-hidden">
            <div class="px-3 pt-3 md:px-4 md:pt-4 xl:px-6 xl:pt-6 xxl:px-8 xxl:pt-8 flex justify-between items-start">
                <div>
                    <p class="m-text text-[#006644] dark:text-neutral-10 font-medium mb-4">Chiffre d'affaires</p>
                    <h3 class="mb-4">{{ number_format($revenue ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <div class="flex gap-3 items-center">
                        <span class="text-[#006644] dark:text-neutral-10 flex items-center gap-2"><i class="las la-arrow-up text-lg"></i> {{ $collectionRate ?? 0 }}%</span>
                        <span>de recouvrement</span>
                    </div>
                </div>
                <span class="size-[52px] rounded-full bg-[#00754E] text-neutral-0 f-center">
                    <i class="las la-chart-line text-3xl"></i>
                </span>
            </div>
        </div>

        <!-- Dépenses -->
        <div class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl bg-secondary-300/30 overflow-hidden">
            <div class="px-3 pt-3 md:px-4 md:pt-4 xl:px-6 xl:pt-6 xxl:px-8 xxl:pt-8 flex justify-between items-start">
                <div>
                    <p class="m-text text-[#571F9C] dark:text-neutral-10 font-medium mb-4">Dépenses</p>
                    <h3 class="mb-4">{{ number_format($expenses ?? 0, 0, ',', ' ') }} FCFA</h3>
                    <div class="flex gap-3 items-center">
                        <span class="text-[#571F9C] dark:text-neutral-10 flex items-center gap-2"><i class="las la-arrow-down text-lg"></i> {{ $expenses > 0 ? round(($expenses / $revenue) * 100, 1) : 0 }}%</span>
                        <span>du CA</span>
                    </div>
                </div>
                <span class="size-[52px] rounded-full bg-[#571F9C] text-neutral-0 f-center">
                    <i class="las la-receipt text-3xl"></i>
                </span>
            </div>
        </div>

        <!-- Bénéfice -->
        <div dir="ltr" class="col-span-12 md:col-span-6 xxl:col-span-4 rounded-xl relative">
            <div class="p-3 md:p-4 xl:p-6 xxl:p-8 relative z-[1] bg-warning-300/30 after:size-80 after:absolute after:bg-warning-300/40 after:rounded-full after:-bottom-[45%] after:-right-[30%] overflow-hidden rounded-xl">
                <div class="flex justify-between items-start mb-5 xl:mb-8">
                    <div>
                        <p class="m-text dark:text-neutral-10 font-medium mb-4">Bénéfice net</p>
                        <h3 class="mb-4">{{ number_format($profit ?? 0, 0, ',', ' ') }} FCFA</h3>
                    </div>
                    <div class="size-[52px] rounded-full bg-[#9C6800] text-neutral-0 f-center">
                        <i class="las la-chart-line text-3xl"></i>
                    </div>
                </div>
                <div class="flex gap-2 mb-5 xl:mb-8">
                    <i class="las la-calendar-check text-xl"></i>
                    <p>Année {{ $year ?? date('Y') }}</p>
                </div>
                <div class="flex gap-5">
                    <div>
                        <p class="s-text mb-3">Marge</p>
                        <p class="xl-text font-medium">{{ $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0 }}%</p>
                    </div>
                    <div>
                        <p class="s-text mb-3">Tendance</p>
                        <p class="xl-text font-medium {{ $profit > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $profit > 0 ? '+' : '' }}{{ $profit > 0 ? round(($profit / $revenue) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique Revenus/Dépenses -->
        <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box overflow-hidden">
            <div class="bb-dashed-n30 flex flex-col sm:flex-row justify-center sm:justify-between gap-4 items-center">
                <div>
                    <h4>Revenus vs Dépenses</h4>
                    <p class="m-text mt-2">Année {{ $year ?? date('Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span>Année : </span>
                    <select name="year" id="yearSelect" class="nc-select n20" onchange="window.location.href='?year='+this.value">
                        <option value="{{ date('Y') }}" {{ ($year ?? date('Y')) == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                        <option value="{{ date('Y')-1 }}" {{ ($year ?? date('Y')) == date('Y')-1 ? 'selected' : '' }}>{{ date('Y')-1 }}</option>
                        <option value="{{ date('Y')-2 }}" {{ ($year ?? date('Y')) == date('Y')-2 ? 'selected' : '' }}>{{ date('Y')-2 }}</option>
                    </select>
                </div>
            </div>
            <div id="revenueExpenseChart" style="height: 380px;"></div>
        </div>

        <!-- Statistiques rapides -->
        <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Indicateurs clés</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg border border-neutral-30">
                    <div>
                        <p class="text-sm text-gray-500">Factures impayées</p>
                        <p class="font-bold text-lg">{{ number_format($pendingInvoices ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="size-10 rounded-full bg-yellow-100 f-center">
                        <i class="las la-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg border border-neutral-30">
                    <div>
                        <p class="text-sm text-gray-500">Factures en retard</p>
                        <p class="font-bold text-lg text-red-600">{{ number_format($overdueInvoices ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="size-10 rounded-full bg-red-100 f-center">
                        <i class="las la-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg border border-neutral-30">
                    <div>
                        <p class="text-sm text-gray-500">Taux de recouvrement</p>
                        <p class="font-bold text-lg">{{ number_format($collectionRate ?? 0, 1) }}%</p>
                    </div>
                    <div class="size-10 rounded-full bg-green-100 f-center">
                        <i class="las la-percent text-green-600"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg border border-neutral-30">
                    <div>
                        <p class="text-sm text-gray-500">Marge bénéficiaire</p>
                        <p class="font-bold text-lg">{{ $revenue > 0 ? number_format(($profit / $revenue) * 100, 1) : 0 }}%</p>
                    </div>
                    <div class="size-10 rounded-full bg-blue-100 f-center">
                        <i class="las la-chart-line text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Méthodes de paiement -->
        <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box">
            <h4 class="bb-dashed-n30">Méthodes de paiement</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                @forelse($paymentsByMethod ?? [] as $payment)
                <div class="text-center p-3 rounded-lg border border-neutral-30">
                    <div class="size-12 mx-auto rounded-full bg-primary-100 f-center mb-2">
                        <i class="las la-credit-card text-2xl text-primary-300"></i>
                    </div>
                    <p class="font-medium">{{ ucfirst($payment->method) }}</p>
                    <p class="text-sm text-primary-300">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</p>
                </div>
                @empty
                <div class="col-span-4 text-center py-8 text-gray-500">Aucune donnée de paiement</div>
                @endforelse
            </div>
        </div>

        <!-- Top clients -->
        <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Top clients</h4>
            <div class="flex flex-col gap-4">
                @forelse($topClients ?? [] as $client)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-primary-100 f-center">
                            <i class="las la-user text-primary-300"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $client->client->name ?? 'Client' }}</p>
                            <p class="text-xs text-gray-500">{{ $client->total }} FCFA</p>
                        </div>
                    </div>
                    <a href="{{ route('clients.show', $client->client_id) }}" class="text-primary-300">
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">Aucun client</div>
                @endforelse
            </div>
        </div>

        <!-- Dépenses par catégorie -->
        <div class="col-span-12 lg:col-span-7 xxl:col-span-8 white-box">
            <h4 class="bb-dashed-n30">Dépenses par catégorie</h4>
            <div id="expenseCategoryChart" style="height: 350px;"></div>
        </div>

        <!-- Échéances -->
        <div class="col-span-12 lg:col-span-5 xxl:col-span-4 white-box">
            <h4 class="bb-dashed-n30">Échéances</h4>
            <div class="space-y-3">
                @php
                    $aging = [
                        '0-30 jours' => $aging['0-30'] ?? 0,
                        '31-60 jours' => $aging['31-60'] ?? 0,
                        '61-90 jours' => $aging['61-90'] ?? 0,
                        '90+ jours' => $aging['90+'] ?? 0,
                    ];
                @endphp
                @foreach($aging as $label => $amount)
                <div class="flex justify-between items-center p-2">
                    <span class="text-sm">{{ $label }}</span>
                    <span class="font-medium {{ $amount > 0 ? 'text-red-600' : 'text-gray-500' }}">
                        {{ number_format($amount, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                @endforeach
                <div class="border-t pt-3 mt-3">
                    <div class="flex justify-between items-center font-bold">
                        <span>Total échéances</span>
                        <span class="text-red-600">{{ number_format($totalAging ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique Revenus vs Dépenses
    var revenueData = {!! json_encode($revenueData ?? []) !!};
var expenseData = {!! json_encode($expenseData ?? []) !!};
var months = {!! json_encode($months ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']) !!};
    var revenueExpenseOptions = {
        series: [
            { name: 'Revenus', data: revenueData, color: '#2C7BE5' },
            { name: 'Dépenses', data: expenseData, color: '#FF6161' }
        ],
        chart: {
            type: 'bar',
            height: 380,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: { categories: months },
        yaxis: {
            title: { text: 'Montant (FCFA)' },
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('fr-FR');
                }
            }
        },
        fill: { opacity: 1 },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value.toLocaleString('fr-FR') + ' FCFA';
                }
            }
        }
    };

    var revenueExpenseChart = new ApexCharts(document.querySelector("#revenueExpenseChart"), revenueExpenseOptions);
    revenueExpenseChart.render();

    // Graphique des dépenses par catégorie
    var expenseCategoriesData = @json($expenseCategoriesData ?? []);
    var expenseCategoriesLabels = @json($expenseCategoriesLabels ?? []);

    if (expenseCategoriesData.length > 0) {
        var expenseCategoryOptions = {
            series: expenseCategoriesData,
            chart: { type: 'donut', height: 350 },
            labels: expenseCategoriesLabels,
            colors: ['#2C7BE5', '#FFAB00', '#FF6161', '#5D69F4', '#775DD0', '#FFC861'],
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function() {
                                    var total = expenseCategoriesData.reduce((a, b) => a + b, 0);
                                    return total.toLocaleString('fr-FR') + ' FCFA';
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toLocaleString('fr-FR') + ' FCFA';
                    }
                }
            }
        };
        var expenseCategoryChart = new ApexCharts(document.querySelector("#expenseCategoryChart"), expenseCategoryOptions);
        expenseCategoryChart.render();
    } else {
        document.querySelector("#expenseCategoryChart").innerHTML = '<div class="text-center py-8 text-gray-500">Aucune donnée de dépenses</div>';
    }
});
</script>
@endpush
