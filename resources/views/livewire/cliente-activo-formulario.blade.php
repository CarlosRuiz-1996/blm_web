<div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Cliente</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Razón Social:" placeholder="Ingrese la razón social"
                                    wire-model="form.razon_social" required type="text" />
                            </div>
                            <div class="col-md-4 mb-3">

                                <x-input-validado label="RFC:" placeholder="Ingrese la RFC"
                                    wire-model="form.rfc_cliente" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">

                                <x-select-validado label="Tipo de cliente:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_cliente_id" required>
                                    @foreach ($ctg_tipo_cliente as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>

                            </div>
                            <!-- Información de contacto -->
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Teléfono:" placeholder="Ingrese la teléfono"
                                    wire-model="form.phone" required type="number" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Correo Electrónico:" placeholder="Ingrese correo electrónico"
                                    wire-model="form.email" required type="email" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="card-header">
                                    <h5 class="text-center">Datos del contacto</h5>
                                </div>
                            </div>
                            <hr />
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Nombre del contacto:" placeholder="Ingrese nombre"
                                    wire-model="form.name" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Apellido paterno:" placeholder="Ingrese apellido paterno"
                                    wire-model="form.paterno" type="text" />

                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Apellido materno:" placeholder="Ingrese apellido materno"
                                    wire-model="form.materno" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Puesto:" placeholder="Ingrese Puesto" wire-model="form.puesto"
                                    type="text" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Domicilio fiscal</h3>
                    </div>
                    <div class="card-body">
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
                                    <input type="text" class="form-control" disabled wire:model='form.municipio'
                                        placeholder="esperando..." required>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label>Estado:</label>
                                    <input type="text" class="form-control" disabled wire:model='form.estado'
                                        placeholder="esperando..." required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select-validado label="Colonia:" placeholder="Selecciona" wire-model="form.ctg_cp_id"
                                    required>

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
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Calle y Número:" placeholder="Calle y Número"
                                wire-model="form.direccion" type="text" />

                        </div>


                        <div class="col-md-6 mb-3">
                            <a href="{{ route('cliente.index') }}" class="btn btn-danger btn-block">Cancelar</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-info btn-block"
                                wire:click="$dispatch('confirm',{{ $form->cliente ? 2 : 1 }})">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>


<script>
    document.addEventListener('livewire:initialized', () => {

        @this.on('confirm', (opcion) => {

            Swal.fire({
                title: '¿Estas seguro?',
                text: opcion == 1 ? "El cliente seran guardado como un cliente activo" :
                    "Los datos del cliente seran actualizados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, adelante!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch(opcion == 1 ? 'save-cliente' : 'edit-cliente');
                }
            })
        })
        Livewire.on('alert', function([message]) {
            var title = message[0];
            var cliente = message[1];
            Swal.fire({
                icon: 'success',
                title: title,
                showConfirmButton: true,
            }).then((result) => {
                if (result.isConfirmed) {

                    if (cliente) {
                        window.location.href =
                            '{{ route('cliente.detalles', [':cliente', ':op']) }}'
                            .replace(':cliente', cliente.id)
                            .replace(':op', 1);

                    } else {
                        window.location.href = '{{ route('cliente.index') }}';
                    }
                }
            })
        });

    });
</script>
