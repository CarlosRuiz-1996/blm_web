<div>
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 for="">RUTAS</h3>

    </div>
    <div class="">
        <ul class="nav nav-tabs" wire:ignore.self id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->


            <li class="nav-item">
                <a class="nav-link active" id="rutas-all-tab" data-toggle="pill" href="#rutas-all" role="tab"
                    aria-controls="rutas-all" aria-selected="true">TODAS LAS RUTAS</a>
            </li>
            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="rutas-dia-tab" data-toggle="pill" href="#rutas-dia" role="tab"
                    aria-controls="rutas-dia" aria-selected="false">RUTAS PARA MAÑANA</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="servicios-new-tab" data-toggle="pill" href="#servicios-new" role="tab"
                    aria-controls="servicios-new" aria-selected="true">SERVICIOS PENDIENTES
                    @if ($news > 0)
                        <span class="badge badge-danger">{{ $news }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="servicios-all-tab" data-toggle="pill" href="#servicios-all" role="tab"
                    aria-controls="servicios-all" aria-selected="true">TODOS LOS SERVICIOS</a>
            </li>
        </ul>

        <div class="tab-content" wire:ignore.self id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="rutas-all" role="tabpanel" aria-labelledby="rutas-all-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-select-validado label="Dia:" placeholder="Seleccione el dia"
                                                wire-model="form.diasfiltro" required>
                                                @if ($dias)
                                                @foreach ($dias as $dia)
                                                    <option value="{{ $dia->id }}">{{ $dia->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Esperando...</option>
                                            @endif
                                            </x-select-validado>
                                        </div>
                                        <div class="col-md-4  mt-3">
                                            <button class="btn btn-info btn-block  mt-3" wire:click='filtrarRutas()'>Filtrar</button>
                                        </div>
                                        
                                    </div>
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Dia</th>
                                                <th>Riesgo</th>
                                                <th>Estado</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Finalización</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rutas as $ruta)
                                                <tr>
                                                    <td>{{ $ruta->id }}</td>
                                                    <td>{{ $ruta->nombre->name }}</td>
                                                    <td>{{ $ruta->dia->name }}</td>
                                                    <td>{{ $ruta->riesgo->name }}</td>
                                                    <td>{{ $ruta->estado->name }}</td>
                                                    <td>{{ $ruta->hora_inicio }}</td>
                                                    <td>{{ $ruta->hora_fin }}</td>
                                                    <td>
                                                        <a href="{{ route('ruta.gestion', [2, $ruta]) }}">Detalles</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Contenido de la pestaña "Otra Pestaña 2" -->
            <div class="tab-pane fade" id="rutas-dia" role="tabpanel" aria-labelledby="rutas-dia-tab">
                

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Dia</th>
                                                <th>Riesgo</th>
                                                <th>Estado</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Finalización</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rutasdiasiguiente as $rutasigui)
                                            <tr>
                                                <td>{{ $rutasigui->id }}</td>
                                                <td>{{ $rutasigui->nombre->name }}</td>
                                                <td>{{ $rutasigui->dia->name }}</td>
                                                <td>{{ $rutasigui->riesgo->name }}</td>
                                                <td>{{ $rutasigui->estado->name }}</td>
                                                <td>{{ $rutasigui->hora_inicio }}</td>
                                                <td>{{ $rutasigui->hora_fin }}</td>
                                                <td>
                                                    <a href="{{ route('ruta.gestion', [2, $rutasigui]) }}">Detalles</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
           

            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade " id="servicios-new" role="tabpanel"
                aria-labelledby="servicios-new-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>RFC</th>
                                                <th>Cantidad</th>
                                                <th>Detalles</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicio_new as $cliente)
                                                <tr>
                                                    <td>{{ $cliente->razon_social }}</td>
                                                    <td>{{ $cliente->rfc_cliente }}</td>
                                                    <td>{{ $cliente->servicios_count }}</td>
                                                    <td>
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            title="Detalles de la sucursal" data-toggle="modal"
                                                            wire:click='DetalleServicioCliente({{ $cliente->id }},1)'
                                                            data-target="#modalPendientes">
                                                            <i class="fa fa-lg fa-fw fa-info-circle"></i>
                                                        </button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade " id="servicios-all" role="tabpanel" aria-labelledby="servicios-all-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>RFC</th>
                                                <th>Cantidad</th>
                                                {{-- <th>Detalles</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clientes as $cliente)
                                                <tr>
                                                    <td>{{ $cliente->razon_social }}</td>
                                                    <td>{{ $cliente->rfc_cliente }}</td>
                                                    <td>{{ $cliente->servicios_count }}</td>
                                                    {{-- <td>
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            title="Detalles de la sucursal" data-toggle="modal"
                                                            wire:click='DetalleServicioCliente({{ $cliente->id }})'
                                                            data-target="#modalDetalles">
                                                            <i class="fa fa-lg fa-fw fa-info-circle"></i>
                                                        </button>

                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- servicios pendientes --}}
    <x-adminlte-modal wire:ignore.self id="modalPendientes" title="Servicios Nuevos" theme="info" icon="fas fa-bolt"
        size='lg' disable-animations>
        @if ($servicios)
            <table class="table table-striped table-bordered">
                <thead class="table-info">
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Dirección</th>
                    <th>Accion</th>
                </thead>
                <tbody>
                    @foreach ($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->id }}</td>
                            <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                            <td>
                                Calle
                                {{ $servicio->sucursal->sucursal->direccion . ' ' . $servicio->sucursal->sucursal->cp->cp . ' ' . $servicio->sucursal->sucursal->cp->estado->name . ' ' }}

                            </td>

                            <td>
                                <button class="btn btn-secondary btn-sm"
                                    wire:click='AgregarRuta({{ $servicio }})'>
                                    Asignar Ruta
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <strong>Cargando...</strong>
        @endif
    </x-adminlte-modal>

    {{-- servicio ruta --}}
    <x-adminlte-modal wire:ignore.self id="addServicioRuta" title="Asignar Ruta" theme="info" icon="fas fa-bolt"
        size='lg' disable-animations>

        @if ($servicio)


            <label for="">Servicio</label>
            <input class="form-control" disabled value="Calle {{ $servicio->ctg_servicio->descripcion }}" />

            <x-select-validadolive label="Dia de la ruta:" placeholder="Selecciona un dia"
                wire-model="form.ctg_ruta_dia_id" required>

                @if ($dias)
                    @foreach ($dias as $dia)
                        <option value="{{ $dia->id }}">{{ $dia->name }}
                        </option>
                    @endforeach
                @else
                    <option value="">Esperando...</option>
                @endif

            </x-select-validadolive>

            @if ($form->ctg_ruta_dia_id)
                <x-select-validadolive label="Ruta:" placeholder="Selecciona una ruta" wire-model="form.ruta_id"
                    required>

                    @if ($rutas_dia)
                        @foreach ($rutas_dia as $ruta)
                            <option value="{{ $ruta->id }}">{{ $ruta->nombre->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">Esperando...</option>
                    @endif

                </x-select-validadolive>
            @endif



            @if ($form->ruta_id && $form->ctg_ruta_dia_id)
                <x-input-validado label="Monto:" placeholder="Ingrese el monto." wire-model="form.monto"
                    type="number" />

                <x-input-validado label="Folio:" placeholder="Ingrese el folio." wire-model="form.folio"
                    type="text" />

                <x-input-validado label="Envases:" placeholder="Ingrese la cantidad de envases."
                    wire-model="form.envases" type="number" />
            @endif

        @endif

        <div class="col-md-3">
            <button type="submit" class="btn btn-info btn-block" wire:click="$dispatch('confirm',1)">Guardar</button>
        </div>
    </x-adminlte-modal>



    @push('js')
        <script>
            // detecto cuando cierra modal y limpio array
            $(document).ready(function() {
                $('#addServicioRuta').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
            });
            document.addEventListener('livewire:initialized', () => {
                @this.on('confirm', (opcion) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: opcion == 1 ? "El servicio se asignara a la ruta." :
                            "",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(opcion == 1 ? 'save-servicio-ruta' : 'save-servicios');
                        }
                    })
                })


                Livewire.on('agregar-ruta', function() {
                    $('#modalPendientes').modal('hide');
                    $('#addServicioRuta').modal('show');
                });

                Livewire.on('success', function([message]) {
                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000

                    });
                });

                Livewire.on('error', function() {

                    Swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error, intenta nuevamente.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

            });
        </script>
    @endpush
</div>
