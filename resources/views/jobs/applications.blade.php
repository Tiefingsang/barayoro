@extends('layouts.app')

@section('title', 'Candidatures reçues')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Candidatures reçues</h1>
            <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-orange-custom">
                <i class="fas fa-arrow-left mr-1"></i>Retour aux offres
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CV</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($applications as $application)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $application->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $application->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->jobOffer->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $application->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $application->status == 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $application->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $application->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $application->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('jobs.application.cv', $application->id) }}" class="text-blue-600 hover:text-blue-800" target="_blank">
                                <i class="fas fa-download mr-1"></i>CV
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="#" class="text-green-600 hover:text-green-800" onclick="updateStatus('{{ $application->id }}', 'reviewed')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-800" onclick="updateStatus('{{ $application->id }}', 'rejected')">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<script>
function updateStatus(id, status) {
    if(confirm('Changer le statut de cette candidature ?')) {
        fetch(`/jobs/applications/${id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        }).then(response => response.json())
          .then(data => {
              if(data.success) {
                  location.reload();
              }
          });
    }
}
</script>
@endsection