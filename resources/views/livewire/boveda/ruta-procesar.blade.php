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
                                                wire:click="$dispatch('finalizar-entrega',{{ $servicio }})">Finalizar Entrega</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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


                            @foreach ($form->servicio->envases_servicios as $envases)
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
                                        type="text" onkeyup="monto(this,{{ $envases->id }})" />


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
                    <button class="btn btn-dark" data-dismiss="modal">Cerrar</button>

                    <button class="btn btn-primary" wire:click="validar">Verificar</button>

                </div>
            </div>
        </div>


        @push('js')
            <script>
                $('#terminar_servicio').on('hidden.bs.modal', function() {
                    Livewire.dispatch('clean');
                });
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

                    Livewire.on('diferencia', function([message]) {
                        Swal.fire({
                            title: "Diferencia de montos",
                            text: message[0],
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: true,

                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Si, generar hoja de diferencia.",
                            cancelButtonText: "No, Corregir",

                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success"
                                });
                            }
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
                            $('#terminar_servicio').modal('hide');
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
                                @this.dispatch('finaliza-entrega',{servicio:servicio});
                            }
                        })
                    })
                });
            </script>
        @endpush
    </div>
