<div>
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 for="">RUTAS</h3>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    <div id="calendario" class="col-md-12 mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    


    @push('js') 
{{-- Include FullCalendar libraries --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.js'></script>
<link src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel="stylesheet"></link>

{{-- Initialize FullCalendar --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
            // Ejemplos de eventos de vacaciones
            {
                title: 'Vacaciones de Juan',
                start: '2024-05-10',
                end: '2024-05-15'
            },
            {
                title: 'Vacaciones de María',
                start: '2024-05-20',
                end: '2024-05-25'
            }
            // Puedes agregar más eventos de vacaciones aquí
        ]
    });
    calendar.render();
});
</script>
@endpush
</div>
