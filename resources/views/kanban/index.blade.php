@extends('layouts.app')

@section('title', 'Tableau Kanban')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="mb-3 xxxl:mb-5">Tableau Kanban</h2>
                    <ul class="flex flex-wrap gap-2 items-center">
                        <li><a class="flex items-center gap-2" href="{{ route('dashboard') }}"><i class="las la-home"></i><span>Accueil</span></a></li>
                        <li class="text-sm text-neutral-100">•</li>
                        <li><a class="flex items-center gap-2 text-primary-300" href="#"><i class="las la-table"></i><span>Kanban</span></a></li>
                    </ul>
                </div>
                <div class="flex gap-4">
                    <select id="projectFilter" class="border rounded-lg px-4 py-2">
                        <option value="">Tous les projets</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                        @endforeach
                    </select>
                    <button onclick="openQuickTaskModal()" class="btn-primary px-4 py-2">
                        <i class="las la-plus"></i> Tâche rapide
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($columns as $key => $column)
        <div class="kanban-column" data-status="{{ $key }}">
            <div class="white-box">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-{{ $column['color'] }}-600">{{ $column['title'] }}</h4>
                    <span class="bg-gray-100 px-2 py-1 rounded-full text-sm">{{ $column['tasks']->count() }}</span>
                </div>
                <div class="kanban-tasks space-y-3 min-h-[500px]">
                    @foreach($column['tasks'] as $task)
                    <div class="task-card bg-gray-50 rounded-lg p-3 cursor-move" data-task-id="{{ $task->id }}" data-task-status="{{ $task->status }}">
                        <div class="flex justify-between items-start">
                            <h5 class="font-medium text-sm">{{ $task->title }}</h5>
                            @if($task->priority == 'high')
                            <span class="text-red-500"><i class="las la-flag"></i></span>
                            @elseif($task->priority == 'medium')
                            <span class="text-orange-500"><i class="las la-flag"></i></span>
                            @endif
                        </div>
                        @if($task->due_date)
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="las la-calendar"></i> {{ $task->due_date->format('d/m/Y') }}
                        </p>
                        @endif
                        @if($task->assignee)
                        <div class="flex items-center gap-2 mt-2">
                            <img src="{{ $task->assignee->avatar ?? asset('assets/images/default-avatar.png') }}" class="w-6 h-6 rounded-full">
                            <span class="text-xs">{{ $task->assignee->name }}</span>
                        </div>
                        @endif
                        <div class="mt-2 flex justify-end">
                            <a href="{{ route('tasks.show', $task) }}" class="text-blue-500 text-xs">Voir détails</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal tâche rapide -->
<div id="quickTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Ajouter une tâche rapide</h3>
        <form id="quickTaskForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Titre *</label>
                <input type="text" name="title" required class="w-full border rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Statut</label>
                <select name="status" class="w-full border rounded-lg px-4 py-2">
                    <option value="todo">À faire</option>
                    <option value="in_progress">En cours</option>
                    <option value="review">En relecture</option>
                    <option value="completed">Terminé</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Projet</label>
                <select name="project_id" class="w-full border rounded-lg px-4 py-2">
                    <option value="">Aucun projet</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeQuickTaskModal()" class="px-4 py-2 border rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Créer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Initialiser Sortable.js pour le drag & drop
document.querySelectorAll('.kanban-tasks').forEach(container => {
    new Sortable(container, {
        group: 'tasks',
        animation: 150,
        onEnd: function(evt) {
            const taskId = evt.item.dataset.taskId;
            const newStatus = evt.to.closest('.kanban-column').dataset.status;

            fetch('{{ route("kanban.update-status") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: taskId,
                    status: newStatus
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Mettre à jour le compteur
                      updateCounters();
                  }
              });
        }
    });
});

function updateCounters() {
    document.querySelectorAll('.kanban-column').forEach(column => {
        const count = column.querySelectorAll('.task-card').length;
        const counter = column.querySelector('.bg-gray-100');
        if (counter) counter.textContent = count;
    });
}

function openQuickTaskModal() {
    document.getElementById('quickTaskModal').classList.add('flex');
    document.getElementById('quickTaskModal').classList.remove('hidden');
}

function closeQuickTaskModal() {
    document.getElementById('quickTaskModal').classList.add('hidden');
    document.getElementById('quickTaskModal').classList.remove('flex');
}

document.getElementById('quickTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('{{ route("kanban.quick-task") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload();
          }
      });
});

document.getElementById('projectFilter').addEventListener('change', function() {
    const projectId = this.value;
    if (projectId) {
        window.location.href = '{{ route("kanban") }}?project_id=' + projectId;
    } else {
        window.location.href = '{{ route("kanban") }}';
    }
});
</script>
@endpush
