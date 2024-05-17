<div>
    <div class="row">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <h5><b>Nombre Empleado:
                        </b>{{ $empleado->user->name . ' ' . $empleado->user->paterno . ' ' . $empleado->user->materno
                        }}.
                    </h5>
                    <h5><b>Clave: </b>{{ $empleado->cve_empleado }}.</h5>
                    <h5><b>Sexo: </b>{{ $empleado->sexo }}.</h5>
                    <h5><b>Correo: </b>{{ $empleado->user->email }}.</h5>
                    <h5><b>Edad: </b>{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age }} Años</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="text-center d-flex justify-content-center">
                        @if(file_exists(storage_path('app/public/fotosEmpleados/' . $empleado->id . '.png')))
                        <img src="{{ asset('storage/fotosEmpleados/' . $empleado->id . '.png') }}"
                            alt="Imagen del empleado" width="200" height="117">
                        @else
                        <img src="{{ asset('img/sinfoto.png') }}" alt="Imagen por defecto" width="200" height="117">
                        @endif
                    </div>
                    @if ($empleado->status_empleado == 1)
                    <button type="submit" class="btn btn-danger btn-block mt-1">Desactivar</button>
                    @else
                    <button type="submit" class="btn btn-success btn-block mt-1">Activar</button>
                    @endif
                </div>
            </div>
        </div>

    <div class="col-md-12">
        <div class="card card-outline card-info">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#documentos">Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#vacaciones">Vacaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#herramientas">Herramientas de Trabajo</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="documentos" class="tab-pane fade show active">
                    @livewire('rh.empleados-expediente-digital', ['empleadoId' => $empleado->id])
                </div>
                <div id="vacaciones" class="tab-pane fade">
                    @livewire('rh.empleados-vacaciones-perfil', ['empleadoId' => $empleado->id])
                </div>
                <div id="herramientas" class="tab-pane fade">
                    @livewire('rh.empleadosherramientas', ['empleadoId' => $empleado->id])
                </div>
            </div>
        </div>
    </div>
</div>
</div>