<div>
    <div class="container-fluid pt-5" x-data="{ openRuta: null, openServicio: null, openCompra: null, openPuerta:null, openPuertaServ:null }">
        @if ($identificado)
            <h3 class="ml-2">Operador-<span class="text-info">{{ $nombreUsuario }}</span></h3>
            <div class="">
                <div class="">
                    @if (count($rutaEmpleados) > 0)
                        <div class="accordion">
                            @foreach ($rutaEmpleados as $index => $rutaServicio)
                                <div class="card">
                                    <div class="card-header bg-gray">
                                        <h2 class="mb-0">
                                            <button class="btn btn-block text-center bg-gray"
                                                @click="openRuta === {{ $index }} ? openRuta = null : openRuta = {{ $index }}"
                                                :aria-expanded="openRuta === {{ $index }} ? 'true' : 'false'"
                                                aria-controls="collapseRuta{{ $index }}">
                                                Ruta {{ $rutaServicio->nombre->name . '-' . $rutaServicio->dia->name }}
                                            </button>
                                        </h2>
                                    </div>

                                    <div x-show="openRuta === {{ $index }}" x-collapse
                                        id="collapseRuta{{ $index }}">
                                        <div class="card-body p-0">
                                            <div class="d-table table-info mt-0 pt-0 bg-gray" style="width:100%">
                                                <div class="d-table-row text-sm" style="width:100%">
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">Hora
                                                        Inicio : <p class="text-bold mb-0">
                                                            {{ $rutaServicio->hora_inicio }}</p>
                                                    </div>
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">Hora
                                                        Termino : <p class="text-bold mb-0">
                                                            {{ $rutaServicio->hora_fin ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">Dia : <p
                                                            class="text-bold mb-0">{{ $rutaServicio->dia->name }}</p>
                                                    </div>
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">Riesgo :
                                                        <p class="text-bold mb-0">{{ $rutaServicio->riesgo->name }}</p>
                                                    </div>
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">Estado :
                                                        <p class="text-bold mb-0">{{ $rutaServicio->estado->name }}</p>
                                                    </div>
                                                    <div class="d-block d-md-table-cell text-center pt-1 pb-0">
                                                        Iniciar/Terminar :
                                                        <p>
                                                            @if ($rutaServicio->status_ruta == 1)
                                                                <button type="button"
                                                                    wire:click="$dispatch('confirm', {{ $rutaServicio->id }})"
                                                                    class="btn btn-primary btn-sm text-xs">
                                                                    <i class="fas fa-play mr-1"></i> Iniciar
                                                                </button>
                                                            @elseif($rutaServicio->status_ruta == 2)
                                                                <button type="button"
                                                                    wire:click="$dispatch('terminar', {{ $rutaServicio->id }})"
                                                                    class="btn btn-danger btn-sm text-xs">
                                                                    <i class="fas fa-stop mr-1"></i> Terminar
                                                                </button>
                                                            @elseif($rutaServicio->status_ruta == 3)
                                                                <span style="color: green;"><i
                                                                        class="fas fa-check-circle"></i> Ruta
                                                                    completada</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--Servicios rutas inicio-->
                                            @if ($rutaServicio->status_ruta != 1)
                                                <div x-data="{ openServicio: null }">
                                                    @foreach ($rutaServicio->rutaServicios->where('status_ruta_servicios', '!=', 6)->where('puerta',0)->sortByDesc('updated_at') as $servicio)
                                                        <div class="card m-0 text-xs">
                                                            <div class="card-header p-0">
                                                                <button class="btn btn-block text-center text-xs"
                                                                    :style="openServicio === {{ $servicio->id }} ?
                                                                        'background-color:#bee5eb' : 'bg-light'"
                                                                    @click="openServicio === {{ $servicio->id }} ? openServicio = null : openServicio = {{ $servicio->id }}"
                                                                    :aria-expanded="openServicio === {{ $servicio->id }} ? 'true' :
                                                                        'false'"
                                                                    aria-controls="collapseServicio{{ $servicio->id }}">
                                                                    <p class="mb-0 text-success text-bold">
                                                                        {{ $servicio->servicio->ctg_servicio->descripcion }}
                                                                    </p>
                                                                    <p class="mb-0 text-danger">
                                                                        {{ $servicio->servicio->cliente->razon_social }}
                                                                    </p>
                                                                    <p class="mb-0 text-info">
                                                                        {{ $servicio->servicio->sucursal->sucursal->sucursal }},
                                                                        {{ $servicio->servicio->sucursal->sucursal->direccion }},
                                                                        {{ $servicio->servicio->sucursal->sucursal->cp->cp }},
                                                                        {{ $servicio->servicio->sucursal->sucursal->cp->estado->name }}
                                                                    </p>
                                                                    <p class="mb-0 text-danger">
                                                                        {{ $servicio->tipo_servicio == 1 ? 'Entrega' : ($servicio->tipo_servicio == 2 ? 'Recolección' : 'Otro') }}
                                                                    </p>
                                                                </button>
                                                            </div>
                                                            <div x-show="openServicio === {{ $servicio->id }}"
                                                                x-collapse id="collapseServicio{{ $servicio->id }}">
                                                                <div class="card-body p-0">
                                                                    <div class="d-table table-info mt-0 pt-0"
                                                                        style="width:100%">
                                                                        <div class="d-table-row text-sm"
                                                                            style="width:100%">
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">No. Servicio: <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->id }}</p>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">Papeleta: <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->folio!=''?$servicio->folio:'N/A' }}</p>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="1">Envases : <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->envases??0 }}</p>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="2">Llaves :</p>
                                                                                <button type="button"
                                                                                    class="btn mb-2 btn-warning btn-sm text-xs"
                                                                                    wire:click="showKeys('{{ $servicio->id }}')"
                                                                                    data-toggle="modal"
                                                                                    data-target="#keyModal">

                                                                                    <i class="fa fa-key"></i>
                                                                                </button>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">Acción :
                                                                                @if ($servicio->status_ruta_servicios == 4)
                                                                                    <p>
                                                                                        <button type="button"
                                                                                            wire:click="$dispatch('confirmarservicio', { contactId: {{ $servicio->id }}, serviciotipo: {{ $servicio->tipo_servicio }} })"
                                                                                            class="btn mb-2 btn-primary btn-sm text-xs">
                                                                                            <i
                                                                                                class="fas fa-play mr-1"></i>
                                                                                            Iniciar Servicio
                                                                                        </button>
                                                                                    </p>
                                                                                @elseif($servicio->status_ruta_servicios == 2)
                                                                                    <p class="mb-0">
                                                                                        <button type="button"
                                                                                            class="btn mb-2 btn-primary btn-sm text-xs"
                                                                                            wire:click="ModalEntregaRecolecta('{{ $servicio->id }}', '{{ $servicio->tipo_servicio }}')"
                                                                                            data-toggle="modal"
                                                                                            data-target="#ModalEntregarRecolectar">
                                                                                            <i
                                                                                                class="fas fa-play mr-1"></i>
                                                                                            {{ $servicio->tipo_servicio == 1 ? 'Entregar' : ($servicio->tipo_servicio == 2 ? 'Recolectar' : 'Otro') }}
                                                                                        </button>
                                                                                    </p>
                                                                                    <p class="mb-0">
                                                                                        <button type="button"
                                                                                            class="btn mb-2 btn-danger btn-sm text-xs"
                                                                                            wire:click="ModalReprogramarServicio('{{ $servicio->id }}')"
                                                                                            data-toggle="modal"
                                                                                            data-target="#ModalReprogramarServicio">
                                                                                            <i
                                                                                                class="fas fa-clock mr-1"></i>
                                                                                            Reprogramar
                                                                                        </button>
                                                                                    </p>
                                                                                    <p class="mb-0">
                                                                                        <button type="button"
                                                                                            class="btn mb-2 btn-secondary btn-sm text-xs"
                                                                                            wire:click="addComision('{{ $servicio->id }}')"
                                                                                            data-toggle="modal"
                                                                                            data-target="#comisionModal">
                                                                                            <i
                                                                                                class="fa fa-plus"></i>
                                                                                            Agregar Comisión
                                                                                        </button>
                                                                                    </p>
                                                                                @else
                                                                                    <p>
                                                                                        <span
                                                                                            class="badge {{ $servicio->status_ruta_servicios == 3 ? 'bg-success' : 'bg-warning' }} mb-2">
                                                                                            {{ $servicio->status_ruta_servicios == 3 ? 'Terminado' : 'Reprogramado' }}
                                                                                        </span>
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <!--Servicios rutas fin-->

                                            <!--COMPRAS BANCOS-->
                                            @if (
                                                $rutaServicio->ruta_compra->isNotEmpty() &&
                                                    $rutaServicio->ruta_compra->where('status_ruta_compra_efectivos', '!=', 5)->count() > 0)
                                                <div x-data="{ openCompra: null }">
                                                    @foreach ($rutaServicio->ruta_compra as $ruta_compra)
                                                        @if ($ruta_compra->status_ruta_compra_efectivos != 5)
                                                            <div class="card m-0">
                                                                <div class="card-header p-0">
                                                                    <h5 class="mb-0">
                                                                        <button
                                                                            class="btn btn-block text-center text-md"
                                                                            :style="openCompra ===
                                                                                {{ $ruta_compra->compra_efectivo_id }} ?
                                                                                'background-color:#bee5eb' : 'bg-light'"
                                                                            @click="openCompra === {{ $ruta_compra->compra_efectivo_id }} ? openCompra = null : openCompra = {{ $ruta_compra->compra_efectivo_id }}"
                                                                            :aria-expanded="openCompra ===
                                                                                {{ $ruta_compra->compra_efectivo_id }} ?
                                                                                'true' : 'false'"
                                                                            aria-controls="collapseCompra{{ $ruta_compra->compra_efectivo_id }}">

                                                                            <p class="mb-0 text-success text-bold">
                                                                                Compra efectivo</p>
                                                                        </button>
                                                                    </h5>
                                                                </div>
                                                                <div x-show="openCompra === {{ $ruta_compra->compra_efectivo_id }}"
                                                                    x-collapse
                                                                    id="collapseCompra{{ $ruta_compra->compra_efectivo_id }}">
                                                                    <div class="card-body p-0">
                                                                        <div class="d-table table-info mt-0 pt-0"
                                                                            style="width:100%">
                                                                            <div class="d-table-row"
                                                                                style="width:100%">
                                                                                <div class="d-block d-md-table-cell text-center pt-1 pb-0"
                                                                                    colspan="3">Monto de la compra :
                                                                                    <p class="text-bold">
                                                                                        ${{ number_format($ruta_compra->compra->total, 2, '.', ',') }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="d-block d-md-table-cell text-center pt-1 pb-0"
                                                                                    colspan="3">Fecha de la compra :
                                                                                    <p class="text-bold">
                                                                                        {{ $ruta_compra->compra->fecha_compra }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="d-block d-md-table-cell text-center pt-1 pb-0"
                                                                                    colspan="3">Estatus de la compra
                                                                                    : <p class="text-bold">
                                                                                        <span
                                                                                            class="badge {{ $ruta_compra->compra->status_compra_efectivos == 2 ? 'bg-warning' : 'bg-success' }}">
                                                                                            {{ $ruta_compra->compra->status_compra_efectivos == 2 ? 'Pendiente' : 'Finalizada' }}
                                                                                        </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="d-block d-md-table-cell text-center pt-1 pb-0"
                                                                                    colspan="3">Detalles de la
                                                                                    compra : <p class="text-bold">
                                                                                        <button class="btn btn-info"
                                                                                            data-toggle="modal"
                                                                                            wire:click="showCompraDetail({{ $ruta_compra->compra }})"
                                                                                            data-target="#modalDetailCompra">Detalles</button>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            <!--COMPRAS BANCOS FIN-->

                                            {{-- puerta en puerta --}}
                                            @if ($rutaServicio->status_ruta != 1)
                                                <div x-data="{ openPuerta: null, openPuertaServ:null }">
                                                    @foreach ($rutaServicio->rutaServicios->where('status_ruta_servicios', '!=', 6)->where('puerta',1)->sortByDesc('updated_at') as $servicio)

                                                   
                                                        <div class="card m-0 text-xs">
                                                           
                                                            <h5 class="mb-0">
                                                                <button
                                                                    class="btn btn-block text-center text-md"
                                                                    :style="openPuerta === {{ $servicio->id }} ? 'background-color:#9fd9e2' : 'bg-light'"
                                                                    @click="openPuerta === {{ $servicio->id }} ? openPuerta = null : openPuerta = {{ $servicio->id }}"
                                                                    :aria-expanded="openPuerta === {{ $servicio->id }} ? 'true' : 'false'"
                                                                    aria-controls="collapsePuerta{{ $servicio->id }}">
                                            
                                                                    <p class="mb-0 text-success text-bold">
                                                                        Servicio de puerta en puerta
                                                                    </p>
                                                                </button>
                                                            </h5>
                                                            <div x-show="openPuerta === {{ $servicio->id }}" x-collapse id="collapsePuerta{{ $servicio->id }}">
                                                                <div class="card-header p-0">
                                                                    <button class="btn btn-block text-center text-xs"
                                                                        :style="openPuertaServ === {{ $servicio->id }} ?
                                                                            'background-color:#bee5eb' : 'bg-light'"
                                                                        @click="openPuertaServ === {{ $servicio->id }} ? openPuertaServ = null : openPuertaServ = {{ $servicio->id }}"
                                                                        :aria-expanded="openPuertaServ === {{ $servicio->id }} ? 'true' :
                                                                            'false'"
                                                                        aria-controls="collapseServicio{{ $servicio->id }}">
                                                                        <p class="mb-0 text-success text-bold">
                                                                            {{ $servicio->servicio->ctg_servicio->descripcion }}
                                                                        </p>
                                                                        <p class="mb-0 text-danger">
                                                                            {{ $servicio->servicio->cliente->razon_social }}
                                                                        </p>
                                                                        <p class="mb-0 text-info">
                                                                            {{ $servicio->servicio->sucursal->sucursal->sucursal }},
                                                                            {{ $servicio->servicio->sucursal->sucursal->direccion }},
                                                                            {{ $servicio->servicio->sucursal->sucursal->cp->cp }},
                                                                            {{ $servicio->servicio->sucursal->sucursal->cp->estado->name }}
                                                                        </p>
                                                                        <p class="mb-0 text-danger">
                                                                            {{ $servicio->tipo_servicio == 1 ? 'Entrega' : ($servicio->tipo_servicio == 2 ? 'Recolección' : 'Otro') }}
                                                                        </p>
                                                                    </button>
                                                                       
                                                                </div>
                                                                <div class="card-body p-0" x-show="openPuertaServ === {{ $servicio->id }}" x-collapse id="collapsePuertaServ{{ $servicio->id }}">
                                                                    <div class="d-table table-info mt-0 pt-0"
                                                                        style="width:100%">
                                                                        <div class="d-table-row text-sm"
                                                                            style="width:100%">
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">No. Servicio: <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->id }}</p>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">Papeleta: <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->folio!=''?$servicio->folio:'N/A' }}</p>
                                                                            </div>
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="1">Envases : <p
                                                                                    class="text-bold">
                                                                                    {{ $servicio->envases??0 }}</p>
                                                                            </div>
                                                                           
                                                                            <div class="d-block d-md-table-cell text-center pt-1 pb-0 mt-0 mb-0"
                                                                                colspan="3">Acción :
                                                                                @if ($servicio->puertaHas->status_puerta_servicio == 0)
                                                                                    <p>
                                                                                        <button type="button"
                                                                                            wire:click="$dispatch('confirmarservicio', { contactId: {{ $servicio->id }}, serviciotipo: {{ $servicio->tipo_servicio }} })"
                                                                                            class="btn mb-2 btn-primary btn-sm text-xs">
                                                                                            <i
                                                                                                class="fas fa-play mr-1"></i>
                                                                                            Iniciar Servicio
                                                                                        </button>
                                                                                    </p>
                                                                                @elseif($servicio->puertaHas->status_puerta_servicio == 1)
                                                                                    <p class="mb-0">
                                                                                        <button type="button"
                                                                                            class="btn mb-2 btn-primary btn-sm text-xs"
                                                                                            wire:click="ModalEntregaRecolecta('{{ $servicio->id }}', '{{ $servicio->puertaHas->recolecta ==0 ? 2: 1 }}')"
                                                                                            data-toggle="modal"
                                                                                            data-target="#ModalEntregarRecolectar">
                                                                                            <i
                                                                                                class="fas fa-play mr-1"></i>
                                                                                            {{ $servicio->puertaHas->recolecta ==0 ? 'Recolecta': 'Entrega' }}
                                                                                        </button>
                                                                                    </p>   
                                                                                @else
                                                                                    <p>
                                                                                        <span
                                                                                            class="badge {{ $servicio->status_ruta_servicios == 3 ? 'bg-success' : 'bg-warning' }} mb-2">
                                                                                            {{ $servicio->status_ruta_servicios == 3 ? 'Terminado' : 'Reprogramado' }}
                                                                                        </span>
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay rutas asignadas para el operador.
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('collapsible', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>

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
                                    <x-input-validado label="No. Servicio:" :readonly="true" placeholder="No. Servicio"
                                        wire-model="nServicio" type="text" />
                                </div>
                            @endif
                            @if ($tiposervicio == 'Recolección')
                                <div class="col-md-4 mb-3">
                                 
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
                                <div class="col-md-1">
                                    <x-label>Morralla</x-label>
                                    <input type="checkbox" class="form-control" wire:model.live='morralla'>
                                </div>
                                <div class="col-md-1" style="margin-top: 32px">

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
                                @if ($input['morralla']==false)
                                    <div class="col-md-1 mb-3" {{ $tiposervicio == 'Entrega' ? 'hidden' : '' }}>
                                        <Label>Violado</Label>
                                        <input label="Cantidad:" id="violado.{{ $index }}"
                                            onclick="select_violado(this)"
                                            wire:model="inputs.{{ $index }}.violado" type="checkbox" />

                                    </div>
                                    <div class="{{ $tiposervicio == 'Recolección' ? 'col-md-2' : 'col-md-3' }} mb-3">

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
                                @endif

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
                                <div class="col-md-2 mb-3" style="margin-top: 32px">
                                    {{-- <x-input-validado label="Evidencia:" :readonly="false" placeholder="Evidencia"
                                        wire-model="inputs.{{ $index }}.photo" type="file" /> --}}

                                        <div class="input-group @error('inputs.{{ $index }}.photo') is-invalid @enderror">
                                            <label class="input-group-text btn btn-primary " for="inputs.{{ $index }}.photo">
                                                <i class="fas fa-camera"></i> Subir Evidencia
                                            </label>
                                            <input type="file" id="inputs.{{ $index }}.photo" wire:model="inputs.{{ $index }}.photo"
                                                class="form-control d-none">
    
                                        </div>
                                        @error('inputs.{{ $index }}.photo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-3 d-flex justify-content-center align-items-center">
                                    <div wire:loading wire:target='inputs.{{ $index }}.photo'
                                        class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                        <strong>Evidencia cargando!</strong>
                                    </div>

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
                    @if ($status_compra == 2)
                        <button type="button" wire:click='finalizarCompraEfectivo' class="btn btn-success"
                            wire:loading.remove>Finalizar compra de efectivo</button>
                    @endif
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
                                <x-input-validado :readonly="false" label="No. Servicio:"
                                    placeholder="Ingresa No. Servicio" wire-model="folio_compra"
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
                                    {{ number_format((float) $input['cantidad']??0, 2, '.', ',') }}
                                </label>
                                <input type="number"
                                    class="form-control @error('inputs.' . $index . '.cantidad') is-invalid @enderror"
                                    wire:model.live="inputs.{{ $index }}.cantidad">
                                @error('inputs.' . $index . '.cantidad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="col-md-3 mb-3">
                                <x-input-validado label="No. Servicio:" :readonly="false" placeholder="No. Servicio"
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
    <!--llaves-->
    <div class="modal fade" id="keyModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Llaves del servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='cleanKeys'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @if ($keys)
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                @if (count($keys))
                                    <table class="table table-striped">
                                        <thead class="table-info">
                                            <th>Llave</th>
                                        </thead>
                                        <tbody>

                                            @foreach ($keys as $key)
                                                <tr>
                                                    <td>{{ $key->key }}</td>


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

    <!--comision-->
    <div class="modal fade" id="comisionModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar comisión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='cleanComision'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" wire:init='loadServicios'>
                        @if ($readyToLoadModal)

                        <div class="col-md-3 mb-4">
                            <x-input-validado label="Cliente:" :readonly="true"
                                wire-model="cliente_comision" type="text" />
                        </div>
                        <div class="col-md-8 mb-4">
                            <x-input-validado label="Dirección:" :readonly="true"
                                wire-model="direccion_comision" type="text" />
                        </div>


                        <div class="col-md-3 mb-3">
                            <x-input-validado label="Papeleta:" :readonly="false"
                                placeholder="Ingresa papeleta:"
                                wire-model="papeleta_comision" type="text" />
                        </div>

                        
                        <div class="col-md-4 mb-3">
                          
                            <label for="">Monto de la comisión: $
                                {{ number_format($monto_comision, 2, '.', ',') }}</label>
                            <input type="number"
                                class="form-control @error('monto_comision') is-invalid @enderror"
                                wire:model.live='monto_comision'>
                            @error('monto_comision')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                       
                        <div class="col-md-2 mb-3" style="margin-top: 33px">
                            <label for="file-upload" class="btn btn-primary btn-sm" 
                             wire:loading.class="btn-secondary"
                            wire:loading.class.remove="btn-primary"
                            >
                                Subir evidencia
                            </label>
                            <input type="file" id="file-upload" wire:model="evidencia_comision"
                            wire:loading.attr="disabled"
                           
                            style="display: none;">
                        </div>
                        
                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                            <div wire:loading wire:target="evidencia_comision">  
                                Cargando evidencia...
                            </div>
                            @if (isset($evidencia_comision) && $evidencia_comision)
                                <img src="{{ $evidencia_comision->temporaryUrl() }}" alt="Foto Tomada"
                                    class="img-fluid" style="max-width: 100px; height: auto;">
                            @endif
                        </div>
                        @else
                        <div class="col-md-12 text-center">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>
                        @endif
                    </div>

                   
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" wire:click='cleanComision'>Cerrar</button>
                    <button class="btn btn-info" wire:click='saveComision'
                    wire:loading.remove
                    >Guardar</button>

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
                @this.on('confirmarservicio', ({
                    contactId,
                    serviciotipo
                }) => {
                    console.log(contactId);
                    console.log(serviciotipo);
                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "El servicio dara inicio.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('empezarRutaServicios', contactId, serviciotipo);
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
                        title: 'Oops...',
                        text: message[0],
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
                        title: tipomensaje=='success'?'Exito':'Error',
                        text: nombreArchivo,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    if (params[2]) {
                        $('#ModalEntregarRecolectar').modal('hide');
                        $('#ModalReprogramarServicio').modal('hide');
                        $('#modalDetailCompra').modal('hide');
                        $('#comisionModal').modal('hide');
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
                $('#comisionModal').on('hidden.bs.modal', function() {
                    @this.call('cleanComision');
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
