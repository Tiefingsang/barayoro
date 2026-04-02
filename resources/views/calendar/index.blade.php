@extends('layouts.app')

@section('title', 'Calendrier')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative">
            <h2 class="mb-3 xxxl:mb-5">Calendrier</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li><a class="flex items-center gap-2" href="{{ route('dashboard') }}"><i class="las la-home"></i><span>Accueil</span></a></li>
                <li class="text-sm text-neutral-100">•</li>
                <li><a class="flex items-center gap-2 text-primary-300" href="#"><i class="las la-calendar-alt"></i><span>Calendrier</span></a></li>
            </ul>
        </div>
    </div>

    <div class="white-box">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    .fc-event {
        cursor: pointer;
        border: none;
        padding: 2px 4px;
    }
    .fc-event-title {
        font-size: 12px;
        font-weight: 500;
    }
    .fc-daygrid-day-number {
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        events: {!! json_encode($events) !!},
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        height: 'auto',
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        }
    });
    calendar.render();
});
</script>
@endpush
