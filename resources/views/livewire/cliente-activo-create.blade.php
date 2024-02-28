<div>
    <style>
        
    </style>
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
                                <div class="form-group">
                                    <label>Razón Social:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;" wire:model='form.razon_social'
                                        placeholder="Ingrese la razón social">
                                    <x-input-error for="form.razon_social"  />

                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>RFC:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;" wire:model='form.rfc_cliente'
                                        placeholder="Ingrese el RFC">
                                    <x-input-error for="form.rfc_cliente" />

                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>Tipo de cliente:</label>
                                    <select class="form-control" wire:model='form.ctg_tipo_cliente_id'>
                                        <option value="0" selected disabled>Seleccione</option>
                                        @foreach ($ctg_tipo_cliente as $ctg)
                                            <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="form.ctg_tipo_cliente_id" />

                                </div>
                            </div>
                            <!-- Información de contacto -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Teléfono:</label>
                                    <input type="number" class="form-control"  wire:model='form.phone'
                                        placeholder="Ingrese el teléfono">
                                    <x-input-error for="form.phone" />

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Correo Electrónico:</label>
                                    <input type="email" class="form-control"  wire:model='form.email'
                                        placeholder="Ingrese el correo electrónico">
                                    <x-input-error for="form.email" />

                                </div>
                                {{-- <x-input-validado placeholder="Ingrese el Puesto" wire:model="puesto" required /> --}}

                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="card-header">
                                    <h5 class="text-center">Datos del contacto</h5>
                                </div>
                            </div>
                            <hr />
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Nombre del contacto:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;"
                                        placeholder="Ingrese el nombre del Contacto" wire:model='form.name'>
                                    <x-input-error for="form.name" />


                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Apellido Paterno:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;"
                                        placeholder="Ingrese el apellido paterno del Contacto"
                                        wire:model='form.paterno'>
                                    <x-input-error for="form.paterno" />

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Apellido Materno:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;" wire:model='form.materno'
                                        placeholder="Ingrese el apellido materno del Contacto">
                                    <x-input-error for="form.materno" />

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Puesto:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;" wire:model='form.puesto'
                                        placeholder="Ingrese el Puesto">
                                    <x-input-error for="form.puesto" />

                                </div>
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
                                <div class="form-group">
                                    <label>Codigo postal:</label>
                                    <input type="number" class="form-control" wire:model='form.cp'
                                        placeholder="Codigo postal" required>
                                        <x-input-error for="form.cp" />
                                </div>
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
                                <div class="form-group">
                                    <label>Colonia:</label>
                                    <select wire:model="form.ctg_cp_id" class="w-full form-control">
                                        @if ($form->colonias)
                                            <option value="">Selecciona una colonia</option>

                                            @foreach ($form->colonias as $cp)
                                                <option value="{{ $cp->id }}">{{ $cp->colonia }}</option>
                                            @endforeach
                                        @else
                                            <option value="">Esperando...</option>
                                        @endif

                                    </select>
                                    <x-input-error for="form.ctg_cp_id" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label>Calle y Número:</label>
                                    <input type="text" class="form-control" style="text-transform:uppercase;" wire:model="form.direccion"
                                        placeholder="Ingrese la Calle y Número">
                                    <x-input-error for="form.direccion" />
                                </div>
                            </div>


                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-danger btn-block">Cancelar</button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-info btn-block"
                                    wire:click="$dispatch('confirm')">Guardar</button>
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

        @this.on('confirm', () => {

            Swal.fire({
                title: '¿Estas seguro?',
                text: "El cliente sera guardado",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, adelante!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('save-cliente');
                }
            })
        })
        Livewire.on('alert', function(message) {
            Swal.fire({
                // position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: true,
                
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('cliente.index') }}';
                }
            })
        });

    });
</script>
