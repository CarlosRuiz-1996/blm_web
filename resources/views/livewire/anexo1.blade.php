<div>
    <div class="container-fluid">

        <div class="row">
            {{-- cabecera --}}
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <h5><b>Razón Social: </b>{{ $cliente->razon_social }}.</h5>
                        <h5><b>RFC: </b>{{ $cliente->rfc_cliente }}.</h5>

                        <h5><b>Tipo de cliente: </b>{{ $cliente->tipo_cliente->name }}.</h5>
                        <h5><b>Contacto:
                            </b>{{ $cliente->user->name . ' ' . $cliente->user->paterno . ' ' . $cliente->user->materno
                            }}.
                        </h5>
                        <h5><b>Telefono: </b>{{ $cliente->phone }}.</h5>
                        <h5><b>Correo: </b>{{ $cliente->user->email }}.</h5>
                        <h5>
                            <b>Dirección: </b>
                            {{ $cliente->direccion }}
                            {{ $cliente->cp->cp }}
                            {{ $cliente->cp->municipio->municipio }},
                            {{ $cliente->cp->estado->name }}.
                        </h5>

                    </div>
                </div>
            </div>

            {{-- servicios --}}
            <div class="col-md-12">
                <div class="card card-outline card-info">

                    <div class="card-body text-end w-100">
                        <div class="row">
                            <div class="mb-2">
                                <x-adminlte-button label="Agregar sucursal" data-toggle="modal"
                                    data-target="#modalNueva" class="bg-secondary" />
                            </div>
                            <table class="table">
                                <thead class="table-info">
                                    <th>PDA</th>
                                    <th>Descripción</th>
                                    <th>Unidad de medida</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Sucursal</th>
                                </thead>
                                <tbody>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <x-adminlte-button label="Asignar sucursal" data-toggle="modal"
                                            wire:click='servicio(5)' data-target="#modalElegir" class="bg-secondary" />
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <a href="/ventas" class="btn btn-danger btn-block">Cancelar</a>
            </div>
            <div class="col-md-6 mb-3">
                <button type="submit" class="btn btn-info btn-block"
                    wire:click="$dispatch('confirm',2)">Guardar</button>
            </div>
        </div>
        
    </div>



    {{-- sucursal nueva --}}
    <x-adminlte-modal wire:ignore.self id="modalNueva" title="Nueva sucursal" theme="info" icon="fas fa-bolt" size='xl'
        disable-animations>
        {{-- sucursales --}}

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    {{-- sucursal general --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Sucursal:" placeholder="Ingrese el nombre de la sucursal"
                                wire-model="form.sucursal" required type="text" />
                        </div>
                        <div class="col-md-4 mb-3">

                            <x-input-validado label="Correo:" placeholder="Ingrese la correo de la sucursal"
                                wire-model="form.correo" type="text" />
                        </div>

                        <!-- Información de contacto -->
                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Teléfono:" placeholder="Ingrese la teléfono de la sucursal"
                                wire-model="form.phone" required type="number" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Contacto:" placeholder="Ingrese contacto de la sucursal"
                                wire-model="form.contacto" required type="email" />
                        </div>


                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Cargo:" placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.cargo" type="text" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Fecha de evaluación:"
                                placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.fecha_evaluacion" type="date" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Fecha de inicio de servicio:"
                                placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.fecha_inicio_servicio" type="date" />
                        </div>

                    </div>
                    {{-- direccion --}}
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-input-validado label="Codigo postal:" placeholder="Codigo postal" wire-model="form.cp"
                                type="number" />
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
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3">
                            <x-input-validado label="Calle y Número:" placeholder="Calle y Número"
                                wire-model="form.direccion" type="text" />

                        </div>
                        <div class="col-md-3 mb-3">
                            <x-input-validado label="Referencias:" placeholder="Alguna referencia"
                                wire-model="form.referencias" type="text" />

                        </div>


                        <div class="col-md-3" style="margin-top: 3%">
                            <button type="submit" class="btn btn-info btn-block"
                                wire:click="$dispatch('confirm', 1)">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-adminlte-modal>

    {{-- elegir sucursal --}}
    <x-adminlte-modal wire:ignore.self id="modalElegir" title="Elegir sucursal" theme="info" icon="fas fa-bolt"
        size='md' disable-animations>
        {{--
        <x-adminlte-button label="Agregar sucursal" onclick="Nueva()" class="bg-secondary" /> --}}
        <div class="col-md-12 mb-3">
            <label for="">Sucursales</label>
            <select class="form-control" wire:model.live="form.sucursal_id">


                {{-- @if ($sucursales)
                @foreach ($sucursales as $sucursal)
                <option value="{{ $sucursal->id }}">{{ $cp->sucursal }}</option>
                @endforeach
                @else
                <option value="">Sin sucursales asignadas...</option>
                @endif --}}
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <x-adminlte-button class="btn-flat" type="submit" wire:click='sucursal_servicio' label="Submit" theme="info"
            icon="fas fa-lg fa-save" />

    </x-adminlte-modal>

    @push('js')
    <script>
     

        document.addEventListener('livewire:initialized', () => {

            @this.on('confirm', (opcion) => {

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: opcion == 1 ? "La sucursal sera guardada en la base de datos" :
                        "Los servicios seran agregados a las sucursales",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch(opcion == 1 ? 'save-sucursal' : 'save-servicios');
                    }
                })
            })
            
            Livewire.on('alert', function([message]) {
            Swal.fire({
                icon: 'success',
                title: message[0],
                showConfirmButton: true,
            }).then((result) => {
                if (result.isConfirmed) {

                    if (message[1]) {
                        ('#modalNueva').modal('hide');
                    } else {
                        window.location.href = '{{ route('cliente.index') }}';
                    }
                }
            });

            Livewire.on('error', function(message) {

                console.log('error');
                    // Swal.fire({
                    //     // position: 'top-end',
                    //     icon: 'error',
                    //     title: "Oops...",
                    //     text: message,
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // })
                });
        });

        });
    </script>
    @endpush
</div>