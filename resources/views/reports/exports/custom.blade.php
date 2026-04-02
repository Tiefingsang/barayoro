<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Personnalisé</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2C7BE5;
        }
        .header h1 {
            color: #2C7BE5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .period {
            background-color: #f0f9ff;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2C7BE5;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #2C7BE5;
            color: white;
            padding: 8px 12px;
            margin: 0 0 15px 0;
            font-size: 16px;
            border-radius: 4px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .kpi-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            background: #f9fafb;
        }
        .kpi-value {
            font-size: 24px;
            font-weight: bold;
            color: #2C7BE5;
        }
        .kpi-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .badge-completed {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .badge-progress {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .badge-pending {
            background-color: #fed7aa;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .progress-bar {
            background-color: #e5e7eb;
            border-radius: 10px;
            height: 8px;
            width: 100%;
        }
        .progress-fill {
            background-color: #2C7BE5;
            border-radius: 10px;
            height: 8px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="header">
        <h1>Rapport d'Activité Personnalisé</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Par : {{ $data['generated_by']->name }}</p>
    </div>

    <!-- Période -->
    <div class="period">
        <strong>📅 Période sélectionnée :</strong>
        Du {{ $data['summary']['period']['start'] }} au {{ $data['summary']['period']['end'] }}
        ({{ $data['summary']['period']['days'] }} jours)
    </div>

    <!-- Section Résumé -->
    <div class="section">
        <h3 class="section-title">📊 Résumé Global</h3>
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-value">{{ $data['summary']['tasks']['total'] }}</div>
                <div class="kpi-label">Tâches totales</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">{{ $data['summary']['tasks']['completed'] }}</div>
                <div class="kpi-label">Tâches terminées</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">{{ $data['summary']['tasks']['completion_rate'] }}%</div>
                <div class="kpi-label">Taux de complétion</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</div>
                <div class="kpi-label">Chiffre d'affaires</div>
            </div>
        </div>
    </div>

    <!-- Section Projets -->
    <div class="section">
        <h3 class="section-title">📁 Projets</h3>
        <table>
            <thead>
                <tr>
                    <th>Projet</th>
                    <th class="text-center">Tâches</th>
                    <th class="text-center">Terminées</th>
                    <th class="text-center">Progression</th>
                    <th class="text-right">Budget</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['project_stats'] as $stat)
                <tr>
                    <td>{{ $stat['project']->name }}</td>
                    <td class="text-center">{{ $stat['total_tasks'] }}</td>
                    <td class="text-center">{{ $stat['tasks_completed'] }}</td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $stat['progress'] }}%"></div>
                        </div>
                        <div class="text-center" style="font-size: 10px; margin-top: 3px;">{{ $stat['progress'] }}%</div>
                    </td>
                    <td class="text-right">{{ number_format($stat['budget'] ?? 0, 0, ',', ' ') }} FCFA</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun projet trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Section Tâches détaillées -->
    <div class="section">
        <h3 class="section-title">✅ Liste des tâches</h3>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Projet</th>
                    <th>Assigné à</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Date échéance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['tasks'] as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->project->name ?? '-' }}</td>
                    <td>{{ $task->assignee->name ?? 'Non assigné' }}</td>
                    <td>
                        @if($task->status == 'completed')
                            <span class="badge-completed">Terminé</span>
                        @elseif($task->status == 'in_progress')
                            <span class="badge-progress">En cours</span>
                        @else
                            <span class="badge-pending">En attente</span>
                        @endif
                    </td>
                    <td>
                        @if($task->priority == 'high')
                            🔴 Haute
                        @elseif($task->priority == 'medium')
                            🟠 Moyenne
                        @else
                            🟢 Basse
                        @endif
                    </td>
                    <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Aucune tâche trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Section Performance par utilisateur -->
    <div class="section">
        <h3 class="section-title">👥 Performance par utilisateur</h3>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th class="text-center">Tâches totales</th>
                    <th class="text-center">Terminées</th>
                    <th class="text-center">En cours</th>
                    <th class="text-center">Taux réussite</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['user_stats'] as $stat)
                <tr>
                    <td>{{ $stat['user']->name }}</td>
                    <td class="text-center">{{ $stat['total_tasks'] }}</td>
                    <td class="text-center">{{ $stat['tasks_completed'] }}</td>
                    <td class="text-center">{{ $stat['tasks_in_progress'] }}</td>
                    <td class="text-center">
                        <strong>{{ $stat['completion_rate'] }}%</strong>
                        <div class="progress-bar" style="margin-top: 5px;">
                            <div class="progress-fill" style="width: {{ $stat['completion_rate'] }}%; background-color: #10B981;"></div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune donnée utilisateur</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Section Financière -->
    <div class="section">
        <h3 class="section-title">💰 Résumé Financier</h3>
        <table>
            <thead>
                <tr>
                    <th>Indicateur</th>
                    <th class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total factures</td>
                    <td class="text-right">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr style="background-color: #e8f5e9;">
                    <td>Factures payées</td>
                    <td class="text-right" style="color: #2e7d32;">{{ number_format($data['summary']['invoices']['paid_amount'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr style="background-color: #fff3e0;">
                    <td>Factures en attente</td>
                    <td class="text-right" style="color: #ed6c02;">{{ number_format($data['summary']['invoices']['pending_amount'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td>Nombre de factures</td>
                    <td class="text-right">{{ $data['summary']['invoices']['total'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section Activités récentes -->
    <div class="section">
        <h3 class="section-title">📋 Activités récentes</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['activities'] as $activity)
                <tr>
                    <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $activity->user->name }}</td>
                    <td>{{ $activity->action }}</td>
                    <td>{{ $activity->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Aucune activité récente</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré automatiquement par Barayoro ERP</p>
        <p>© {{ date('Y') }} Barayoro - Tous droits réservés</p>
    </div>

</body>
</html>
