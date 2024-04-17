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
                            <th class="col-md-2">Servicios</th>
                            <th class="col-md-1">Estatus</th>
                            <th class="col-md-2 text-center">Entrega</th>
                            <th class="col-md-2 text-center">Recoleccion</th>
                            <th class="col-md-2">Cantidad Entrega</th>
                            <th class="col-md-2">Cantidad Recoleccion</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->descripcion }}</td>
                                <td>
                                    @if ($servicio->status_ruta_servicios == 1)
                                        <span class="text-primary">En proceso</span>
                                    @elseif ($servicio->status_ruta_servicios == 2)
                                        <span class="text-warning">En ruta</span>
                                    @elseif ($servicio->status_ruta_servicios == 3)
                                        <span class="text-success">Finalizado</span>
                                    @endif
                                </td>                                
                                <td class="text-success text-center">
                                    @if ($servicio->tipo_servicio == 1 || $servicio->tipo_servicio == 3)
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($servicio->tipo_servicio == 2 || $servicio->tipo_servicio == 3)
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($servicio->tipo_servicio == 1 || $servicio->tipo_servicio == 3)
                                    {{ $servicio->monto }}
                                    @endif
                                </td>
                                <td>
                                    @if ($servicio->tipo_servicio == 2 || $servicio->tipo_servicio == 3)
                                    {{ $servicio->monto }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>                              
                </table>
       </div> 
    </div>  
    </div>
</div>
