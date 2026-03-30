@extends('layouts.app')

@section('title', 'Détails du client')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Fil d'Ariane -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails du client</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('clients.index') }}">
                  <i class="las la-users shrink-0"></i>
                  <span>Clients</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-building shrink-0"></i>
                  <span>Détails</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Carte client -->
        <div class="white-box">
          <div class="grid grid-cols-12 gap-4 lg:gap-6">

            <!-- Colonne Profil -->
            <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
              <div class="flex flex-col items-center text-center">
                <!-- Logo / Avatar -->
                <div class="relative mb-4">
                  <div class="size-32 lg:size-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                    @if($client->logo)
                      <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600">
                        <span class="text-white text-4xl font-bold">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                      </div>
                    @endif
                  </div>
                  <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                    @if($client->status == 'active')
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                    @elseif($client->status == 'inactive')
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactif</span>
                    @else
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Prospect</span>
                    @endif
                  </div>
                </div>

                <!-- Nom et code -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $client->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $client->code }}</p>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-3 gap-2 w-full mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_projects'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Projets</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_invoices'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Factures</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_payments'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500">FCFA payés</p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 mt-6 w-full">
                  <a href="{{ route('clients.edit', $client) }}" class="flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="las la-edit mr-1"></i> Modifier
                  </a>
                  <a href="{{ route('clients.index') }}" class="flex-1 px-4 py-2 text-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="las la-arrow-left mr-1"></i> Retour
                  </a>
                </div>
              </div>
            </div>

            <!-- Colonne Informations -->
            <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
              <div class="space-y-6">

                <!-- Informations générales -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-info-circle mr-2"></i> Informations générales
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Code client</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->code }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Nom</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->name }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Email</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->email ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Site web</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($client->website)
                          <a href="{{ $client->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $client->website }}</a>
                        @else
                          -
                        @endif
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Téléphone</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->phone ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Mobile</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->mobile ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Contact principal -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-user-tie mr-2"></i> Contact principal
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Nom</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->contact_person ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Email</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->contact_email ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Téléphone</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->contact_phone ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Adresse -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-map-marker-alt mr-2"></i> Adresse
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start col-span-2">
                      <div class="w-32 text-sm font-medium text-gray-500">Adresse</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->address ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Ville</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->city ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Pays</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->country ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Code postal</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->postal_code ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Informations fiscales -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-file-invoice mr-2"></i> Informations fiscales
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">NIF / N° d'impôt</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->tax_number ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">N° TVA</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->vat_number ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Statistiques financières -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-chart-line mr-2"></i> Statistiques financières
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl">
                      <p class="text-sm text-gray-500 dark:text-gray-400">Total facturé</p>
                      <p class="text-xl font-bold text-green-600">{{ number_format($stats['total_invoiced'] ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                      <p class="text-sm text-gray-500 dark:text-gray-400">Total payé</p>
                      <p class="text-xl font-bold text-blue-600">{{ number_format($stats['total_payments'] ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                      <p class="text-sm text-gray-500 dark:text-gray-400">Solde dû</p>
                      <p class="text-xl font-bold text-yellow-600">{{ number_format($stats['total_due'] ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                  </div>
                </div>

                <!-- Notes -->
                @if($client->notes)
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-sticky-note mr-2"></i> Notes
                  </h4>
                  <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <p class="text-gray-700 dark:text-gray-300">{{ $client->notes }}</p>
                  </div>
                </div>
                @endif

                <!-- Métadonnées -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-calendar-alt mr-2"></i> Métadonnées
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Créé le</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Modifié le</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $client->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                  </div>
                </div>

                <!-- Dernières factures (optionnel) -->
                @if(isset($recentInvoices) && count($recentInvoices) > 0)
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-file-invoice mr-2"></i> Dernières factures
                  </h4>
                  <div class="space-y-3">
                    @foreach($recentInvoices as $invoice)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</p>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</p>
                        <span class="px-2 py-1 text-xs rounded-full
                          @if($invoice->status == 'paid') bg-green-100 text-green-800
                          @elseif($invoice->status == 'pending') bg-yellow-100 text-yellow-800
                          @else bg-red-100 text-red-800
                          @endif">
                          {{ $invoice->status == 'paid' ? 'Payée' : ($invoice->status == 'pending' ? 'En attente' : 'En retard') }}
                        </span>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
