<div>
    {{-- resources/views/livewire/vacaciones-component.blade.php --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Solicitud de Vacaciones</span>
                        <span class="info-box-number">{{$solicitudespen}} solicitudes</span>
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
                        <span class="info-box-number">{{$solicitudesacep}} Aceptadas</span>
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
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vacaciones en curso</span>
                        <span class="info-box-number">{{$solicitudesactivas}} en curso</span>
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
                    <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vacaciones terminadas</span>
                        <span class="info-box-number">{{$solicitudestermin}} terminadas</span>
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
        <div class="row mb-4">
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
    <link href='https://unpkg.com/@fullcalendar/core@5.10.1/main.min.css' rel='stylesheet' />
    <link href='https://unpkg.com/@fullcalendar/daygrid@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://unpkg.com/@fullcalendar/core@5.10.1/main.min.js'></script>
    <script src='https://unpkg.com/@fullcalendar/daygrid@5.10.1/main.min.js'></script>
    <script src='https://unpkg.com/@fullcalendar/locales@5.10.1/es.global.js'></script> <!-- Incluye el archivo de idioma -->
    

    <script>
        document.addEventListener('livewire:init', function() {
            var calendarEl = document.getElementById('calendario');
    
            // Generar los eventos a partir de las solicitudes de Livewire
            var events = @json($solicitudesactivascalen); // Cargar los datos como un array JSON
    
            // Formatear los eventos para FullCalendar
            events = events.map(function (solicitud) {
                return {
                    title: 'Vacaciones de ' + solicitud.nombre_completo,
                    start: solicitud.fecha_inicio,
                    end: solicitud.fecha_fin,
                };
            });
    
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events, // Usar el array de eventos generado
                locale: 'es',   // Configurar el idioma a español
                headerToolbar: {
                    left: 'prev,next',    // Botones de navegación
                    center: 'title',      // Título central
                    right: 'dayGridMonth' // Vista por mes
                },
                buttonText: {
                    prev: 'Anterior',      // Cambia el texto de "prev"
                    next: 'Siguiente',     // Cambia el texto de "next"
                    dayGridMonth: 'Mes',   // Cambia el texto de vista mensual
                },
                editable: false, // Deshabilitar edición
                droppable: false // Deshabilitar soltar eventos
            });
    
            // Renderizar el calendario
            calendar.render();
        });
    </script>
    
</div>