<div>
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 for="">RUTAS</h3>

    </div>
    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
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
                    aria-controls="servicios-new" aria-selected="true">SERVICIOS NUEVOS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="servicios-all-tab" data-toggle="pill" href="#servicios-all" role="tab"
                    aria-controls="servicios-all" aria-selected="true">TODOS LOS SERVICIOS</a>
            </li>
        </ul>

        <div class="tab-content" id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade " id="rutas-all" role="tabpanel" aria-labelledby="rutas-all-tab">
                <div class="row">

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
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Razon Social</th>
                                                <th>RFC</th>
                                                <th>Contacto</th>
                                                <th>Fecha de Solicitud</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            @foreach ($terminadas as $solicitud)
                                            <td>{{ $solicitud->id }}</td>
                                            <td>{{ $solicitud->cliente->razon_social }}</td>
                                            <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                                            <td>{{ $solicitud->cliente->user->name .
                                                ' ' .
                                                $solicitud->cliente->user->paterno .
                                                ' ' .
                                                $solicitud->cliente->user->materno }}
                                            </td>
                                            <td>{{ $solicitud->updated_at }}</td>
                                            <td>
                                                pdf
                                            </td>
                                        @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
            </div>

            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade" id="servicios-new" role="tabpanel" aria-labelledby="servicios-new-tab">
                <div class="row">

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

            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="servicios-all" role="tabpanel"
                aria-labelledby="servicios-all-tab">
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
                                            @foreach ($clientes as $cliente)
                                                <tr>
                                                    <td>{{ $cliente->razon_social }}</td>
                                                    <td>{{ $cliente->rfc_cliente }}</td>
                                                    <td>{{ $cliente->servicios_count }}</td>
                                                    <td>
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            title="Detalles de la sucursal" data-toggle="modal"
                                                            wire:click='DetalleServicioCliente({{ $cliente->id }})'
                                                            data-target="#modalDetalles">
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
        </div>
    </div>



    {{-- detalles sucursal --}}
    <x-adminlte-modal wire:ignore.self id="modalDetalles" title="Servicios del cliente" theme="info"
        icon="fas fa-bolt" size='lg' disable-animations>
        @if ($servicios)
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                    <th>Servicio</th>
                    <th>Dirección</th>
                    <th>Monto</th>
                    <th>Ruta</th>
                    <th>Dia</th>
                </thead>
                <tbody>
                    @foreach ($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                            <td>
                                Calle
                                {{ $servicio->sucursal->sucursal->direccion . ' ' . $servicio->sucursal->sucursal->cp->cp . ' ' . $servicio->sucursal->sucursal->cp->estado->name . ' ' }}
                            
                            </td>
                            <td>{{$servicio->ruta_servicio? $servicio->ruta_servicio->monto :'0'}}</td>
                            <td>{{$servicio->ruta_servicio? $servicio->ruta_servicio->ruta->nombre->name:'Sin ruta' }}</td>
                            <td>{{$servicio->ruta_servicio? $servicio->ruta_servicio->ruta->dia->name:'Sin ruta' }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <strong>No hay servicios con rutas</strong>
        @endif
    </x-adminlte-modal>


    @push('js')
    <script>
          // detecto cuando cierra modal y limpio array
          $(document).ready(function() {
                $('#modalDetalles').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
                // $('#servicios_edit').on('hidden.bs.modal', function(event) {
                //     @this.dispatch('clean-servicios');
                // });
            });
    </script>
        
    @endpush
</div>
