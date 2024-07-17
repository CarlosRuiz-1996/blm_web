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
                <table class="table">
                    <thead>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Folio/Papeleta</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>

                        @foreach ($ruta->rutaServicios as $servicio)
                            <tr>

                                <td>{{ $servicio->servicio->cliente->razon_social }}</td>
                                <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                <td>{{ $servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolecta' }}</td>
                                <td>
                                    {{ $servicio->folio }}
                                </td>
                                <td>
                                    @if ($servicio->status_ruta_servicios != 1)
                                        @if ($servicio->tipo_servicio == 2)
                                            <button class="btn btn-info" data-toggle="modal"
                                                wire:click='opernModal({{ $servicio }})'
                                                data-target="#terminar_servicio">Verificar monto</button>
                                        @else
                                            <button class="btn btn-info" {{-- wire:click='opernModal({{ $servicio }})' --}}
                                                wire:click="$dispatch('finalizar-entrega',{{ $servicio }})">Finalizar
                                                Entrega</button>
                                        @endif
                                    @else
                                        <span style="font-weight: bold;"> Finalizado.</span>
                                    @endif
                                    {{-- <input type="checkbox" name="" id="" class="large-checkbox"
                                    disabled 
                                    {{ $servicio->tipo_servicio == 1 ? 'checked' : 'hidden ' }}> --}}
                                </td>
                            </tr>
                        @endforeach
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
                                <x-input-validado label="Papeleta:" placeholder="Papeleta" wire-model="form.folio"
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
                                    <label for="">Papeleta</label>
                                    <input class="form-control" value="{{ $envases->folio }}" placeholder="Folio"
                                        disabled type="text" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="">Monto</label>


                                    <input
                                        class="form-control {{ $errors->has('monto_envases.' . $envases->id . '.cantidad') ? 'is-invalid' : '' }}"
                                        wire:model="monto_envases.{{ $envases->id }}.cantidad" placeholder="Monto"
                                        type="number" onkeyup="monto(this,{{ $envases->id }})" />


                                    @error('monto_envases.' . $envases->id . '.cantidad')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <Label>Violado</Label>

                                    <input {{ $envases->evidencia_recolecta->violate ? 'checked' : '' }}
                                        type="checkbox" class="large-checkbox" disabled />
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
                                                        <span
                                                            class="font-weight-bold checkbox">{{ $diferencia['servicio']['updated_at'] }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p>
                                                        ORIGEN DEL DEPÓSITO: (CLIENTE)
                                                        <span
                                                            class="font-weight-bold checkbox">{{ $diferencia['servicio']['rutaServicios']['servicio']['cliente']['rfc_cliente'] }}</span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <p>
                                                        NÚMERO DE FOLIO DE PAPELETA:
                                                        <span
                                                            class="font-weight-bold checkbox">{{ $diferencia['servicio']['folio'] }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p>
                                                        Sello de seguridad:
                                                        <span
                                                            class="font-weight-bold checkbox">{{ $diferencia['servicio']['sello_seguridad'] }}</span>
                                                    </p>
                                                </div>

                                                <div class="col-md-8 mb-3">
                                                    <p>
                                                        IMPORTE QUE DICE CONTENER: $
                                                        <span
                                                            class="font-weight-bold checkbox">
                                                                {{ number_format($diferencia['monto'], 2, '.', ',') }} MXN
                                                            </span>
                                                    </p>
                                                </div>

                                                <div class="col-md-8 mb-3">
                                                    <p>
                                                        IMPORTE COMPROBADO: $
                                                        <span
                                                            class="font-weight-bold checkbox">
                                                            
                                                           
                                                            {{ number_format($diferencia['cantidad_ingresada'], 2, '.', ',') }} MXN
                                                            </span>
                                                    </p>
                                                </div>

                                                <div class="col-md-8 mb-3">
                                                    <p>DIFERENCIA</p>
                                                    <p>
                                                        FALTANTE <span
                                                            class="checkbox">{{ $diferencia['tipo_dif'] == 0 ? '_X_' : '___' }}</span>
                                                        SOBRANTE <span
                                                            class="checkbox">{{ $diferencia['tipo_dif'] != 1 ? '___' : '_X_' }}</span>
                                                        DE  <span
                                                            class="amount">
                                                            $ {{ number_format($diferencia['diferencia'], 2, '.', ',') }} MXN
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <p>OBSERVACIONES:</p>

                                                    <textarea class="form-control w-full" cols="3" rows="2" wire:model='observaciones.{{ $index }}'>
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
                // $('#terminar_servicio').on('show.bs.modal', function() {
                //     $('#monto_total').val(0);
                //     array_monto = [];
                //     // @this.dispatch('corregirMonto');
                // });
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

                    console.log('entra')
                    console.log(params)
                    const msg = params[0].msg;
                    const tipomensaje = params[1].tipomensaje;
                    const terminar = params[2]?.terminar || '';
                    $('#terminar_servicio').modal('hide');
                    $('#diferencia_mdl').modal('hide');

                    Swal.fire({
                        position: 'center',
                        icon: tipomensaje,
                        title: msg,
                        showConfirmButton: false,
                        timer: 3000
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
                })
            });
        </script>
    @endpush
</div>
