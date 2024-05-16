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
    </div>

</div>