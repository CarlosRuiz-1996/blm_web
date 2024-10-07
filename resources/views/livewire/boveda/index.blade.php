<div>
    <div class="container-fluid" wire:init='loadServicios'>
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Resguardo</span>

                @if ($readyToLoad)
                    <span class="info-box-number">

                        {{ number_format($resguardototal, 2, '.', ',') }} MXN
                    </span>
                @else
                    <div class="spinner-border" role="status"></div>
                @endif

                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                    <a href="{{ route('boveda.bovedaresguardo') }}">Más información</a>
                </span>
            </div>
        </div>



        <div class="card-outline card-info info-box">
            <div class="info-box-content">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab"
                            aria-controls="tab1" wire:ignore.self aria-selected="true">Cargar Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="rutaRecoleccion-tab" data-toggle="tab" href="#rutaRecoleccion"
                            role="tab" aria-controls="rutaRecoleccion" wire:ignore.self aria-selected="false">Ruta
                            Terminada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab"
                            aria-controls="tab2" wire:ignore.self aria-selected="false">Reporte de Movimiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="diferencia-tab" data-toggle="tab" href="#diferencia" role="tab"
                            aria-controls="diferencia" wire:ignore.self aria-selected="false">Acta de Diferencia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="serviciostotales-tab" data-toggle="tab" href="#serviciostotales" role="tab"
                            aria-controls="serviciostotales" wire:ignore.self aria-selected="false">Servicios Totales</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab"
                        wire:ignore.self>
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
                                        <th>Cargar</th>
                                    </tr>
                                </thead>
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    @if (count($servicios))
                                        @foreach ($servicios as $ruta)
                                            <tr>
                                                <td>{{ $ruta->id }}</td>
                                                <td>{{ $ruta->nombre->name }}</td>
                                                <td>{{ $ruta->dia->name }}</td>
                                                <td>{{ $ruta->riesgo->name }}</td>
                                                <td>{{ $ruta->estado->name }}</td>
                                                <td>{{ $ruta->hora_inicio }}</td>
                                                <td>{{ $ruta->hora_fin ?? 'No especificada' }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-info" data-toggle="modal"
                                                        data-target="#detalleModal"
                                                        wire:click='llenarmodalservicios({{ $ruta->id }})'>Cargar
                                                        ruta</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @if ($readyToLoad)
                                            <tr>
                                                <td colspan="8">No hay datos disponibles</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <div class="spinner-border"
                                                        style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                                        role="status">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginación -->

                        @if ($servicios && $servicios->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $servicios->links() }}
                            </div>
                        @endif

                    </div>
                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab"
                        wire:ignore.self>
                        <div class="mb-3 mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Filtrar por Ruta"
                                        wire:model.live="filtroRuta">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Filtrar por Servicio"
                                        wire:model.live="filtroServicio">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" placeholder="Filtrar por RFC Cliente"
                                        wire:model.live="filtroRFC">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" wire:model.live="filtroTipoServicio">
                                        <option value="">Tipo de Servicio</option>
                                        <option value="1">Entrega</option>
                                        <option value="2">Recolección</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" wire:model.live="filtroFecha">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-xs">Ruta</th>
                                        <th class="text-xs">Servicio</th>
                                        <th class="text-xs">RFC Cliente</th>
                                        <th class="text-xs">Tipo de servicio</th>
                                        <th class="text-xs">Estatus</th>
                                        <th class="text-xs">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($Movimientos))
                                        @foreach ($Movimientos as $movimiento)
                                            <tr>
                                                <td class="text-xs">{{ $movimiento->ruta->nombre->name }}</td>
                                                <td class="text-xs">
                                                    {{ $movimiento->servicio->ctg_servicio->descripcion }}
                                                </td>
                                                <td class="text-xs">{{ $movimiento->servicio->cliente->rfc_cliente }}
                                                </td>

                                                <td class="text-xs">
                                                    {{ $movimiento->tipo_servicio == 1 ? 'Entrega' : 'Recolección' }}
                                                </td>
                                                <td class="text-xs">
                                                    @if ($movimiento->tipo_servicio == 1)
                                                        <span
                                                            class="badge {{ $movimiento->status_ruta_servicio_reportes == 4 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $movimiento->status_ruta_servicio_reportes == 4
                                                                ? 'Servicio cargado'
                                                                : 'Servicio eliminado para esta ruta (reprogramar)' }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge {{ $movimiento->status_ruta_servicio_reportes == 4 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $movimiento->status_ruta_servicio_reportes == 4
                                                                ? 'Servicio
                                                                                                            Autorizado para recolectar'
                                                                : 'Servicio no autorizado para esta ruta
                                                                                                            (reprogramar)' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-xs">{{ $movimiento->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @if ($readyToLoad)
                                            <tr>
                                                <td colspan="8">No hay datos disponibles</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <div class="spinner-border"
                                                        style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                                        role="status">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($Movimientos && $Movimientos->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $Movimientos->links() }}
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="rutaRecoleccion" role="tabpanel"
                        aria-labelledby="rutaRecoleccion-tab" wire:ignore.self>
                        @livewire('boveda.ruta-recolecta')
                    </div>
                    <div class="tab-pane fade" id="diferencia" role="tabpanel" aria-labelledby="diferencia-tab"
                        wire:ignore.self>
                        @livewire('boveda.diferecia-valores')
                    </div>
                    <div class="tab-pane fade" id="serviciostotales" role="tabpanel" aria-labelledby="serviciostotales-tab"
                        wire:ignore.self>
                        @livewire('boveda.totales-servicios')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Cargar servicios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    @if ($serviciosRuta)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-info">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Sucursal</th>
                                        <th>Dirección</th>
                                        <th>Llaves</th>
                                        <th>Servicio</th>
                                        <th>Tipo Servicio</th>
                                        <th>Envases</th>
                                        <th style="width: 150px">Monto</th>
                                        <th>Cargado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviciosRuta as $rutaserv)
                                        <tr>
                                            <td>{{ $rutaserv->servicio->cliente->razon_social }}</td>
                                            <td>{{ $rutaserv->servicio->sucursal->sucursal->sucursal }}</td>
                                            <td>Calle
                                                {{ $rutaserv->servicio->sucursal->sucursal->direccion .
                                                    ', CP.' .
                                                    $rutaserv->servicio->sucursal->sucursal->cp->cp .
                                                    ', ' .
                                                    $rutaserv->servicio->sucursal->sucursal->cp->estado->name }}

                                            </td>
                                            <td>
                                                @if ($rutaserv->status_ruta_servicios != 0)
                                                    <a href="#" data-toggle="modal" class="btn btn-warning"
                                                        data-target="#keyModal"
                                                        wire:click='showKeys({{ $rutaserv->id }})'>Llaves</a>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        REPROGRAMADO
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $rutaserv->servicio->ctg_servicio->descripcion }}</td>
                                            <td>{{ $rutaserv->tipo_servicio == 1 ? 'ENTREGA' : 'RECOLECCIÓN' }}</td>
                                            @if ($rutaserv->tipo_servicio == 1)
                                                <td>
                                                    @if ($rutaserv->status_ruta_servicios != 0)
                                                        @if ($rutaserv->envase_cargado == 0)
                                                            <a href="#" data-toggle="modal"
                                                                class="btn btn-info"
                                                                data-target="#detalleModalEnvases"
                                                                wire:click='llenarmodalEnvases({{ $rutaserv->id }})'>Envases</a>
                                                        @else
                                                            <span class="badge bg-success">
                                                                CARGADO.
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            REPROGRAMADO
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>$ {{ number_format($rutaserv->monto, 2, '.', ',') }}</td>
                                            @else
                                                <td>N/A</td>
                                                <td>
                                                    @if ($rutaserv->monto != null && $rutaserv->monto != 0)
                                                        $ {{ number_format($rutaserv->monto, 2, '.', ',') }}
                                                    @else
                                                        Sin especificar
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if ($rutaserv->tipo_servicio == 1)
                                                    @if ($rutaserv->status_ruta_servicios == 1)
                                                        <button wire:click="cargar({{ $rutaserv->id }})"
                                                            class="btn btn-primary mb-3">Confirmar</button>
                                                        <button type="button" data-target="#exampleModalCerrar"
                                                            data-toggle="modal"
                                                            wire:click="cargarNoObtenerdatos({{ $rutaserv->id }})"
                                                            class="btn btn-danger">Rechazar</button>
                                                    @else
                                                        <span
                                                            class="badge {{ $rutaserv->status_ruta_servicios == 4 ? 'bg-success' : ($rutaserv->status_ruta_servicios == 0 ? 'bg-secondary' : 'bg-danger') }}">
                                                            {{ $rutaserv->status_ruta_servicios == 4
                                                                ? 'Servicio cargado'
                                                                : ($rutaserv->status_ruta_servicios == 0
                                                                    ? 'EN REPROGRAMACIÓN'
                                                                    : 'ERROR EN EL SERVICIO') }}

                                                        </span>
                                                    @endif
                                                @else
                                                    @if ($rutaserv->status_ruta_servicios == 1)
                                                        <button wire:click="cargarRecoleccion({{ $rutaserv->id }})"
                                                            class="btn btn-primary mb-3">Confirmar</button>
                                                        <button type="button" data-target="#exampleModalCerrar"
                                                            data-toggle="modal"
                                                            wire:click="cargarNoObtenerdatos({{ $rutaserv->id }})"
                                                            class="btn btn-danger">Rechazar</button>
                                                    @else
                                                        <span
                                                            class="badge {{ $rutaserv->status_ruta_servicios == 4 ? 'bg-success' : ($rutaserv->status_ruta_servicios == 0 ? 'bg-secondary' : 'bg-danger') }}">
                                                            {{ $rutaserv->status_ruta_servicios == 4
                                                                ? 'Servicio Autorizado para recolecta'
                                                                : ($rutaserv->status_ruta_servicios == 0
                                                                    ? 'EN REPROGRAMACIÓN'
                                                                    : 'ERROR EN EL SERVICIO')}}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                role="status">
                            </div>
                        </div>
                    @endif
                    @if (isset($compra_efectivo) && $compra_efectivo->isNotEmpty())
                        <div class="table-responsive">
                            <h2>Compra de efectivo</h2>
                            <table class="table">
                                <!-- Encabezados de la tabla -->
                                <thead class="table-info">
                                    <tr>
                                        <th>Monto compra</th>
                                        <th>Fecha requerida</th>
                                        <th>Detalles</th>
                                        <th>Cargar</th>
                                    </tr>
                                </thead>
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    @foreach ($compra_efectivo as $compra)
                                        <tr>
                                            <td>${{ number_format($compra->compra->total, 2, '.', ',') }}</td>

                                            <td>{{ $compra->compra->fecha_compra }}</td>
                                            <td>
                                                <button class="btn btn-info" data-toggle="modal"
                                                    wire:click="showCompraDetail({{ $compra->compra }})"
                                                    data-target="#modalDetailCompra">Detalles</button>
                                            </td>
                                            <td>
                                                @if ($compra->status_ruta_compra_efectivos == 1)
                                                    <button class="btn btn-primary"
                                                        wire:click="$dispatch('confirm-compra',[{{ $compra->compra }},2])">Confirmar</button>
                                                    <button class="btn btn-danger"
                                                        wire:click="$dispatch('confirm-compra',[{{ $compra->compra }},0])">Rechazar</button>
                                                @else
                                                    <span
                                                        class="badge bg-{{ $compra->status_ruta_compra_efectivos == 2 ? 'success' : 'secondary' }}">
                                                        {{ $compra->status_ruta_compra_efectivos == 2 ? 'ACEPTADO' : 'RECHAZADO' }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    @if ($serviciosRuta)

                    <div>
                        <span class="badge bg-success" style="font-weight: bold;"> Total de llaves: {{$total_keys}}</span>
                    </div>

                        {{-- $dispatch('confirm-serv',3) --}}
                        <button type="button" class="btn btn-info" wire:click="finailzar" wire:loading.remove>Mandar
                            a ruta</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>


                </div>
            </div>
        </div>
    </div>
    <!--modal motivo no cargar-->
    <div class="modal fade" id="exampleModalCerrar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Motivo de no cargar o recolectar servicios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3" hidden>
                            <x-input-validado label="Folio:" :readonly="true" placeholder=""
                                wire-model="idserviorutacancelado" wire-attribute="idserviorutacancelado"
                                type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-validado label="Motivo:" :readonly="false" placeholder="Ingrese el motivo"
                                wire-model="motivoNo" wire-attribute="motivoNo" type="text" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" data-dismiss="modal" wire:click="Nocargar">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!--llaves-->
    <div class="modal fade" id="keyModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Llaves al servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='cleanKeys'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @if ($ruta_servicio)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Cliente:" :readonly="true" placeholder="Cliente"
                                    wire-model="razon_social" wire-attribute="razon_social" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="No. Llave:" :readonly="false" placeholder="Ingresa la llave"
                                    wire-model="key" wire-attribute="key" type="text" />
                            </div>
                            <div class="col-md-1 mb-3" style="margin-top: 32px">
                                <button wire:click='AddKeys' class="btn btn-info" wire:loading.remove>Agregar</button>
                            </div>

                            <div class="col-md-12 mb-3">


                                @if (count($keys))
                                    <table class="table table-striped">
                                        <thead class="table-info">
                                            <th>Llave</th>
                                            <th>Eliminar</th>
                                        </thead>
                                        <tbody>

                                            @foreach ($keys as $key)
                                                <tr>
                                                    <td>{{ $key->key }}</td>

                                                    <td>
                                                        <button class="btn btn-danger"
                                                            wire:click="removeKey({{ $key->id }})"
                                                            id="id{{ $key->id }}"
                                                            name="name{{ $key->id }}"
                                                            wire:loading.attr="disabled"
                                                            wire:target="removeKey({{ $key->id }})">
                                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                                        </button>
                                                        <span wire:loading style="color: red"
                                                            wire:target="removeKey({{ $key->id }})">Eliminando...</span>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No hay llaves para este servicio.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center">

                            <div class="spinner-border text-center" role="status"></div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" wire:click='cleanKeys'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--modal envases de cargar-->
    <div class="modal fade" id="detalleModalEnvases" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Envases para Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <x-input-validadolive label="Papeleta/folio:" :readonly="!$canje?true:false"
                                placeholder="Papeleta/folio" wire-model="papeleta" wire-attribute="papeleta"
                                type="text" />
                        </div>
                        <div class="col-md-3 mb-3">


                            <div class="form-group">
                                <label for="">Monto {{ number_format($MontoRecolecta, 2, '.', ',') }}</label>
                                <input type="text" class="form-control w-full" placeholder="Monto total"
                                    wire:model.live='MontoRecolecta'
                                    {{!$canje?'readonly':''}}
                                    >
                            </div>
                        </div>
                        <div class="col-md-1 mb-3">


                            <div class="form-group">
                                <label for="">Canjear</label>
                                <input type="checkbox" class="form-control w-full" placeholder="Monto total"
                                    wire:model.live='canje'    
                                >
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <x-input-validadolive :readonly="false" label="Envases:"
                                placeholder="Ingrese cantidad de envases" wire-model="envasescantidad"
                                wire-attribute="envasescantidad" type="text" />

                        </div>
                        <div class="col-md-1" style="margin-top: 32px">

                            <button class="btn btn-info" wire:loading.remove
                                wire:click='envase_recolecta'>Agregar</button>
                        </div>

                        @foreach ($inputs as $index => $input)
                            <div class="col-md-6 mb-3">

                                <div class="form-group">
                                    <label for="">Monto del envase:
                                        {{ number_format((float) $input, 2, '.', ',') }}</label>

                                    <input type="number"
                                        class="form-control w-full @error('inputs.' . $index) is-invalid @enderror"
                                        placeholder="Monto del envase {{ $index + 1 }}"
                                        wire:model.live="inputs.{{ $index }}">
                                    @error('inputs.' . $index)
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Sello de seguridad:" :readonly="false"
                                    placeholder="Sello {{ $index + 1 }}" wire-model="sellos.{{ $index }}"
                                    wire-attribute="sellos.{{ $index }}" type="text" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" wire:click="GuardarEnvases" wire:loading.remove>Guardar</button>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="modalDetailCompra" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles de la compra</h5>
                    <button type="button" wire:click='limpiarDatosDetalleCompra' class="close"
                        data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-info">
                            <tr>
                                <th>Banco/Consignatario</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($readyToLoadModal)

                                @if ($compra_detalle && count($compra_detalle->detalles))
                                    @foreach ($compra_detalle->detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->consignatario->name }}</td>
                                            <td>${{ number_format($detalle->monto, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="8"> No hay movimientos</td>
                                    </tr>
                                @endif
                            @else
                                <tr class="text-center">
                                    <td colspan="8">
                                        <div class="spinner-border" role="status"></div>
                                    </td>
                                </tr>

                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatosDetalleCompra' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('confirm-compra', (data) => {
                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La compra sera procesada.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('confirmCompra-boveda', {
                                compra: data[0],
                                op: data[1]
                            });
                        }
                    })
                });

                @this.on('confirm-serv', (data) => {

                    console.log(data);

                    // finalizar-ruta 3

                    // Swal.fire({
                    //     title: '¿Estas seguro?',
                    //     text: "La compra sera procesada.",
                    //     icon: 'warning',
                    //     showCancelButton: true,
                    //     confirmButtonColor: '#3085d6',
                    //     cancelButtonColor: '#d33',
                    //     confirmButtonText: 'Si, adelante!',
                    //     cancelButtonText: 'Cancelar'
                    // }).then((result) => {
                    //     if (result.isConfirmed) {
                    //         @this.dispatch('confirmCompra-boveda', {
                    //             compra: data[0],
                    //             op: data[1]
                    //         });
                    //     }
                    // })
                });
                Livewire.on('error', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('success-compra', function(message) {
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                });

                Livewire.on('successservicioEnvases', function([message]) {
                    Swal.fire({
                        icon: message[1],
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        // Cerrar el modal después de que se muestra el mensaje de éxito
                        $('#detalleModalEnvases').modal('hide');
                    });
                });

                Livewire.on('success-fin-ruta', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'La ruta se mando a ruta con exito.',
                        text: 'El operador comenzara la ruta a la hora indicada en operaciones.',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        // Cerrar el modal después de que se muestra el mensaje de éxito
                        // $('#exampleModalCerrar').modal('hide');
                        $('#detalleModal').modal('hide');
                    });
                });
                Livewire.on('error-fin-ruta', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Aun falta que se carguen servicios',
                        // text:'El operador comenzara la ruta a la hora indicada en operaciones.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('error-fin-ruta-compra', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Aun quedan compras por revisar.',
                        // text:'El operador comenzara la ruta a la hora indicada en operaciones.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('error-envases', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Aun falta que se carguen evases en las entregas',
                        // text:'El operador comenzara la ruta a la hora indicada en operaciones.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

                $('#keyModal').on('hidden.bs.modal', function() {
                    $('#detalleModal').modal('show');
                    @this.call('cleanKeys');
                });
            });

            // Ocultar el primer modal cuando se muestra el segundo modal
            $('#exampleModalCerrar').on('show.bs.modal', function() {
                $('#detalleModal').modal('hide');
            });
            // Mostrar el primer modal cuando se cierra el segundo modal
            $('#exampleModalCerrar').on('hidden.bs.modal', function() {
                $('#detalleModal').modal('show');
            });

            // Ocultar el segundo modal cuando se muestra el primer modal
            $('#detalleModal').on('show.bs.modal', function() {
                $('#exampleModalCerrar').modal('hide');
                $('#detalleModalEnvases').modal('hide');
                $('#modalDetailCompra').modal('hide');
            });


            // Ocultar el primer modal cuando se muestra el segundo modal ENVASES
            $('#detalleModalEnvases').on('show.bs.modal', function() {
                $('#detalleModal').modal('hide');
            });
            $('#detalleModalEnvases').on('hidden.bs.modal', function() {
                $('#detalleModal').modal('show');
            });

            // Ocultar el primer modal cuando se muestra el segundo modal COMPRAS
            $('#modalDetailCompra').on('show.bs.modal', function() {
                $('#detalleModal').modal('hide');
            });
            $('#modalDetailCompra').on('hidden.bs.modal', function() {
                $('#detalleModal').modal('show');
            });
            // Ocultar el primer modal cuando se muestra el segundo modal llaves
            $('#keyModal').on('show.bs.modal', function() {
                $('#detalleModal').modal('hide');
            });
        </script>
    @endpush
</div>
