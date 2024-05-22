<div>
    {{-- resources/views/livewire/vacaciones-component.blade.php --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Solicitud de Vacaciones</span>
                        <span class="info-box-number">10 solicitudes</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="{{ route('rh.solicitudVacaciones')}}">Más información</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-plane"></i></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vacaciones Aceptadas</span>
                        <span class="info-box-number">10 Aceptadas</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            Más información 
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vacaciones en curso</span>
                        <span class="info-box-number">10 en curso</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            Más información 
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vacaciones terminadas</span>
                        <span class="info-box-number">10 terminadas</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            Más información 
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{-- Calendario de vacaciones --}}
                <div class="mt-4">
                    <div id="calendario" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
    
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

</div>