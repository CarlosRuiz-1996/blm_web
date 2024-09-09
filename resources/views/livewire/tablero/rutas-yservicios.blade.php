<div>
    <div class="container-fluid">
        <h1 class="text-center mb-4">Tablero de Rutas</h1>

        <div class="row">
            @if($rutaEmpleados)
            @foreach ($rutaEmpleados as $rutas)


            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                        <h5 class="mb-3">
                            <i class="fas fa-car me-2 text-success"></i> Ruta : {{$rutas->nombre->name}}
                        </h5>
                        </p>
                        <p>
                        <h6>
                            Hora Inicio Estimada:{{$rutas->hora_inicio}}
                        </h6>
                        </p>
                        <p>
                        <h6>
                            Hora Fin Estimada:{{$rutas->hora_fin}}
                        </h6>
                        </p>
                        @php
                        // Contar total de servicios en la ruta
                        $totalServicios = $rutas->rutaServicios->count();

                        // Contar servicios que están completados (por ejemplo, status_ruta_servicios = 2)
                        $serviciosCompletados = $rutas->rutaServicios->where('status_ruta_servicios', 3)->count();

                        // Calcular el porcentaje de progreso
                        $porcentajeProgreso = $totalServicios > 0 ? ($serviciosCompletados / $totalServicios) * 100 : 0;
                        @endphp
                        <div class="progress mb-3">
                            @if($rutas->ctg_rutas_estado_id == 3)
                            @if($rutas->status_ruta == 1)
                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;">0%</div>
                            @elseif($rutas->status_ruta == 2)
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $porcentajeProgreso }}%;">{{ $porcentajeProgreso }}%</div>
                            @endif
                            @elseif($rutas->ctg_rutas_estado_id == 4 && $rutas->status_ruta == 3)
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;">100%</div>
                            @endif
                        </div>
                        @if($rutas->ctg_rutas_estado_id==3)
                        @if($rutas->status_ruta==1)
                        <p class="card-text">Estatus : Sin iniciar</p>
                        @elseif($rutas->status_ruta==2)
                        <p class="card-text">Estatus : EN PROCESO/RUTA</p>
                        @endif
                        @elseif($rutas->ctg_rutas_estado_id==4 && $rutas->status_ruta == 3)
                        <p class="card-text">Estatus : {{$rutas->estado->name}}</p>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <div class="container-fluid">
            <h1 class="text-center mb-4">Estado de Rutas</h1>

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"><i class="fas fa-road me-2"></i> Ruta</th>
                        <th scope="col"><i class="fas fa-road me-2"></i> Servicio</th>
                        <th scope="col"><i class="fas fa-tachometer-alt me-2"></i> Hora inicio</th>
                        <th scope="col"><i class="fas fa-tachometer-alt me-2"></i> Hora fin</th>
                        <th scope="col">Proceso</th>
                        <th scope="col"><i class="fas fa-info-circle me-2"></i> Estado</th>
                        <th scope="col"><i class="fas fa-image"></i> Evidencia</th>
                    </tr>
                </thead>
                <tbody>
                    @if($rutaEmpleados)
                    @foreach ($rutaEmpleados as $rutas)
                    @foreach ($rutas->rutaServicios as $servicios)
                    <tr>

                        <td>{{$servicios->ruta->nombre->name}}</td>
                        <td>{{$servicios->servicio->ctg_servicio->descripcion}}</td>
                        @if($servicios->status_ruta_servicios==4)
                        <td>Sin iniciar</td>
                        <td>Sin iniciar</td>
                        @elseif($servicios->status_ruta_servicios==2)
                        <td>{{ $servicios->created_at->format('H:i:s') }}</td>
                        <td>Sin Terminar</td>
                        @elseif($servicios->status_ruta_servicios==3)
                        <td>{{ $servicios->created_at->format('H:i:s') }}</td>
                        <td>{{ $servicios->updated_at->format('H:i:s') }}</td>
                        @else
                        <td>Sin informacion</td>
                        <td>Sin informacion</td>
                        @endif
                        <td>
                            @if($servicios->status_ruta_servicios==4)
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;">0%</div>
                            </div>
                            @elseif($servicios->status_ruta_servicios==2)
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 50%;">50%</div>
                            </div>
                            @elseif($servicios->status_ruta_servicios==3)
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;">100%</div>
                            </div>
                            @else
                            Sin informacion
                            @endif
                        </td>
                        <td>
                            @if($servicios->status_ruta_servicios==4)
                            Sin iniciar
                            @elseif($servicios->status_ruta_servicios==2)
                            En progreso
                            @elseif($servicios->status_ruta_servicios==3)
                            Terminado
                            @else
                            Sin informacion
                            @endif
                        </td>
                        <td>asas</td>
                    </tr>
                    @endforeach
                    @foreach ($rutas->ruta_compra as $ruta_compra)
                    @if ($ruta_compra->status_ruta_compra_efectivos != 5)
                    <tr>
                        <td>{{$ruta_compra->ruta->nombre->name}}</td>
                        <td>COMPRA DE EFECTIVO BANCOS</td>
                        <td>{{$ruta_compra->created_at->format('H:i:s')}}</td>
                        <td>{{$ruta_compra->created_at->format('H:i:s')}}</td>
                        <td><div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;">0%</div>
                            </div>
                        </td>
                        <td>Terminado</td>
                        <td>adasdsa</td>
                       
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </tbody>

                @endif
            </table>
        </div>
    </div>