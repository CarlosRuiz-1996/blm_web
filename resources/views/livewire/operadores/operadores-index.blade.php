<div>
    <div class="container-fluid pt-5">
        @if ($identificado)
            <h3 class="ml-2">Operador-<span class="text-info">{{ $nombreUsuario }}</span></h3>
            <div class="card">
                <div class="card-body">
                    @if (count($rutaEmpleados) > 0)
                        <div class="table-responsive">
                            <table class="table" x-data="{ open: false }">
                                @foreach ($rutaEmpleados as $index => $rutaServicio)
                                    <thead >
                                        <tr @click="open === {{ $index }} ? open = false : open = {{ $index }}">
                                            <th colspan='8' class="text-center table-secondary">Ruta
                                                {{ $rutaServicio->nombre->name . '-' . $rutaServicio->dia->name }}
                                                <i :class="open === {{ $index }} ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-2"></i>

                                            </th>
                                        </tr>
                                        <tr x-show="open === {{ $index }}" class="table-info">
                                            <th colspan="2">Hora Inicio</th>
                                            <th>Hora Termino</th>
                                            <th>Dia</th>
                                            <th>Riesgo</th>
                                            <th>Estado</th>
                                            <th colspan="2">Iniciar/Terminar</th>
                                        </tr>
                                    </thead>
                                    <tbody x-show="open === {{ $index }}">
                                        <tr>

                                            <td colspan="2">{{ $rutaServicio->hora_inicio }}</td>
                                            <td>{{ $rutaServicio->hora_fin ?? 'N/A' }}</td>
                                            <td>{{ $rutaServicio->dia->name }}</td>
                                            <td>{{ $rutaServicio->riesgo->name }}</td>
                                            <td>{{ $rutaServicio->estado->name }}</td>
                                            <td colspan="2">
                                                @if ($rutaServicio->status_ruta == 1)
                                                    <button type="button"
                                                        wire:click="$dispatch('confirm',{{ $rutaServicio->id }})"
                                                        class="btn btn-primary"><i class="fas fa-play mr-1"></i>
                                                        Iniciar</button>
                                                @elseif($rutaServicio->status_ruta == 2)
                                                    <button type="button"
                                                        wire:click="$dispatch('terminar',{{ $rutaServicio->id }})"
                                                        class="btn btn-danger"><i class="fas fa-stop mr-1"></i>
                                                        Terminar</button>
                                                @elseif($rutaServicio->status_ruta == 3)
                                                    <span style="color: green;"><i class="fas fa-check-circle"></i> Ruta
                                                        completada</span>
                                                @endif
                                            </td>
                                        </tr>

                                        @if ($rutaServicio->status_ruta != 1)
                                            <tr class="table-primary">
                                                <th colspan="3">Servicio</th>
                                                <th>Papeleta</th>
                                                <th>Envases</th>
                                                <th>Tipo de Servicio</th>
                                                <th>Acción</th>
                                            </tr>
                                            @foreach ($rutaServicio->rutaServicios as $servicio)
                                                <tr>
                                                    <td colspan="3">
                                                        {{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                                    <td>{{ $servicio->folio }}</td>
                                                    <td>{{ $servicio->envases }}</td>
                                                    <td>
                                                        {{ $servicio->tipo_servicio == 1 ? 'Entrega' : ($servicio->tipo_servicio == 2 ? 'Recolección' : 'Otro') }}
                                                    </td>
                                                    <td>
                                                        @if ($servicio->status_ruta_servicios == 2)
                                                            <button type="button" class="btn btn-primary"
                                                                wire:click="ModalEntregaRecolecta('{{ $servicio->id }}','{{ $servicio->tipo_servicio }}')"
                                                                data-toggle="modal"
                                                                data-target="#ModalEntregarRecolectar">
                                                                <i class="fas fa-play mr-1"></i>
                                                                {{ $servicio->tipo_servicio == 1 ? 'Entregar' : ($servicio->tipo_servicio == 2 ? 'Recolectar' : 'Otro') }}
                                                            </button>
                                                            <button type="button" class="btn btn-danger"
                                                                wire:click="ModalReprogramarServicio('{{ $servicio->id }}')"
                                                                data-toggle="modal"
                                                                data-target="#ModalReprogramarServicio"><i
                                                                    class="fas fa-clock mr-1"></i>
                                                                Reprogramar</button>
                                                        @else
                                                            <span
                                                                class="badge {{ $servicio->status_ruta_servicios == 3 ? 'bg-success' : 'bg-warning' }}">
                                                                {{ $servicio->status_ruta_servicios == 3 ? 'Terminado' : 'Reprogramado' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach


                                            <tr>
                                                <th colspan='8' class="text-center table-success">Compra efectivo
                                                </th>
                                            </tr>
                                            <tr class="table-success">
                                                <th colspan="2">Monto de la compra</th>
                                                <th colspan="2">Fecha de la compra</th>
                                                <th colspan="2">Estatus de la compra</th>
                                                <th colspan="2">Detalles de la compra</th>
                                            </tr>
                                            @foreach ($rutaServicio->ruta_compra as $ruta_compra)
                                                <tr>
                                                    <td colspan="2">
                                                        ${{ number_format($ruta_compra->compra->total, 2, '.', ',') }}
                                                    </td>

                                                    <td colspan="2">{{ $ruta_compra->compra->fecha_compra }}</td>
                                                    <td colspan="2">
                                                        <span
                                                            class="badge {{ $ruta_compra->compra->status_compra_efectivos == 2 ? 'bg-warning' : 'bg-success' }}">
                                                            {{ $ruta_compra->compra->status_compra_efectivos == 2 ? 'Pendiente' : 'Finalizada' }}
                                                        </span>
                                                    </td>
                                                    <td colspan="2">
                                                        <button class="btn btn-info" data-toggle="modal"
                                                            wire:click="showCompraDetail({{ $ruta_compra->compra }})"
                                                            data-target="#modalDetailCompra">Detalles</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- @break --}}
                                        @else
                                            <tr>
                                                <td colspan="7">
                                                    <div class="card bg-warning">
                                                        <div class="card-body text-center">
                                                            <h5 class="card-text text-center">Sin iniciar ruta</h5>
                                                            <p class="card-text text-center">Iniciar Ruta para ver
                                                                Servicios</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                @endforeach


                            </table>


                        </div>
                    @else
                        <div class="card bg-info">
                            <div class="card-body text-center">
                                <h5 class="card-text text-center">Sin Rutas Asignadas</h5>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card bg-danger">
                <div class="card-body text-center">
                    <h5 class="card-text text-center">No autorizado</h5>
                    <p class="card-text text-center">Área no apta para visualizar datos del operador.</p>
                </div>
            </div>
        @endif
    </div>
    <style>
        #message {
            display: none;
            /* El mensaje está oculto por defecto */
        }

        .input-group.is-invalid {
            border: 1px solid #dc3545;
            /* Rojo de Bootstrap */
            border-radius: 0.25rem;
            /* Asegura que el borde tenga el mismo radio que los inputs */
            padding: 0.375rem 0.75rem;
            /* Ajusta el padding para que el contenido no se sobreponga al borde */
        }
    </style>
    <div class="modal fade" id="ModalEntregarRecolectar" wire:ignore.self tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">
                        @if ($tiposervicio == 'Entrega')
                            <i class="fas fa-box mr-1"></i>
                        @elseif($tiposervicio == 'Recolección')
                            <i class="fas fa-hand-holding mr-1"></i>
                        @endif
                        {{ $tiposervicio }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" wire:init='loadServicios'>
                        @if ($readyToLoadModal)
                            <div class="col-md-12 mb-3" hidden>
                                <x-input-validado :readonly="true" label="idrecolecta:" placeholder="idrecolecta"
                                    wire-model="idrecolecta" wire-attribute="idrecolecta" type="text" />
                            </div>
                            <div class="col-md-12 mb-3" hidden>
                                <x-input-validado :readonly="true" label="Monto:" placeholder="Ingrese Monto"
                                    wire-model="MontoEntrega" wire-attribute="MontoEntrega" type="text" />
                            </div>
                            @if ($tiposervicio)
                                <div class="{{ $tiposervicio == 'Recolección' ? 'col-md-2' : 'col-md-12' }} mb-3">
                                    <x-input-validado label="Papeleta:" :readonly="true" placeholder="Papeleta"
                                        wire-model="papeleta" type="text" />
                                </div>
                            @endif
                            @if ($tiposervicio == 'Recolección')
                                <div class="col-md-4 mb-3">
                                    {{-- <x-input-validado :readonly="false" label="Monto:" placeholder="Ingrese Monto total"
                                wire-model="MontoRecolecta" wire-attribute="MontoRecolecta" type="text" /> --}}
                                    <label for="">Monto de la recolecta: $
                                        {{ number_format($MontoRecolecta, 2, '.', ',') }}</label>
                                    <input type="number"
                                        class="form-control @error('MontoRecolecta') is-invalid @enderror"
                                        wire:model.live='MontoRecolecta'>
                                    @error('MontoRecolecta')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-1">
                                    <x-input-validadolive :readonly="false" label="Envases:"
                                        placeholder="Ingrese cantidad de envases" wire-model="envasescantidad"
                                        wire-attribute="envasescantidad" type="text" />

                                </div>
                                <div class="col-md-2" style="margin-top: 32px">

                                    <button class="btn btn-info" wire:loading.remove
                                        wire:click='envase_recolecta'>Agregar</button>
                                </div>
                            @endif
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-warning" id="message" role="alert">
                                    No puedes llevar envases dañados.
                                </div>
                            </div>



                            @foreach ($inputs as $index => $input)
                                <div class="col-md-1 mb-3" {{ $tiposervicio == 'Entrega' ? 'hidden' : '' }}>
                                    <Label>Violado</Label>
                                    <input label="Cantidad:" id="violado.{{ $index }}"
                                        onclick="select_violado(this)"
                                        wire:model="inputs.{{ $index }}.violado" type="checkbox" />

                                </div>


                                <div class="{{ $tiposervicio == 'Recolección' ? 'col-md-2' : 'col-md-3' }} mb-3">
                                    {{-- <x-input-validado label="Cantidad:" :readonly="$tiposervicio == 'Entrega'" placeholder="Envase"
                                wire-model="inputs.{{ $index }}.cantidad" wire-attribute="inputs"
                                type="text" /> --}}
                                    <label for="">Monto: $
                                        {{ number_format((float) $input['cantidad'], 2, '.', ',') }}
                                    </label>
                                    <input type="number"
                                        class="form-control @error('inputs.' . $index . '.cantidad') is-invalid @enderror"
                                        wire:model.live="inputs.{{ $index }}.cantidad">
                                    @error('inputs.' . $index . '.cantidad')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror

                                </div>

                                @if ($tiposervicio == 'Recolección')
                                    <div class="col-md-2 mb-3">
                                        <x-input-validado label="Papeleta:" :readonly="false" placeholder="Papeleta"
                                            wire-model="inputs.{{ $index }}.folio" wire-attribute="folios"
                                            type="text" />
                                    </div>
                                @endif
                                <div class="{{ $tiposervicio == 'Recolección' ? 'col-md-2' : 'col-md-3' }} mb-3">
                                    <x-input-validado label="Sello seguridad:" :readonly="false"
                                        placeholder="Sello de seguridad"
                                        wire-model="inputs.{{ $index }}.sello" type="text" />
                                </div>
                                <div class="col-md-2 mb-3">
                                    <x-input-validado label="Evidencia:" :readonly="false" placeholder="Evidencia"
                                        wire:model="inputs.{{ $index }}.photo" type="file" />
                                </div>
                                <div class="col-md-3 d-flex justify-content-center align-items-center">
                                    <!-- Muestra la imagen cargada si existe -->
                                    @if (isset($input['photo']) && $input['photo'])
                                        <img src="{{ $input['photo']->temporaryUrl() }}" alt="Foto Tomada"
                                            class="img-fluid" style="max-width: 100px; height: auto;">
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" role="status">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @if ($tiposervicio == 'Entrega')
                        <button type="button" class="btn btn-primary" wire:loading.remove
                            wire:click='ModalAceptar'>Aceptar</button>
                    @elseif($tiposervicio == 'Recolección')
                        <button type="button" class="btn btn-primary" wire:loading.remove
                            wire:click='ModalAceptarRecolecta'>Aceptar</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    {{-- modal reprogramar --}}
    <div class="modal fade" id="ModalReprogramarServicio" wire:ignore.self tabindex="-1" role="dialog"
        aria-labelledby="ModalReprogramarServicioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalReprogramarServicioLabel">
                        <i class="fas fa-clock mr-1"></i>Reprogramar
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3" hidden>
                            <x-input-validado :readonly="true" label="Id servicio" placeholder="Idservicio"
                                wire-model="IdservicioReprogramar" wire-attribute="IdservicioReprogramar"
                                type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-validado :readonly="false" label="Motivo Reprogramación:"
                                placeholder="Ingrese el motivo para reprogramar"
                                wire-model="motivoReprogramarConcepto" wire-attribute="motivoReprogramarConcepto"
                                type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <!-- Input file que abre la cámara -->

                            @error('photorepro')
                                <div class="text-danger text-xs">{{ $message }}</div>
                            @enderror
                            <input type="file" accept="image/*" capture="camera" id="photorepro"
                                wire:model.live="photorepro" style="display: none;">
                            <button type="button" class="btn btn-primary btn-block"
                                onclick="document.getElementById('photorepro').click();"><i
                                    class="fas fa-camera"></i></button>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center align-items-center">
                            <!-- Vista previa de la foto tomada -->
                            @if ($photorepro)
                                <img src="{{ $photorepro->temporaryUrl() }}" alt="Foto Tomada" class="img-fluid"
                                    style="max-width: 25%; height: auto;">
                            @endif
                        </div>
                        <div class="col-md-12 mt-3 mb-2 text-center">
                            <div wire:loading wire:target="photorepro">
                                <div class="d-flex justify-content-center align-items-center"
                                    style="min-height: 50px;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Cargando archivo...</span>
                                    </div>
                                    <span class="ml-2">Cargando archivo...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @if (!$photorepro) disabled @endif
                        wire:loading.remove wire:click='ModalAceptarReprogramar'>Aceptar</button>
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
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-info">
                            <tr>
                                <th>Banco/Consignatario</th>
                                <th>Monto</th>
                                {{-- <th>Estatus</th> --}}
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($readyToLoadModal)

                                @if ($compra_detalle && count($compra_detalle->detalles))
                                    @foreach ($compra_detalle->detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->consignatario->name }}</td>
                                            <td>${{ number_format($detalle->monto, 2, '.', ',') }}</td>

                                            <td>
                                                @if ($detalle->status_detalles_compra_efectivos == 1)
                                                    <button class="btn btn-success" data-toggle="modal"
                                                        wire:click="detalleCompraEfectivo({{ $detalle }})"
                                                        data-target="#finalizarCompra">Finalizar</button>
                                                    <button class="btn btn-danger">Rechazar</button>
                                                @else
                                                    <span
                                                        class="badge 
                                                    {{ $detalle->status_detalles_compra_efectivos == 2 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $detalle->status_detalles_compra_efectivos == 2 ? 'Terminado' : 'Rechazado' }}
                                                    </span>
                                                @endif
                                            </td>
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
                    <button type="button" wire:click='finalizarCompraEfectivo' class="btn btn-success" wire:loading.remove
                    >Finalizar compra de efectivo</button>
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="finalizarCompra" wire:ignore.self tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Finalizar compra de efectivo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='limpiarDatosDetalleCompra'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" wire:init='loadServicios'>

                        @if ($detalle_compra)
                            <div class="col-md-4 mb-3">
                                <label for="">Consignatario/Banco</label>
                                <input type="text" disabled class="form-control"
                                    value="{{ $detalle_compra->consignatario->name }}">
                            </div>
                            <div class="col-md-4 mb-3">

                                <label for="">Monto de la recolecta: $
                                    {{ number_format($monto_compra, 2, '.', ',') }}</label>
                                <input type="number" class="form-control @error('monto_compra') is-invalid @enderror"
                                    wire:model.live='monto_compra'>
                                @error('monto_compra')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <x-input-validadolive :readonly="false" label="Envases: (opcional)"
                                    placeholder="Envases" wire-model="envases_compra" wire-attribute="envases_compra"
                                    type="number" />
                            </div>

                            @if ($envases_compra)
                                <div class="col-md-1" style="margin-top: 32px">
                                    <button class="btn btn-info" wire:loading.remove
                                        wire:click='addEnvasesCompra'>Agregar</button>
                                </div>
                            @endif


                            <div class="col-md-4 mb-3">
                                <label for="">Fecha solicitada.</label>
                                <input type="text" disabled class="form-control"
                                    value="{{ $detalle_compra->compra_efectivo->fecha_compra }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-input-validado :readonly="false" label="Folio/Papeleta:"
                                    placeholder="Ingresa papeleta/folio" wire-model="folio_compra"
                                    wire-attribute="folio_compra" type="text" />
                            </div>

                            @if (!$envases_compra)
                                <div class="col-md-2 " style="margin-top: 32px">

                                    <div class="input-group @error('evidencia_compra') is-invalid @enderror">
                                        <label class="input-group-text btn btn-primary " for="evidencia_compra">
                                            <i class="fas fa-camera"></i> Subir Evidencia
                                        </label>
                                        <input type="file" id="evidencia_compra" wire:model="evidencia_compra"
                                            class="form-control d-none">

                                    </div>
                                    @error('evidencia_compra')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror


                                </div>
                                <div class="col-md-3 d-flex justify-content-center align-items-center">
                                    <div wire:loading wire:target='evidencia_compra'
                                        class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                        <strong>Evidencia cargando!</strong>
                                    </div>

                                    <!-- Muestra la imagen cargada si existe -->
                                    @if (isset($evidencia_compra) && $evidencia_compra)
                                        <img src="{{ $evidencia_compra->temporaryUrl() }}" alt="Foto Tomada"
                                            class="img-fluid" style="max-width: 100px; height: auto;">
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" role="status">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12 mb-3"></div>

                        @foreach ($inputs as $index => $input)
                            <div class="col-md-2 mb-3">
                                

                                    <label for="">Monto: $
                                        {{ number_format((float) $input['cantidad'], 2, '.', ',') }}
                                    </label>
                                    <input type="number"
                                        class="form-control @error('inputs.' . $index . '.cantidad') is-invalid @enderror"
                                        wire:model.live="inputs.{{ $index }}.cantidad">
                                    @error('inputs.' . $index . '.cantidad')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror

                            </div>

                            <div class="col-md-3 mb-3">
                                <x-input-validado label="Papeleta:" :readonly="false" placeholder="Papeleta"
                                    wire-model="inputs.{{ $index }}.folio" wire-attribute="folios"
                                    type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-input-validado label="Sello seguridad:" :readonly="false"
                                    placeholder="Sello de seguridad" wire-model="inputs.{{ $index }}.sello"
                                    type="text" />
                            </div>

                            <div class="col-md-2 " style="margin-top: 32px">

                                <div class="input-group @error('inputs.' . $index . '.photo') is-invalid @enderror">
                                    <label class="input-group-text btn btn-primary" for="photo.{{ $index }}">
                                        <i class="fas fa-camera"></i> Subir Evidencia
                                    </label>
                                    <input type="file" id="photo.{{ $index }}"
                                        wire:model="inputs.{{ $index }}.photo" class="form-control d-none">
                                </div>
                                @error('inputs.' . $index . '.photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-2 d-flex justify-content-center align-items-center">
                                <!-- Muestra la imagen cargada si existe -->
                                @if (isset($input['photo']) && $input['photo'])
                                    <img src="{{ $input['photo']->temporaryUrl() }}" alt="Foto Tomada"
                                        class="img-fluid" style="max-width: 100px; height: auto;">
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click='limpiarDatosDetalleCompra'
                        data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:loading.remove
                        wire:click='finalizarCompra'>Aceptar</button>

                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (contactId) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La ruta dara inicio.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('empezarRuta', contactId);
                        }
                    })
                })
                @this.on('terminar', (contactId) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La ruta terminara ,asegure que los servicios han sido concluidos o reprogramados.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('TerminarRuta', contactId);
                        }
                    })
                })

                Livewire.on('success-cotizacion', function([message]) {
                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {


                            window.location.href = '/ventas';

                        }
                    });
                });
                Livewire.on('success-compra-detalle', function(params) {
                    const nombreArchivo = params[0].nombreArchivo;
                    const tipomensaje = params[1].tipomensaje;
                    Swal.fire({
                        position: 'center',
                        icon: tipomensaje,
                        title: nombreArchivo,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    if (tipomensaje != 'error') {
                        $('#finalizarCompra').modal('hide');
                        $('#modalDetailCompra').modal('show');
                    }

                });


                Livewire.on('error', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

                Livewire.on('cerrarModal', function([message]) {
                    $('#exampleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: message,
                        title: "Cliente:",
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('agregarArchivocre', function(params) {
                    const nombreArchivo = params[0].nombreArchivo;
                    const tipomensaje = params[1].tipomensaje;
                    Swal.fire({
                        position: 'center',
                        icon: tipomensaje,
                        title: nombreArchivo,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    if (params[2]) {
                        $('#ModalEntregarRecolectar').modal('hide');
                        $('#ModalReprogramarServicio').modal('hide');
                        $('#modalDetailCompra').modal('hide');
                    }

                });
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('photo').addEventListener('change', function(event) {
                        var file = event.target.files[0];
                        if (file) {
                            // Mostrar la vista previa de la foto tomada
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                var img = document.createElement('img');
                                img.src = e.target.result;
                                img.width = '100%';
                                document.getElementById('photoPreview').appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });



                //reprogramacion        
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('photorepro').addEventListener('change', function(event) {
                        var file = event.target.files[0];
                        if (file) {
                            // Mostrar la vista previa de la foto tomada
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                var img = document.createElement('img');
                                img.src = e.target.result;
                                img.width = '100%';
                                document.getElementById('photoreproPreview').appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });
                $('#ModalEntregarRecolectar').on('hidden.bs.modal', function() {
                    Livewire.dispatch('modalCerrado');
                });
                $('#ModalReprogramarServicio').on('hidden.bs.modal', function() {
                    Livewire.dispatch('modalCerradoReprogramar');
                });


                // Ocultar el primer modal cuando se muestra el segundo modal
                $('#modalDetailCompra').on('show.bs.modal', function() {
                    $('#finalizarCompra').modal('hide');
                });

                // Ocultar el segundo modal cuando se muestra el primer modal
                $('#finalizarCompra').on('show.bs.modal', function() {
                    $('#modalDetailCompra').modal('hide');
                });

                $('#finalizarCompra').on('hidden.bs.modal', function() {
                    $('#modalDetailCompra').modal('show');
                });
            });

            var i = 0;

            function select_violado(check) {
                var message = document.getElementById('message');

                if (check.checked) {
                    i++;
                    message.style.display = 'block'; // Muestra el mensaje

                } else {
                    i--;
                    if (i == 0) {
                        message.style.display = 'none'; // Oculta el mensaje
                    }
                }
                console.log(i)
            }
        </script>
    @endpush

</div>
