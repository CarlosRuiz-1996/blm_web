<div>

    {{-- sucursal nueva --}}
    <div class="modal fade" wire:ignore.self id="modalNueva" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Elegir sucursal</h5>
                    <button type="button" onclick="cancelarNuevaSucursal()" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                {{-- sucursal general --}}
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Sucursal:"
                                            placeholder="Ingrese el nombre de la sucursal" wire-model="form.sucursal"
                                            required type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">

                                        <x-input-validado label="Correo:" placeholder="Ingrese la correo de la sucursal"
                                            wire-model="form.correo" type="email" />
                                    </div>

                                    <!-- Información de contacto -->
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-telefono label="Teléfono:"
                                            placeholder="Ingrese la teléfono de la sucursal" wire-model="form.phone"
                                            required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-validado label="Contacto:"
                                            placeholder="Ingrese contacto de la sucursal" wire-model="form.contacto"
                                            required type="email" />
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <x-input-validado label="Cargo:"
                                            placeholder="Ingrese cargo del contacto de la sucursal"
                                            wire-model="form.cargo" type="text" />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-date label="Fecha de evaluación:"
                                            placeholder="Ingrese cargo del contacto de la sucursal"
                                            wire-model="form.fecha_evaluacion" type="date" />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-date label="Fecha de inicio de servicio:"
                                            placeholder="Ingrese cargo del contacto de la sucursal"
                                            wire-model="form.fecha_inicio_servicio" type="date" />
                                    </div>
                                </div>
                                {{-- direccion --}}
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <x-input-validado label="Codigo postal:" placeholder="Codigo postal"
                                            wire-model="form.cp" type="number" />
                                    </div>
                                    <div class="col-md-2 mb-3" style="margin-top: 3%">
                                        <div class="form-group">
                                            <button wire:click='validarCp' class="btn btn-secondary btn-block">Validar
                                                cp</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label>Alcaldia/Municipio:</label>
                                            <input type="text" class="form-control" disabled
                                                wire:model='form.municipio' placeholder="esperando..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label>Estado:</label>
                                            <input type="text" class="form-control" disabled wire:model='form.estado'
                                                placeholder="esperando..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <x-select-validado label="Colonia:" placeholder="Selecciona"
                                            wire-model="form.ctg_cp_id" required>

                                            @if ($form->colonias)
                                                @foreach ($form->colonias as $cp)
                                                    @if ($form->ctg_cp_id == $cp->id)
                                                        <option value="{{ $cp->id }}" selected>{{ $cp->colonia }}
                                                        </option>
                                                    @break

                                                @else
                                                    <option value="{{ $cp->id }}">{{ $cp->colonia }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="">Esperando...</option>
                                        @endif

                                    </x-select-validado>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Calle y Número:" placeholder="Calle y Número"
                                        wire-model="form.direccion" type="text" />

                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Referencias:" placeholder="Alguna referencia"
                                        wire-model="form.referencias" type="text" />

                                </div>


                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-info btn-block"
                                        wire:click="$dispatch('confirm-sucursal')">Guardar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelarNuevaSucursal()" class="btn btn-danger"
                    data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>

</div>

{{-- elegir sucursal --}}
<div class="modal fade" wire:ignore.self id="modalElegir" tabindex="-1" aria-labelledby="exampleModalLabel2"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel2">Elegir sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    {{-- Success is as dangerous as failure. --}}
                    <x-adminlte-button label="Agregar sucursal" onclick="ocultar()" class="bg-primary" />
                    <x-select-validado label="Sucursales:" placeholder="" wire-model="form.sucursal_id" required>
                        @if ($sucursales && count($sucursales))
                            @foreach ($sucursales as $sucursal)
                                {{-- <option value="{{ $sucursal->id }}"> --}}
                                <option value="{{ $sucursal->id }}">
                                    {{-- {{ $selectedSucursalId == $sucursal->id ? 'selected' : '' }}> --}}

                                    {{ $sucursal->sucursal }}</option>
                            @endforeach
                        @else
                            <option value="">Sin sucursales</option>
                        @endif
                    </x-select-validado>
                </div>

            </div>
            <div class="modal-footer">
                @if ($sucursales && count($sucursales))
                    {{-- <x-adminlte-button class="btn-flat ml-2" type="submit" wire:click='sucursal_servicio'
                        label="Asignar" theme="primary" icon="fas fa-lg fa-save" /> --}}
                    <button type="button" class="btn btn-primary"
                        wire:click='sucursal_servicio'>Siguente</button>
                @endif
                <button type="button" class="btn btn-danger" wire:click='clean()'
                    data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" wire:ignore.self id="modalMemo" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Complementos del servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">

                        <x-input-validado label="Grupo comercial:" placeholder="Grupo comercial:"
                            wire-model="form_memo.grupo" type="text" />

                    </div>
                    <div class="col-md-4 mb-3">

                        <x-select-validado label="Tipo de solicitud:" placeholder="Seleccione"
                            wire-model="form_memo.ctg_tipo_solicitud_id" required>
                            @foreach ($ctg_tipo_solicitud as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                            @endforeach
                        </x-select-validado>

                    </div>
                    <div class="col-md-4 mb-3">

                        <x-select-validado label="Tipo de servicio:" placeholder="Seleccione"
                            wire-model="form_memo.ctg_tipo_servicio_id" required>

                            @foreach ($ctg_tipo_servicio as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                            @endforeach
                        </x-select-validado>

                    </div>
                    <div class="col-md-3 mb-3">
                        <x-select-validadolive label="HORARIO DE ENTREGA:" placeholder="Selecciona"
                            wire-model="form_memo.horarioEntrega" required>

                            @foreach ($ctg_horario_entrega as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                            @endforeach

                        </x-select-validadolive>

                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select-validadolive label="DIA DE ENTREGA:" placeholder="Selecciona"
                            wire-model="form_memo.diaEntrega" required>
                            @foreach ($ctg_dia_entrega as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                </option>
                            @endforeach

                        </x-select-validadolive>
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select-validadolive label="HORARIO DE SERVICIO:" placeholder="Selecciona"
                            wire-model="form_memo.horarioServicio" required>
                            @foreach ($ctg_horario_servicio as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                </option>
                            @endforeach

                        </x-select-validadolive>
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select-validadolive label="DIA DE SERVICIO:" placeholder="Selecciona"
                            wire-model="form_memo.diaServicio" required>
                            @foreach ($ctg_dia_servicio as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                </option>
                            @endforeach

                        </x-select-validadolive>
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-select-validadolive label="CONSIGNATARIO:" placeholder="Selecciona"
                            wire-model="form_memo.consignatorio" required>
                            @foreach ($ctg_consignatario as $ctg)
                                <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                </option>
                            @endforeach

                        </x-select-validadolive>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" wire:click='terminar()'>Terminar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    wire:click='clean()'>Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('open-sucursal', () => {
                $('#modalElegir').modal('show');
            })

            @this.on('confirm-sucursal', () => {
                console.log('entra')
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "La sucursal sera guardada en la base de datos",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('entra2')
                        @this.dispatch('save-sucursal-servicio');
                    }
                })
            })

            Livewire.on('success', function([message]) {
                Swal.fire({
                    icon: 'success',
                    title: message[0],
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        if (message[1]) {
                            $('#modalNueva').modal('hide');
                            if (opcion_cliente == 0) {

                                $('#modalElegir').modal('show');
                            }
                        }
                    }
                });
            });

            Livewire.on('error', function([message]) {
                Swal.fire({
                    icon: 'error',
                    title: message[0],
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        if (message[1]) {
                            $('#modalNueva').modal('hide');
                            $('#modalElegir').modal('show');
                        }
                    }
                });
            });


            Livewire.on('close-memo', function([message]) {
                $('#modalMemo').modal('hide');

            });
            @this.on('sucursal-servico-memorandum', () => {
                $('#modalMemo').modal('show');
                $('#modalElegir').modal('hide');
            });

            Livewire.on('resetSelect2', function() {
                $('.cliente_sucursal').val('').trigger('change');

            });

        });
        var opcion_cliente = 0;

        function ocultar(op = 0) {
            opcion_cliente = op;

            $('#modalNueva').modal('show');
            $('#modalElegir').modal('hide');
        }

        function cancelarNuevaSucursal() {

            $('#modalNueva').modal('hide');
            $('#modalElegir').modal('show');

        }
    </script>
@endpush
</div>
