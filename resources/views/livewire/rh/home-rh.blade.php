<div>
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Empleados Activos</span>
                <span class="info-box-number">{{$conteoEmpleados}} Empleados</span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                    <a href="{{ route('rh.EmpleadosActivos')}}">Más información</a>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Empleados Inactivos</span>
                <span class="info-box-number">{{$conteoEmpleadosInactivos}} Empleados</span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                    <a href="{{ route('rh.EmpleadosInactivos')}}">Más información</a>
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
</div> 
</div>
