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
                                    <x-input-validado label="Razón Social:"  placeholder="Ingrese la razón social" wire-model="form.razon_social" required wire-attribute="form.razon_social" type="text" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">

                                    <x-input-validado label="RFC:"  placeholder="Ingrese la RFC" wire-model="form.rfc_cliente" wire-attribute="form.phone" type="text" />
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
                                    {{-- <x-input-validado label="Teléfono:"  placeholder="Ingrese la teléfono" wire:model="form.phone" required /> --}}
                                    <x-input-validado label="Teléfono:" placeholder="Ingrese la teléfono" wire-model="form.phone" required wire-attribute="form.phone" type="tel" />

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    
                                    <x-input-validado label="Correo Electrónico:" placeholder="Ingrese correo electrónico" wire-model="form.email" required wire-attribute="form.email" type="email" />

                                </div>

                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="card-header">
                                    <h5 class="text-center">Datos del contacto</h5>
                                </div>
                            </div>
                            <hr />
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    
                                    <x-input-validado label="Nombre del contacto:"  placeholder="Ingrese nombre" wire-model="form.name" wire-attribute="form.name" type="text" />

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <x-input-validado label="Apellido paterno:"  placeholder="Ingrese apellido paterno" wire-model="form.paterno" wire-attribute="form.paterno" type="text" />


                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <x-input-validado label="Apellido materno:"  placeholder="Ingrese apellido materno" wire-model="form.materno" wire-attribute="form.materno" type="text" />


                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    
                                    <x-input-validado label="Puesto:"  placeholder="Ingrese Puesto" wire-model="form.puesto" wire-attribute="form.puesto" type="text" />

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
                                    
                                    <x-input-validado label="Codigo postal:"  placeholder="Codigo postal" wire-model="form.cp" wire-attribute="form.cp" type="number" />

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
                                    
                                    <x-input-validado label="Calle y Número:"  placeholder="Calle y Número" wire-model="form.direccion" wire-attribute="form.direccion" type="number" />

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
