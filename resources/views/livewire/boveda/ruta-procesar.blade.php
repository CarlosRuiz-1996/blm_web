<div>
    @push('css')
        <style>
            /* Escalar el checkbox */
            .large-checkbox {
                transform: scale(1.5);
                /* Cambia el valor para ajustar el tamaño */
                -webkit-transform: scale(1.5);
                /* Para compatibilidad con navegadores webkit */
                -ms-transform: scale(1.5);
                /* Para compatibilidad con Internet Explorer */
                margin: 10px;
                /* Ajustar el margen si es necesario */
            }

            .styled-text {
                color: white;
                /* Color del texto */
                font-weight: bold;
                /* Hacer la letra más gruesa */
                padding: 10px;
                /* Opcional: añadir padding para espaciado */
                border-radius: 5px;
                /* Opcional: añadir bordes redondeados */
            }

            .checkbox {
                font-weight: bold;
                text-decoration: underline;
            }

            .amount {
                font-weight: bold;
            }

            .d-flex.align-items-center span {
                margin-right: 5px;
            }

            .carousel-control-prev.custom-prev,
            .carousel-control-next.custom-next {
                background-color: rgba(0, 0, 0, 0.5);
                /* Fondo semi-transparente */
                border-radius: 50%;
                /* Redondear los bordes */
                width: 50px;
                height: 50px;
                position: absolute;
                top: 10px;
                /* Ajustar esta línea para posicionar los botones en la parte superior */
                z-index: 5;
            }

            .carousel-control-prev.custom-prev {
                left: 10px;
                /* Ajusta esta línea para mover el botón izquierdo */
            }

            .carousel-control-next.custom-next {
                right: 10px;
                /* Ajusta esta línea para mover el botón derecho */
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                background-color: rgb(154, 154, 158);
                /* Cambia este valor al color deseado */
                border-radius: 50%;
                padding: 10px;
                /* Ajustar el tamaño del icono */
            }

            .carousel-control-prev.custom-prev:hover,
            .carousel-control-next.custom-next:hover {
                background-color: rgba(0, 0, 0, 0.7);
                /* Color al pasar el mouse */
            }
        </style>
    @endpush
    <div class="card-outline card-info info-box">
        <div class="info-box-content">
            <div class="table-responsive">
                <table class="table" x-data="{ servicio: false, compra: false,comision:false, puerta:false }">
                    <thead>
                        <tr @click="servicio = ! servicio">
                            <th colspan="6" class="text-center table-secondary">
                                <h2>Servicios
                                    <i :class="servicio === false ? 'fas fa-chevron-up' :
                                        'fas fa-chevron-down'" class="ml-2"></i>
                                </h2>
                            </th>
                        </tr>
                        <tr class="table-success" x-show="servicio">
                            <th>No. Servicio</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Tipo</th>
                            
                            <th>Llaves</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($ruta->rutaServicios->where('status_ruta_servicios','>=',3)->where('puerta',0)->where('status_ruta_servicios','<=',5) as $servicio) 
                        
                            <tr x-show="servicio">
                                <td>
                                    {{ $servicio->folio }}
                                </td>
                                <td>{{ $servicio->servicio->cliente->razon_social }}</td>
                                <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                <td>{{ $servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolecta' }}</td>
                                
                                <td>
                                    <button type="button" class="btn mb-2 btn-warning btn-sm text-xs"
                                        wire:click="showKeys('{{ $servicio->id }}')" data-toggle="modal"
                                        data-target="#keyModal">

                                        <i class="fa fa-key"></i>
                                    </button>
                                </td>
                                <td>
                                    @if ($servicio->status_ruta_servicios != 5)
                                    @if ($servicio->tipo_servicio == 2)
                                    <button class="btn btn-info" data-toggle="modal"
                                        wire:click='opernModal({{ $servicio }})' data-target="#terminar_servicio">Verificar
                                        monto</button>
                                    @else
                                    <button class="btn btn-info" data-toggle="modal"
                                        wire:click='detallesEntrega({{ $servicio }})'
                                        data-target="#ModalDetalleEntregar">Finalizar
                                        Entrega</button>
                                    @endif
                                    @else
                                    <span class="badge bg-success" style="font-weight: bold;"> Finalizado.</span>
                                    @endif

                                </td>
                            </tr>
                        @endforeach


                        @if ($ruta->ruta_compra->isNotEmpty() &&
                            $ruta->ruta_compra->where('status_ruta_compra_efectivos', '!=', 5)->count() > 0)

                            <tr @click="compra = ! compra">
                                <th colspan="6" class="text-center table-secondary">
                                    <h2>Compra de efectivo
                                        <i :class="compra === false ? 'fas fa-chevron-up' :
                                            'fas fa-chevron-down'" class="ml-2"></i>
                                    </h2>
                                </th>
                            </tr>
                            <tr class="table-success" x-show="compra">
                                <th>Total de la compra</th>
                                <th>Fecha de la compra</th>
                                <th >Estatus</th>
                                <th colspan="3" class="text-center">Acciones</th>
                            </tr>
                            @foreach ($ruta->ruta_compra as $ruta_compra)
                                @if ($ruta_compra->status_ruta_compra_efectivos < 5) <tr x-show="compra">
                                    <td>
                                        ${{ number_format($ruta_compra->compra->total, 2, '.', ',') }}


                                    </td>
                                    <td>{{ $ruta_compra->compra->fecha_compra }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $ruta_compra->compra->status_compra_efectivos == 3 ? 'bg-warning' : 'bg-success' }}">
                                            {{ $ruta_compra->compra->status_compra_efectivos == 3 ? 'Pendiente' :
                                            'Finalizada' }}
                                        </span>
                                    </td>
                                    <td colspan="3" class="text-center">
                                        <button class="btn btn-info" data-toggle="modal"
                                            wire:click="showCompraDetail({{ $ruta_compra->compra }})"
                                            data-target="#modalDetailCompra">Detalles
                                        </button>
                                        @if ($ruta_compra->compra->status_compra_efectivos == 3)
                                        <button class="btn btn-danger"
                                            wire:click="$dispatch('finalizar-compra',{{ $ruta_compra }})">
                                            Finalizar
                                        </button>
                                        @endif
                                    </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        @if (!$comisiones->isEmpty())
                            <tr @click="comision = ! comision">
                                <th colspan="6" class="text-center table-secondary">
                                    <h2>Comisiones Extra
                                        <i :class="comision === false ? 'fas fa-chevron-up' :
                                        'fas fa-chevron-down'" class="ml-2"></i>
                                    </h2>
                                </th>
                            </tr>
                            <tr class="table-success" x-show="comision">
                                <th >Cliente</th>
                                <th >Sucursal</th>
                                <th>Total</th>
                                <th>Papeleta</th>
                                <th>Evidencia</th>
                                <th>Estatus</th>
                                {{-- <th colspan="2" class="text-center">Acciones</th> --}}
                            </tr>

                            @foreach ($comisiones as $comision)
                                    
                                <tr x-show="comision">
                                    <td>{{$comision->ruta_servicio->servicio->cliente->razon_social}}</td>
                                    <td>{{$comision->ruta_servicio->servicio->sucursal->sucursal->sucursal}}</td>
                                    <td>
                                        $ {{ number_format($comision->monto, 2, '.', ',') }} MXN
                                    </td>
                                    <td>{{$comision->papeleta}}</td>
                                    <td>
                                        <button class="btn btn-info" data-toggle="modal" data-target="#evidenciaModal"
                                        wire:click='evidenciaComision({{ $comision }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                            <path
                                                d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                            <path
                                                d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                        </svg>
                                    </button>
                                    </td>
                                    <td>{{$comision->status_servicio_comisions==1?'PENDIENTE':'PROCESADO'}}</td>
                                </tr>

                            @endforeach
                        @endif
                        @if(!$puertas->isEmpty())

                        <tr @click="puerta = ! puerta">
                            <th colspan="6" class="text-center table-secondary">
                                <h2>Servicios de puerta en puerta
                                    <i :class="puerta === false ? 'fas fa-chevron-up' :
                                    'fas fa-chevron-down'" class="ml-2"></i>
                                </h2>
                            </th>
                        </tr>
                        <tr class="table-success" x-show="puerta">
                            <th>No. Servicio</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Sucursal</th>
                            <th>Tipo</th>
                            <th >Acciones</th>
                        </tr>
                            @foreach($ruta->rutaServicios->where('status_ruta_servicios','>=',3)->where('puerta',1)->where('status_ruta_servicios','<=',5) as $servicio) 
                            
                                <tr x-show="puerta">
                                    <td>
                                        {{ $servicio->id }}
                                    </td>
                                    <td>{{ $servicio->servicio->cliente->razon_social }}</td>
                                    <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                    <td>{{$servicio->servicio->sucursal->sucursal->sucursal}}</td>
                                    <td>Puerta en puerta</td>
                                    
                                  
                                    <td>
                                        @if ($servicio->status_ruta_servicios != 5)
                                        @if ($servicio->tipo_servicio == 2)
                                        <button class="btn btn-info" data-toggle="modal"
                                            wire:click='opernModal({{ $servicio }})' data-target="#terminar_servicio">Verificar
                                            monto</button>
                                        @else
                                        <button class="btn btn-info" data-toggle="modal"
                                            wire:click='detallesEntrega({{ $servicio }})'
                                            data-target="#ModalDetalleEntregar">Finalizar
                                            Entrega</button>
                                        @endif
                                        @else
                                        <span class="badge bg-success" style="font-weight: bold;"> Finalizado.</span>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <button class="btn btn-success" wire:click="$dispatch('terminar')">FINALIZAR</button>
        </div>
    </div>



    <!--modal envases de cargar-->
    <div class="modal fade" id="terminar_servicio" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Verificar Montos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click='limpiar'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" wire:init='loadServicios'>
                        @if ($form->servicio)
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="No. Servicio:" placeholder="No. Servicio" wire-model="form.folio"
                                wire-attribute="folios" type="text" :readonly='true' />
                        </div>


                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Monto total del servicio:" placeholder="Envase"
                                wire-model="monto_calculado" id="monto_total" type="text" :readonly='true' />
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info" id="message" role="alert">
                                <span class="styled-text"> Ingresa el monto de cada en vase para revisar que todo
                                    este correcto.</span>
                            </div>
                        </div>


                        @foreach ($servicio_e as $envases)
                        <div class="col-md-3 mb-3">
                            <label for="">Sello</label>
                            <input class="form-control" value="{{ $envases->sello_seguridad }}" disabled
                                placeholder="Sello de seguridad" type="text" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="">No. Servicio</label>
                            <input class="form-control" value="{{ $envases->folio }}" placeholder="Folio" disabled
                                type="text" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="">
                                Monto:
                                {{-- ${{ number_format((float) $monto_envases[$envases->id]['cantidad'] ?? 0, 2, '.',
                                ',') }} --}}
                                ${{ number_format((float) ($monto_envases[$envases->id]['cantidad'] ?? 0), 2, '.', ',')
                                }}

                            </label>
                            <input
                                class="form-control {{ $errors->has('monto_envases.' . $envases->id . '.cantidad') ? 'is-invalid' : '' }}"
                                wire:model.live="monto_envases.{{ $envases->id }}.cantidad" placeholder="Monto"
                                type="number" onkeyup="monto(this,{{ $envases->id }})" />


                            @error('monto_envases.' . $envases->id . '.cantidad')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-1 mb-3">
                            <label style="display: block;">Violado</label>
                            <input {{ $envases->evidencia_recolecta->violate ? 'checked' : '' }}
                            type="checkbox" class="large-checkbox" disabled />
                        </div>

                        <div class="col-md-1 mb-3">
                            <Label>Evidencia</Label>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#evidenciaModal"
                                wire:click='evidenciaRecolecta({{ $envases }})'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-image" viewBox="0 0 16 16">
                                    <path
                                        d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                    <path
                                        d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-md-1 mb-3">
                            <label style="display: block;">Acreditación</label>
                            <input type="checkbox" wire:model="monto_envases.{{ $envases->id }}.acreditacion"
                                class="large-checkbox" />
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-12 text-center">
                            <div class="spinner-border" role="status">
                                {{-- <span class="visually-hidden">Loading...</span> --}}
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-dismiss="modal" wire:click='limpiar'>Cerrar</button>

                    <button class="btn btn-primary" wire:click="validar">Verificar</button>

                </div>
            </div>
        </div>
    </div>


    {{-- modal de diferencias --}}
    <div class="modal fade" id="diferencia_mdl" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Formato de diferencia de valores.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click='limpiar'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{ asset('/img/logospdf.png') }}" alt="Nombre alternativo" class="mb-3"
                                    style="max-width: 50px; float: left; margin-right: 10px;">
                            </div>
                            <div class="col-md-10">
                                <h5 class="text-dark text-center" style="margin-top: 15px;">Servicios Integrados
                                    PRO-BLM de México
                                    S.A. de C.V.</h5>
                            </div>
                        </div>
                        <div class="small-text mt-4">

                            <p class="text-start">ACTA ADMINISTRATIVA DE DIFERENCIAS</p>


                            <div class="col-md-12 col-12">
                                @php
                                $fecha_creacion = \Carbon\Carbon::parse(now());
                                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F'));
                                $dia = $fecha_creacion->format('d');
                                $anio = $fecha_creacion->format('Y');
                                @endphp
                                <h6 class="text-dark text-right" style="margin-top: 15px;margin-right: 8%">EN LA
                                    CIUDAD DE MÉXICO, A
                                    {{ $dia . ' DE ' . $nombre_mes . ' DEL ' . $anio }}.
                                </h6>
                            </div>
                            <p class="justify-text ">
                                EN LA EMPRESA "SERVICIOS INTEGRADOS PRO-BLM DE MÉXICO, SA DE C.V" UBICADA EN CALLE
                                CUAUHTEMOC No. 12, COL. PUEBLO DE SANTA CRUZ MEYEHUALCO, DEL. IZTAPALAPA, CP. 09700
                                EN EL ÁREA DE RECUENTO Y PROCESO DE VALORES DE ESTA EMPRESA, SE LEVANTA LA PRESENTE ACTA
                                PARA DEJAR
                                CONSTANCIA
                                DE LA DIFERENCIA DETALLADA A CONTUNUACIÓN:
                            </p>
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner card" style="padding-left: 30px">
                                    @foreach ($diferencias as $index => $diferencia)
                                    <div class="carousel-item  {{ $index == 0 ? 'active' : '' }}">
                                        <div class="d-block w-100 p-4">
                                            <div class="col-md-8 mb-3">
                                                <p>
                                                    FECHA DEL COMPROBANTE:
                                                    <span class="font-weight-bold checkbox">{{
                                                        $diferencia['servicio']['updated_at'] }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p>
                                                    ORIGEN DEL DEPÓSITO: (CLIENTE)
                                                    <span class="font-weight-bold checkbox">{{
                                                        $diferencia['servicio']['rutaServicios']['servicio']['cliente']['rfc_cliente']
                                                        }}</span>
                                                </p>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <p>
                                                    NÚMERO DE SERVICIO:
                                                    <span class="font-weight-bold checkbox">{{
                                                        $diferencia['servicio']['folio'] }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p>
                                                    Sello de seguridad:
                                                    <span class="font-weight-bold checkbox">{{
                                                        $diferencia['servicio']['sello_seguridad'] }}</span>
                                                </p>
                                            </div>

                                            <div class="col-md-8 mb-3">
                                                <p>
                                                    IMPORTE QUE DICE CONTENER: $
                                                    <span class="font-weight-bold checkbox">
                                                        {{ number_format($diferencia['monto'], 2, '.', ',') }} MXN
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-md-8 mb-3">
                                                <p>
                                                    IMPORTE COMPROBADO: $
                                                    <span class="font-weight-bold checkbox">


                                                        {{ number_format($diferencia['cantidad_ingresada'], 2, '.', ',')
                                                        }}
                                                        MXN
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-md-8 mb-3">
                                                <p>DIFERENCIA</p>
                                                <p>
                                                    FALTANTE <span class="checkbox">{{ $diferencia['tipo_dif'] == 0 ?
                                                        '_X_' : '___' }}</span>
                                                    SOBRANTE <span class="checkbox">{{ $diferencia['tipo_dif'] != 1 ?
                                                        '___' : '_X_' }}</span>
                                                    DE <span class="amount">
                                                        $
                                                        {{ number_format($diferencia['diferencia'], 2, '.', ',') }}
                                                        MXN
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <p>OBSERVACIONES:</p>

                                                <textarea class="form-control w-full" cols="3" rows="2"
                                                    wire:model='observaciones.{{ $index }}'>
                                                </textarea>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev custom-prev" type="button"
                                    data-target="#carouselExampleControls" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </button>
                                <button class="carousel-control-next custom-next" type="button"
                                    data-target="#carouselExampleControls" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </button>

                            </div>

                            <p class="justify-text">LOS INVOLUCRADOS BIEN IMPUESTOS DEL CONTENIDO DE LA PRESENTE ACTA Y
                                DE LOS ALCANCES DE LA MISMA SE
                                MANIFIESTAN CONFORMES, CONSTATANDO MEDIANTE NOMBRE Y FIRMA.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-dismiss="modal" wire:click='limpiar'>Cancelar</button>

                    <button class="btn btn-primary" wire:click='diferencia'>Aceptar</button>

                </div>
            </div>
        </div>
    </div>


    {{-- detalle de la compra --}}
    <div class="modal fade" wire:ignore.self id="modalDetailCompra" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <th>Evidencia</th>
                                <th>Estatus</th>
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
                                    <button class="btn btn-info" data-toggle="modal" data-target="#evidenciaModal"
                                        wire:click='evidenciaCompra({{ $detalle }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                            <path
                                                d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                            <path
                                                d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                        </svg>
                                    </button>
                                </td>
                                <td>

                                    <span
                                        class="badge {{ $detalle->status_detalles_compra_efectivos == 2 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $detalle->status_detalles_compra_efectivos == 2 ? 'Finalizada' :
                                        'Reprogramada' }}
                                    </span>

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

                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    {{-- detalles de entrega --}}
    <div class="modal fade" id="ModalDetalleEntregar" wire:ignore.self tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Detalles de la Entrega
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @if ($readyToLoadModal)




                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <th>Monto</th>
                                <th>Papeleta/Folio</th>
                                <th>Sello de seguridad</th>
                                <th>Evidencia</th>
                            </thead>
                            <tbody>
                                @foreach ($inputs as $index => $input)
                                <tr>
                                    <td>
                                        {{ number_format((float) $input['cantidad'], 2, '.', ',') }}
                                    </td>
                                    <td>
                                        {{ $input['folio'] }}
                                    </td>
                                    <td>

                                        {{ $input['sello'] }}
                                    </td>

                                    <td>
                                        <button class="btn btn-info" data-toggle="modal" data-target="#evidenciaModal"
                                            wire:click='evidenciaEntrega({{ $index }})'>
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
                                @endforeach
                            </tbody>
                        </table>
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
                    <button class="btn btn-info"
                        wire:click="$dispatch('finalizar-entrega',{{ $servicioRuta_id }})">Finalizar
                        Entrega</button>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="evidenciaModal" wire:ignore.self tabindex="-1" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <!-- Tamaño del modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Evidencia</h5>
                </div>
                <div class="modal-body text-center">
                    @if ($readyToLoadModal)
                    <div class="img-container">
                        <img src="{{ asset('storage/' . $evidencia_foto) }}" alt="Evidencia" class="img-fluid">
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

    <div class="modal fade" id="keyModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1"
        wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Llaves del servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click='cleanKeys'>
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
                                    <th>Entregar</th>
                                </thead>
                                <tbody>

                                    @foreach ($keys as $key)
                                    <tr>
                                        <td>{{ $key->key }}</td>
                                        <td>
                                            @if($key->status_servicio_keys ==1)
                                            <button class="btn btn-primary" wire:click='updateKeys({{$key}})'
                                                wire:loading.attr="disabled" wire:target="updateKeys({{ $key }})">
                                                Entregar
                                            </button>
                                            <span style="color: rgb(6, 125, 2)" wire:loading
                                                wire:target="updateKeys({{ $key}})">Entregando...</span>

                                            @else
                                            <span class="badge bg-success" style="font-weight: bold;"> Entregado.</span>

                                            @endif
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

                    
                    @if($ruta_servicio && $ruta_servicio->keys==1)
                    <button class="btn btn-info" wire:click='endKeysRutaServices'
                        wire:loading.remove>Finalizar</button>
                    @endif
                    <button class="btn btn-secondary" data-dismiss="modal" wire:click='cleanKeys'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        var array_monto = [];

            const monto = (input, index) => {



                // Obtener el valor actual del campo de entrada y convertirlo a número
                let montoActual = parseFloat($('#monto_total').val());

                // Verificar si el valor actual es un número válido, si no, inicializar a 0
                if (isNaN(montoActual)) {
                    montoActual = 0;
                }

                // Obtener el valor ingresado en el input y convertirlo a número
                let nuevoMonto = parseFloat($(input).val());

                // Verificar si el nuevo monto es un número válido, si no, inicializar a 0
                if (isNaN(nuevoMonto)) {
                    nuevoMonto = 0;
                }
                // Verificar si ya existe el índice en el arreglo
                if (typeof array_monto[index] !== 'undefined') {
                    // Restar el monto antiguo del monto total
                    montoActual -= array_monto[index];
                }

                // Actualizar el valor en el arreglo
                array_monto[index] = nuevoMonto;

                // Sumar el nuevo monto al valor actual
                montoActual += nuevoMonto;

                // Establecer el nuevo valor en el campo de entrada de monto total
                $('#monto_total').val(montoActual);
            };

            document.addEventListener('livewire:initialized', () => {
                Livewire.on('error', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

                Livewire.on('limpiar_monto_js', function([message]) {
                    $('#monto_total').val(0);
                    array_monto = [];
                });
                Livewire.on('diferencia', function([message]) {
                    Swal.fire({
                        title: "Diferencia de valores",
                        text: message[0],
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: true,

                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si, generar acta de diferencia.",
                        cancelButtonText: "No, Corregir montos",

                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#terminar_servicio').modal('hide');
                            $('#diferencia_mdl').modal('show');
                        } else {
                            $('#monto_total').val(0);
                            array_monto = [];
                            @this.dispatch('corregirMonto');
                        }
                    });
                });

                Livewire.on('agregarArchivocre', function(params) {


                    const msg = params[0].msg;
                    const tipomensaje = params[1].tipomensaje;
                    const terminar = params[2]?.terminar || '';
                    $('#terminar_servicio').modal('hide');
                    $('#diferencia_mdl').modal('hide');

                    if(tipomensaje != 'error'){
                    $('#keyModal').modal('hide');
                    }
                    Swal.fire({
                        position: 'center',
                        icon: tipomensaje,
                        title: msg,
                        showConfirmButton: false,
                        timer: 4000
                    });

                    if (terminar) {
                        window.location.href = '/boveda/inicio';
                    }

                });


                @this.on('terminar', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La ruta terminara.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('terminar-ruta-boveda');
                        }
                    })
                })



                @this.on('finalizar-entrega', (servicio) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "El servicio de entrega termino sin ningun problema.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('finaliza-entrega', {
                                servicio: servicio
                            });
                        }
                    })
                });

                @this.on('finalizar-compra', (compra) => {


                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "La compra sera terminada y podra ser procesada en bancos.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('finaliza-compra', {
                                ruta_compra: compra
                            });
                        }
                    })
                });

                $('#keyModal').on('hidden.bs.modal', function() {
                    @this.call('cleanKeys');
                });
            });
    </script>
    @endpush
</div>