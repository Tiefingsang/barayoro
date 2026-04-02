<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Journalier</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #2C7BE5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #2C7BE5; margin: 0; }
        .period { background: #f0f9ff; padding: 10px; border-left: 4px solid #2C7BE5; margin-bottom: 20px; }
        .section-title { background: #2C7BE5; color: white; padding: 8px; margin: 20px 0 10px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .badge-completed { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; }
        .badge-progress { background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; }
        .badge-pending { background: #fed7aa; color: #92400e; padding: 2px 8px; border-radius: 12px; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Journalier</h1>
        <p>Date : {{ \Carbon\Carbon::parse($period)->format('d/m/Y') }}</p>
        <p>Généré le : {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="period">
        <strong>📅 Période :</strong> {{ \Carbon\Carbon::parse($period)->format('d/m/Y') }}
    </div>

    <!-- Résumé -->
    <div class="section-title">📊 Résumé du jour</div>
    <table>
        <tr><td width="50%">Tâches totales</td><td><strong>{{ $data['summary']['tasks']['total'] }}</strong></td></tr>
        <tr><td>Tâches terminées</td><td><strong>{{ $data['summary']['tasks']['completed'] }}</strong></td></tr>
        <tr><td>Tâches en cours</td><td><strong>{{ $data['summary']['tasks']['in_progress'] }}</strong></td></tr>
        <tr><td>Taux de complétion</td><td><strong>{{ $data['summary']['tasks']['completion_rate'] }}%</strong></td></tr>
    </table>

    <!-- Liste des tâches -->
    <div class="section-title">✅ Tâches du jour</div>
    <table>
        <thead>
            <tr><th>Titre</th><th>Statut</th><th>Priorité</th><th>Assigné à</th></tr>
        </thead>
        <tbody>
            @forelse($data['tasks'] as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>
                    @if($task->status == 'completed') <span class="badge-completed">Terminé</span>
                    @elseif($task->status == 'in_progress') <span class="badge-progress">En cours</span>
                    @else <span class="badge-pending">En attente</span> @endif
                </td>
                <td>{{ $task->priority }}</td>
                <td>{{ $task->assignee->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Aucune tâche</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par Barayoro ERP</p>
    </div>
</body>
</html>
