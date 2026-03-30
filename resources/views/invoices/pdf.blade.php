<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .company-info {
            float: left;
            width: 60%;
        }
        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }
        .clearfix {
            clear: both;
        }
        .client-info {
            margin: 30px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-paid { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-overdue { background: #f8d7da; color: #721c24; }
        .status-draft { background: #e2e3e5; color: #383d41; }
        .status-sent { background: #cce5ff; color: #004085; }
    </style>
</head>
<body>
    <div class="invoice-box">

        <!-- En-tête -->
        <div class="header">
            <div class="company-info">
                <h1 style="color: #3b82f6; margin-bottom: 10px;">{{ auth()->user()->company->name ?? 'Barayoro' }}</h1>
                <p>{{ auth()->user()->company->address ?? 'Dakar, Sénégal' }}<br>
                Email: {{ auth()->user()->company->email ?? 'contact@barayoro.com' }}<br>
                Tél: {{ auth()->user()->company->phone ?? '+221 33 123 45 67' }}</p>
            </div>
            <div class="invoice-info">
                <h2>FACTURE</h2>
                <p><strong>N° Facture:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date d'émission:</strong> {{ $invoice->issue_date->format('d/m/Y') }}<br>
                <strong>Date d'échéance:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>
                <p>
                    <strong>Statut:</strong><br>
                    <span class="status
                        @if($invoice->status == 'paid') status-paid
                        @elseif($invoice->status == 'pending') status-pending
                        @elseif($invoice->status == 'overdue') status-overdue
                        @elseif($invoice->status == 'sent') status-sent
                        @else status-draft
                        @endif">
                        @if($invoice->status == 'paid') PAYÉE
                        @elseif($invoice->status == 'pending') EN ATTENTE
                        @elseif($invoice->status == 'overdue') EN RETARD
                        @elseif($invoice->status == 'sent') ENVOYÉE
                        @elseif($invoice->status == 'draft') BROUILLON
                        @elseif($invoice->status == 'cancelled') ANNULÉE
                        @else {{ strtoupper($invoice->status) }}
                        @endif
                    </span>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Client -->
        <div class="client-info">
            <h3 style="margin-bottom: 10px;">Destinataire</h3>
            <p><strong>{{ $invoice->client->name }}</strong><br>
            {{ $invoice->client->address ?? '' }}<br>
            Email: {{ $invoice->client->email ?? '' }}<br>
            Tél: {{ $invoice->client->phone ?? '' }}</p>
        </div>

        <!-- Détails des articles -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantité</th>
                    <th class="text-right">Prix unit.</th>
                    <th class="text-right">Remise</th>
                    <th class="text-right">Taxe</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ number_format($item['unit_price'], 0, ',', ' ') }} FCFA</td>
                    <td class="text-right">{{ $item['discount'] }}%</td>
                    <td class="text-right">{{ $item['tax_rate'] }}%</td>
                    <td class="text-right">{{ number_format($item['total'], 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>Sous-total</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @if($invoice->discount > 0)
                <tr>
                    <td colspan="5" class="text-right">Remise</td>
                    <td class="text-right">- {{ number_format($invoice->discount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
                @if($invoice->tax > 0)
                <tr>
                    <td colspan="5" class="text-right">Taxes</td>
                    <td class="text-right">{{ number_format($invoice->tax, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @if($invoice->paid > 0)
                <tr>
                    <td colspan="5" class="text-right">Montant payé</td>
                    <td class="text-right">{{ number_format($invoice->paid, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><strong>Solde restant</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->balance, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @endif
            </tfoot>
        </table>

        <!-- Notes et conditions -->
        @if($invoice->notes || $invoice->terms)
        <div style="margin: 20px 0;">
            @if($invoice->notes)
            <div style="margin-bottom: 15px;">
                <h4 style="margin-bottom: 5px;">Notes:</h4>
                <p style="margin: 0;">{{ $invoice->notes }}</p>
            </div>
            @endif
            @if($invoice->terms)
            <div>
                <h4 style="margin-bottom: 5px;">Conditions de paiement:</h4>
                <p style="margin: 0;">{{ $invoice->terms }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <p>Merci de votre confiance !</p>
            <p>Barayoro - Solution de gestion d'entreprise</p>
            <p>www.barayoro.com</p>
        </div>
    </div>
</body>
</html>
