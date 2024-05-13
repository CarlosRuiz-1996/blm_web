<div>
    <div class="container-fluid">
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
        <div class="card-outline card-info info-box">
            <div class="info-box-content">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Cargar Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Reporte de Movimiento</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                        <!-- Contenido de la pestaña 1 -->
                        <div class="table-responsive">
                            <table class="table">
                                <!-- Encabezados de la tabla -->
                                <thead class="table-info">
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
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    @foreach ($servicios as $ruta)
                                        <tr>
                                            <td>{{ $ruta->id }}</td>
                                            <td>{{ $ruta->nombre->name }}</td>
                                            <td>{{ $ruta->dia->name }}</td>
                                            <td>{{ $ruta->riesgo->name }}</td>
                                            <td>{{ $ruta->estado->name }}</td>
                                            <td>{{ $ruta->hora_inicio }}</td>
                                            <td>{{ $ruta->hora_fin }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#detalleModal" wire:click='llenarmodalservicios({{$ruta->id}})'>Detalles</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginación -->
                        {{ $servicios->links() }}
                    </div>
                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <!-- Encabezados de la tabla -->
                                <thead class="table-info">
                                    <tr>
                                        <th>Ruta</th>
                                        <th>Servicio</th>
                                        <th>Tipo de servicio</th>
                                        <th>Estatus</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    @foreach ($Movimientos as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->ruta_id }}</td>
                                            <td>{{ $movimiento->servicio_id }}</td>
                                            <td>{{ $movimiento->tipo_servicio }}</td>
                                            <td><span class="badge {{ $movimiento->status_ruta_servicio_reportes == 2 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $movimiento->status_ruta_servicio_reportes == 2 ? 'Servicio cargado' : 'Servicio eliminado (reprogramar)' }}
                                            </span>
                                            </td>
                                            <td>{{ $movimiento->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginación -->
                        {{ $Movimientos->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
        
    <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Detalles de la Ruta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-info">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Dirección</th>
                                    <th>Llaves</th>
                                    <th>Servicio</th>
                                    <th>Tipo Servicio</th>
                                    <th>Monto</th>
                                    <th>Cargado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($serviciosRuta)
                                @foreach ($serviciosRuta as $rutaserv)
                                    <tr>
                                        <td>{{ $rutaserv->servicio->cliente->razon_social }}</td>
                                        <td>Direccion</td>
                                        <td></td>
                                        <td>{{ $rutaserv->servicio->ctg_servicio->descripcion }}</td>
                                        <td>{{ $rutaserv->tipo_servicio == 1 ? 'Entrega' : 'Recolección' }}</td>
                                        <td>$ {{ number_format($rutaserv->monto, 2, '.', ',') }}</td>
                                        <td> 
                                            @if($rutaserv->status_ruta_servicios==1)
                                            <button wire:click="cargar({{ $rutaserv->servicio_id }}, {{ $rutaserv->ruta_id }})" class="btn btn-primary">Si</button>
                                            <button type="button" data-target="#exampleModalToggle2" data-toggle="modal"  wire:click="cargarNoObtenerdatos({{ $rutaserv->servicio_id }}, {{ $rutaserv->ruta_id }})" class="btn btn-danger">No</button>
                                           @else
                                                <span
                                                    class="badge {{ $rutaserv->status_ruta_servicios ==2 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $rutaserv->status_ruta_servicios ==2 ? 'Servicio cargado' : 'Error en el servicio'
                                                    }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>                              
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--modal motivo no cargar-->
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Motivo de no cargar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3" hidden>
                        <x-input-validado label="Folio:" :readonly="true"
                            placeholder="" wire-model="idserviorutacancelado"
                            wire-attribute="idserviorutacancelado" type="text" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <x-input-validado label="Motivo:" :readonly="false" placeholder="Ingrese el motivo"
                            wire-model="motivoNo" wire-attribute="motivoNo" type="text" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-target="#detalleModal" data-toggle="modal">Cerrar</button>
                <button class="btn btn-primary" wire:click="Nocargar">Aceptar</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La cotizacion se generara y comenzara el proceso de contratación.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('save-cotizacion');
                        }
                    })
                })

                Livewire.on('success-cotizacion', function([message]) {
                    console.log(message);

                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            window.location.href = '/ventas/detalle-cotizacion/' + message[1];

                        }
                    });
                });


                Livewire.on('error', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

                Livewire.on('errorTablaDatos', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('errorTabla', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('successservicio', function(message) {
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        // Cerrar el modal después de que se muestra el mensaje de éxito
                        $('#exampleModalToggle2').modal('hide');
                    });
                });
                

            });

              // Ocultar el primer modal cuando se muestra el segundo modal
                $('#exampleModalToggle2').on('show.bs.modal', function () {
                    $('#detalleModal').modal('hide');
                });

                // Mostrar el primer modal cuando se cierra el segundo modal
                $('#exampleModalToggle2').on('hidden.bs.modal', function () {
                    $('#detalleModal').modal('show');
                });

                // Ocultar el segundo modal cuando se muestra el primer modal
                $('#detalleModal').on('show.bs.modal', function () {
                    $('#exampleModalToggle2').modal('hide');
                });

        
            
    </script>


    @endpush
    
</div>
