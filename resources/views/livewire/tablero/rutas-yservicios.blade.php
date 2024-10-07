<div>
    <style>
        .modal-lg {
            max-width: 80% !important; /* Ajusta el tamaño máximo del modal */
        }

        /* Contenedor de la imagen para manejar la imagen responsiva */
        .img-container {
            position: relative;
            width: 100%;
            height: auto;
        }

        /* Imagen responsiva */
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
    </style>
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
                        $totalServicios = $rutas->rutaServicios->where('status_ruta_servicios', '<',6)->count();

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

            <table class="table table-striped text-xs">
                <thead class="table-dark ">
                    <tr>
                        <th scope="col"><i class="fas fa-road me-2"></i> Ruta</th>
                        <th scope="col"><i class="fas fa-truck"></i> Servicio</th>
                        <th scope="col"><i class="fas fa-user me-2"></i> Cliente</th>
                        <th scope="col"><i class="fas fa-round me-2"></i>Sucursal</th>
                        <th scope="col"><i class="fas fa-round me-2"></i>Monto</th>
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
                    @foreach ($rutas->rutaServicios->where('status_ruta_servicios','!=',6) as $servicios)
                    
                    <tr>

                        <td>{{$servicios->ruta->nombre->name}}</td>
                        <td>{{$servicios->servicio->ctg_servicio->descripcion}}</td>
                        <td>{{ $servicios->servicio->cliente->razon_social }}</td>
                        <td> 
                                {{ $servicios->servicio->sucursal->sucursal->sucursal }},
                                {{ $servicios->servicio->sucursal->sucursal->direccion }},
                                {{ $servicios->servicio->sucursal->sucursal->cp->cp }},
                                {{ $servicios->servicio->sucursal->sucursal->cp->estado->name }}
                            </td>
                        <td>
                            <button data-toggle="modal" class="btn btn-warning" data-target="#showModalMontos"
                                wire:click="rutamonto({{$servicios->id}})">
                                ${{ number_format($servicios->monto, 2, '.', ',') }}
                            </button>
                        </td>
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
                        @if($servicios->status_ruta_servicios == 3)
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#evidenciaModal"
                                wire:click="{{ $servicios->tipo_servicio == 1 ? 'evidenciaEntrega' : 'evidenciaRecolecta' }}({{ $servicios->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                    <path d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                    <path d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                </svg>
                            </button>
                        </td>
                        @elseif($servicios->status_ruta_servicios==4)
                        <td>
                            Servicio sin Iniciar
                        </td>
                        @else
                        <td>
                            Servicio sin terminar
                        </td>
                    @endif
                    </tr>
                    @endforeach
                    @foreach ($rutas->ruta_compra as $ruta_compra)
                    @if ($ruta_compra->status_ruta_compra_efectivos != 5)
                    <tr>
                        <td>{{$ruta_compra->ruta->nombre->name}}</td>
                        <td colspan="3">COMPRA DE EFECTIVO BANCOS</td>
                        <td>
                            @if ($ruta_compra && count($ruta_compra->compra->detalles))
                            
                            @foreach($ruta_compra->compra->detalles as $detallesa)
                            {{ $detallesa->consignatario->name }}
                            @php
                            $detalleaaa = $detallesa;
                            @endphp
                            @endforeach
                            @endif
                        </td>
                        <td>{{$ruta_compra->created_at->format('H:i:s')}}</td>
                        <td>{{$ruta_compra->created_at->format('H:i:s')}}</td>
                        <td><div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;">0%</div>
                            </div>
                        </td>
                        <td>Terminado</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal"data-target="#evidenciaModal"
                                        wire:click='evidenciaCompra({{ $detalleaaa }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                            <path
                                                d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                            <path
                                                d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                        </svg>
                                    </button>
                        </td>
                       
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </tbody>

                @endif
            </table>
        </div>


<!-- Modal -->
<div class="modal fade" id="evidenciaModal" wire:ignore.self tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Evidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Tu contenido del modal aquí -->
                @if ($readyToLoadModal)
                    <div id="evidenciaCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($evidencia_foto as $index => $foto)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $foto) }}" class="d-block w-100 h-100" alt="Evidencia" style="width: 300px; height: 350px;">
                                </div>
                            @endforeach
                        </div>
                        <!-- Controles del carrusel -->
                        <button class="carousel-control-prev" type="button" data-target="#evidenciaCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-target="#evidenciaCarousel" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                @else
                    <div class="col-md-12 text-center">
                        <div class="spinner-border" role="status"></div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal fuera del div x-data -->
<!-- Modal -->
<div class="modal fade" id="showModalMontos" wire:ignore.self tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">MONTOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form wire:submit.prevent="updateService"> 
                    <div class="modal-body">
                        <div class="row">
                            @foreach($serviciosRutasevidencias as $index => $servicio)
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidad-{{ $index }}">Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad-{{ $index }}" wire:model="serviciosRutasevidencias.{{ $index }}.cantidad">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="sello_seguridad-{{ $index }}">Sello de Seguridad</label>
                                        <input type="text" class="form-control" id="sello_seguridad-{{ $index }}" wire:model="serviciosRutasevidencias.{{ $index }}.sello_seguridad" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if($servicio['tipo_servicio'] == "2") 
                                        <img src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_' . $servicio['ruta_servicios']['id'] . '_recolecta_' . $servicio['evidencia_recolecta']['id'] . '_evidencia.png?v=' . time()) }}" alt="Evidencia" class="img-fluid mb-2" style="width: 150px; height: 150px;">
                                    @else
                                        <img src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_' . $servicio['ruta_servicios']['id'] . '_entrega_' . $servicio['evidencia_entrega']['id'] . '_evidencia.png?v=' . time()) }}" alt="Evidencia" class="img-fluid mb-2" style="width: 150px; height: 150px;">
                                    @endif                                    
                                    </div>
                                </div>                               
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="evidencianueva-{{ $index }}">Cargar Nueva Evidencia</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="evidencianueva-{{ $index }}" wire:model.live="serviciosRutasevidencias.{{ $index }}.evidencianueva" accept="image/*">
                                            <label class="custom-file-label text-left" placeholder="Cargar" for="evidencianueva-{{ $index }}">Archivo</label>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        @if (isset($servicio['evidencianueva']))
                                        <img src="{{ $servicio['evidencianueva']->temporaryUrl() }}" alt="Vista Previa" class="img-fluid mb-2" style="width: 150px; height: 150px;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
                
                
            </div>
        </div>
    </div>
</div>





    </div>