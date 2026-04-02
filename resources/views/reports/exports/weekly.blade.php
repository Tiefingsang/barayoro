<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Hebdomadaire</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #2C7BE5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #2C7BE5; margin: 0; }
        .period { background: #f0f9ff; padding: 10px; border-left: 4px solid #2C7BE5; margin-bottom: 20px; }
        .section-title { background: #2C7BE5; color: white; padding: 8px; margin: 20px 0 10px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Hebdomadaire</h1>
        <p>Semaine du {{ \Carbon\Carbon::parse($period)->startOfWeek()->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($period)->endOfWeek()->format('d/m/Y') }}</p>
        <p>Généré le : {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="period">
        <strong>📅 Période :</strong> Semaine {{ \Carbon\Carbon::parse($period)->weekOfYear }} - Année {{ \Carbon\Carbon::parse($period)->year }}
    </div>

    <div class="section-title">📊 Résumé de la semaine</div>
    <table>
        <tr><td width="50%">Tâches totales</td><td><strong>{{ $data['summary']['tasks']['total'] }}</strong></td></tr>
        <tr><td>Tâches terminées</td><td><strong>{{ $data['summary']['tasks']['completed'] }}</strong></td></tr>
        <tr><td>Taux de complétion</td><td><strong>{{ $data['summary']['tasks']['completion_rate'] }}%</strong></td></tr>
        <tr><td>Chiffre d'affaires</td><td><strong>{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</strong></td></tr>
    </table>

    <div class="section-title">👥 Performance par utilisateur</div>
    <table>
        <thead><tr><th>Utilisateur</th><th class="text-center">Tâches</th><th class="text-center">Terminées</th><th class="text-center">Taux</th></tr></thead>
        <tbody>
            @foreach($data['user_stats'] as $stat)
            <tr>
                <td>{{ $stat['user']->name }}</td>
                <td class="text-center">{{ $stat['total_tasks'] }}</td>
                <td class="text-center">{{ $stat['tasks_completed'] }}</td>
                <td class="text-center">{{ $stat['completion_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par Barayoro ERP</p>
    </div>
</body>
</html>
