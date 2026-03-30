@extends('layouts.app')

@section('title', 'Détails de la facture')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Breadcrumb -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails de la facture</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('invoices.index') }}">
                  <i class="las la-file-invoice shrink-0"></i>
                  <span>Factures</span>
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

        <!-- Détails de la facture -->
        <div class="white-box">
          <div class="flex justify-between items-start mb-6">
            <div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Facture #{{ $invoice->invoice_number }}</h3>
              <p class="text-gray-500 dark:text-gray-400 mt-1">Date d'émission : {{ $invoice->issue_date->format('d/m/Y') }}</p>
              <p class="text-gray-500 dark:text-gray-400">Date d'échéance : {{ $invoice->due_date->format('d/m/Y') }}</p>
            </div>
            <div class="text-right">
              <span class="px-4 py-2 rounded-full text-sm font-semibold
                @if($invoice->status == 'paid') bg-green-100 text-green-800
                @elseif($invoice->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                @if($invoice->status == 'paid') Payée
                @elseif($invoice->status == 'pending') En attente
                @elseif($invoice->status == 'overdue') En retard
                @elseif($invoice->status == 'sent') Envoyée
                @elseif($invoice->status == 'draft') Brouillon
                @elseif($invoice->status == 'cancelled') Annulée
                @else {{ ucfirst($invoice->status) }}
                @endif
              </span>
            </div>
          </div>

          <!-- Informations Expéditeur / Destinataire -->
          <div class="grid grid-cols-2 gap-6 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
            <div>
              <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Expéditeur</h4>
              <p class="font-medium">{{ auth()->user()->company->name ?? 'Barayoro' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->company->address ?? 'Dakar, Sénégal' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->company->email ?? 'contact@barayoro.com' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->company->phone ?? '+221 33 123 45 67' }}</p>
            </div>
            <div>
              <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Destinataire</h4>
              <p class="font-medium">{{ $invoice->client->name }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client->address ?? '' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client->email ?? '' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client->phone ?? '' }}</p>
            </div>
          </div>

          <!-- Détails des articles -->
          <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails de la facture</h4>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Description</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Quantité</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Prix unit.</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Remise</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Taxe</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  @foreach($invoice->items as $item)
                  <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item['description'] }}</td>
                    <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ $item['quantity'] }}</td>
                    <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($item['unit_price'], 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ $item['discount'] }}%</td>
                    <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ $item['tax_rate'] }}%</td>
                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white">{{ number_format($item['total'], 0, ',', ' ') }} FCFA</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Sous-total</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</td>
                  </tr>
                  @if($invoice->discount > 0)
                  <tr>
                    <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Remise</td>
                    <td class="px-4 py-3 text-right text-red-600">- {{ number_format($invoice->discount, 0, ',', ' ') }} FCFA</td>
                  </tr>
                  @endif
                  @if($invoice->tax > 0)
                  <tr>
                    <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Taxes</td>
                    <td class="px-4 py-3 text-right text-green-600">{{ number_format($invoice->tax, 0, ',', ' ') }} FCFA</td>
                  </tr>
                  @endif
                  <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                    <td colspan="5" class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">Total</td>
                    <td class="px-4 py-3 text-right font-bold text-xl text-gray-900 dark:text-white">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- Notes et conditions -->
          @if($invoice->notes || $invoice->terms)
          <div class="grid grid-cols-2 gap-6 mb-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            @if($invoice->notes)
            <div>
              <h4 class="text-sm font-semibold text-gray-500 mb-2">Notes</h4>
              <p class="text-sm text-gray-700 dark:text-gray-300">{{ $invoice->notes }}</p>
            </div>
            @endif
            @if($invoice->terms)
            <div>
              <h4 class="text-sm font-semibold text-gray-500 mb-2">Conditions</h4>
              <p class="text-sm text-gray-700 dark:text-gray-300">{{ $invoice->terms }}</p>
            </div>
            @endif
          </div>
          @endif

          <!-- Paiements -->
          @if($payments && $payments->count() > 0)
          <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Historique des paiements</h4>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Montant</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Méthode</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Référence</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  @foreach($payments as $payment)
                  <tr>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($payment->method) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $payment->reference ?? '-' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4 text-right">
              <p class="text-sm text-gray-500">Total payé : <span class="font-semibold text-green-600">{{ number_format($invoice->paid, 0, ',', ' ') }} FCFA</span></p>
              <p class="text-sm text-gray-500">Solde restant : <span class="font-semibold {{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($invoice->balance, 0, ',', ' ') }} FCFA</span></p>
            </div>
          </div>
          @endif

          <!-- Actions -->
          <div class="flex gap-4 flex-wrap pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn-primary-outlined px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
              <i class="las la-file-pdf mr-2"></i> Télécharger PDF
            </a>
            @if($invoice->status == 'draft')
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn-primary-outlined px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
              <i class="las la-edit mr-2"></i> Modifier
            </a>
            <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="btn-primary px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                <i class="las la-envelope mr-2"></i> Envoyer par email
              </button>
            </form>
            @endif
            @if($invoice->status == 'pending' && $invoice->balance > 0)
            <form action="{{ route('invoices.mark-as-paid', $invoice) }}" method="POST" class="inline">
              @csrf
              @method('PUT')
              <button type="submit" class="btn-primary px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition" onclick="return confirm('Marquer cette facture comme payée ?')">
                <i class="las la-check-circle mr-2"></i> Marquer comme payée
              </button>
            </form>
            @endif
            <a href="{{ route('invoices.index') }}" class="btn-primary-outlined px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
              <i class="las la-arrow-left mr-2"></i> Retour à la liste
            </a>
            
          </div>
        </div>
      </div>
@endsection
