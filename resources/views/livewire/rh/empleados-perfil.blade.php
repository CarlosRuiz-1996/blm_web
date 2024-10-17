<div>
    <div class="row">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="info-empleado-tab" data-toggle="tab" href="#info-empleado" role="tab" aria-controls="info-empleado" aria-selected="true">Información del Empleado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="emergencias-tab" data-toggle="tab" href="#emergencias" role="tab" aria-controls="emergencias" aria-selected="false">Emergencias</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="salud-tab" data-toggle="tab" href="#salud" role="tab" aria-controls="salud" aria-selected="false">Salud</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <!-- Información del Empleado -->
                        <div class="tab-pane fade show active" id="info-empleado" role="tabpanel" aria-labelledby="info-empleado-tab">
                            <h5><b>Nombre Empleado:</b> {{ $empleado->user->name . ' ' . $empleado->user->paterno . ' ' . $empleado->user->materno }}.</h5>
                            <h5><b>Clave:</b> {{ optional($empleado)->cve_empleado ?? 'Sin información' }}.</h5>
                            <h5><b>Sexo:</b> {{ optional($empleado)->sexo ?? 'Sin información' }}.</h5>
                            <h5><b>Correo:</b> {{ optional($empleado->user)->email ?? 'Sin información' }}.</h5>
                            <h5><b>Edad:</b> {{ optional($empleado)->fecha_nacimiento ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age . ' Años' : 'Sin información' }}</h5>
                        </div>
                        <!-- Emergencias -->
                        <div class="tab-pane fade" id="emergencias" role="tabpanel" aria-labelledby="emergencias-tab">
                            <h5><b>Nombre Emergencia 1:</b> {{ optional($empleado)->nombre_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Teléfono Emergencia 1:</b> {{ optional($empleado)->telefono_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Parentesco Emergencia 1:</b> {{ optional($empleado)->parentesco_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Dirección Emergencia 1:</b> {{ optional($empleado)->direccion_emergencia1 ?? 'Sin información' }}.</h5>
                        
                            <h5><b>Nombre Emergencia 2:</b> {{ optional($empleado)->nombre_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Teléfono Emergencia 2:</b> {{ optional($empleado)->telefono_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Parentesco Emergencia 2:</b> {{ optional($empleado)->parentesco_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Dirección Emergencia 2:</b> {{ optional($empleado)->direccion_emergencia2 ?? 'Sin información' }}.</h5>
                        </div>
                        <!-- Salud -->
                        <div class="tab-pane fade" id="salud" role="tabpanel" aria-labelledby="salud-tab">
                            <h5><b>Alergias:</b> {{ optional($empleado)->alergias ?? 'Sin información' }}.</h5>
                            <h5><b>Tipo de Sangre:</b> {{ optional($empleado)->tipo_sangre ?? 'Sin información' }}.</h5>
                            <h5><b>UMF:</b> {{ optional($empleado)->umf ?? 'Sin información' }}.</h5>
                            <h5><b>Hospital:</b> {{ optional($empleado)->hospital ?? 'Sin información' }}.</h5>
                        </div>
                    </div>
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
                    <button wire:click='desactivarempleado({{$empleado->id}})' class="btn btn-danger btn-block mt-1">Desactivar</button>
                    @else
                    <button wire:click='activarempleado({{$empleado->id}})' class="btn btn-success btn-block mt-1">Activar</button>
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