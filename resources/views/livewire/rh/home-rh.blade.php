<div>
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Empleados</span>
                <span class="info-box-number">10 Empleados</span>
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
            <span class="info-box-icon bg-info "><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vacantes</span>
                <span class="info-box-number">2 vacantes</span>
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
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Puestos</span>
                <span class="info-box-number">10 puestos</span>
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
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Resguardo</span>
                <span class="info-box-number">$41,410</span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                    Aumento del 70% en 30 días
                </span>
            </div>
        </div>
    </div>
</div>



    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.js'></script>
    <link src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel="stylesheet"></link>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  var initialLocaleCode = 'es';
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    locale: initialLocaleCode,
    buttonIcons: false, // show the prev/next text
    weekNumbers: true,
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    dayMaxEvents: true, // allow "more" link when too many events
    events: 'https://fullcalendar.io/api/demo-feeds/events.json?overload-day'
  });

  calendar.render();
});
    </script>
    <div class="row">
        <div class="col-md-4">
            <div id='calendar'></div>
        </div>
    </div>
  
</div>
