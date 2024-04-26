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
        <div class="info-box">
            <div class="info-box-content">
            <div class="table-responsive">
                <table class="table">
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
       {{ $servicios->links() }}
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
                                    <th>¿ Cargado ?</th>
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
                                            <button wire:click="cargar({{ $rutaserv->servicio_id }})" class="btn btn-primary">Si</button>
                                           @else
                                            <button wire:click="cancelarCargar({{ $rutaserv->servicio_id }})" class="btn btn-primary">descargar</button>
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
    
</div>
