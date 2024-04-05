<div>
    <div class="container-fluid">

        <div class="row">
            {{-- cabecera --}}
            <div class="col-md-12">
                <livewire:cliente-cabecera :cliente="$form->cliente_id" />
                {{-- {{$cliente}} --}}
            </div>
            {{-- servicios --}}
            <div class="col-md-12">
                <div class="card card-outline card-info">

                    <div class="card-body text-end w-100">
                        <div class="row">
                            <div class="mb-2">
                                <x-adminlte-button label="Agregar sucursal" data-toggle="modal" data-target="#modalNueva"
                                    wire:click='limpiarDatos' class="bg-primary" />
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

                                    @if ($servicios)

                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>
                                                    {{ $servicio->servicio->ctg_servicio->folio }}</td>
                                                <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                                <td>{{ $servicio->servicio->ctg_servicio->unidad }}</td>
                                                <td>{{ $servicio->servicio->cantidad }}</td>
                                                <td>{{ $servicio->servicio->precio_unitario }}</td>
                                                <td>{{ $servicio->servicio->subtotal }}</td>
                                                <td>
                                                    @php
                                                        $sucursalAsignada = null;
                                                        $servicioId = $servicio->servicio->id;
                                                        $sucursalAsignadaId = 0;
                                                        // Verificar si hay una sucursal asignada para este servicio en la sesión
                                                        if (Session::has('servicio-sucursal')) {
                                                            $serviciosSucursal = Session::get('servicio-sucursal');
                                                            foreach ($serviciosSucursal as $servicioSucursal) {
                                                                if ($servicioSucursal['servicio_id'] == $servicioId) {
                                                                    $sucursalAsignada = $servicioSucursal['nombre'];
                                                                    $sucursalAsignadaId =
                                                                        $servicioSucursal['sucursal_id'];
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($sucursalAsignada)
                                                        Sucursal:<b>{{ $sucursalAsignada }}</b>
                                                        <br>
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            data-toggle="modal"
                                                            wire:click="getSucursalesForEdit({{ $servicioId }},{{ $sucursalAsignadaId }})"
                                                            data-target="#modalElegir">
                                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                            wire:click='eliminarSucursalServicio({{ $servicioId }})'
                                                            title="Eliminar sucursal">
                                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            title="Detalles de la sucursal" data-target="#modalShow"
                                                            data-toggle="modal"
                                                            wire:click='getSucursal({{ $sucursalAsignadaId }})'>
                                                            <i class="fa fa-lg fa-fw fa-info-circle"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                            title="Detalles de la sucursal" data-toggle="modal"
                                                            wire:click="getSucursales({{ $servicioId }})"
                                                            data-target="#modalElegir">
                                                            <i class="fa fa-lg fa-fw fa-plus"></i>
                                                        </button>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif
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
    <x-adminlte-modal wire:ignore.self id="modalNueva" title="Nueva sucursal" theme="info" icon="fas fa-bolt"
        size='xl' disable-animations>
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
                                wire-model="form.correo" type="email" />
                        </div>

                        <!-- Información de contacto -->
                        <div class="col-md-4 mb-3">
                            <x-input-validado-telefono  label="Teléfono:" placeholder="Ingrese la teléfono de la sucursal"
                                wire-model="form.phone" required  />
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
                                wire:click="$dispatch('confirm', 1)">Guardar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-adminlte-modal>
    {{-- detalles nueva --}}
    <x-adminlte-modal wire:ignore.self id="modalShow" title="Detalles de sucursal" theme="info" icon="fas fa-bolt"
        size='xl' disable-animations>
        {{-- sucursales --}}

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    {{-- sucursal general --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Sucursal:</label>
                            <x-input wire:model="form.sucursal" type="text" disabled />
                        </div>
                        <div class="col-md-4 mb-3">

                            <label>Correo:</label>
                            <x-input wire:model="form.correo" type="email" disabled />
                        </div>

                        <!-- Información de contacto -->
                        <div class="col-md-4 mb-3">
                            <label>Teléfono:</label>
                            <x-input wire:model="form.phone" type="number" disabled />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Contacto:</label>
                            <x-input wire:model="form.contacto" type="email" disabled />
                        </div>


                        <div class="col-md-6 mb-3">
                            <label>Cargo:</label>
                            <x-input wire:model="form.cargo" type="text" disabled />
                        </div>


                        <div class="col-md-12 mb-3">
                            <label>Direccion:</label>
                            <x-input wire:model="form.direccion_completa" type="text" disabled />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-adminlte-modal>
    {{-- elegir sucursal --}}
    <div class="modal fade" wire:ignore.self id="modalElegir" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Elegir sucursal</h5>
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <x-select-validado label="Sucursales:" placeholder="" wire-model="form.sucursal_id" required>
                            @if ($sucursales && count($sucursales) > 0)
                                @foreach ($sucursales as $sucursal)
                                    {{-- <option value="{{ $sucursal->id }}"> --}}
                                    <option value="{{ $sucursal->id }}"
                                        {{ $selectedSucursalId == $sucursal->id ? 'selected' : '' }}>

                                        {{ $sucursal->sucursal }}</option>
                                @endforeach
                            @else
                                <option value="">Sin sucursales</option>
                            @endif
                        </x-select-validado>
                    </div>
                    @if ($sucursales && count($sucursales) > 0)
                        @if ($selectedSucursalId == '')
                            <x-adminlte-button class="btn-flat ml-2" type="submit" wire:click='sucursal_servicio'
                                label="Asignar" theme="info" icon="fas fa-lg fa-save" />
                        @else
                            <x-adminlte-button class="btn-flat ml-2" type="submit"
                                wire:click='actualizarSucursalServicio' label="Cambiar" theme="info"
                                icon="fas fa-lg fa-save" />
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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

            Livewire.on('success', function([message]) {
                Swal.fire({
                    icon: 'success',
                    title: message[0],
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        if (message[1]) {
                            $('#modalNueva').modal('hide');
                        } else {
                            window.location.href = '/ventas';
                        }
                    }
                });
            });
            Livewire.on('sucursal-delete', function(message) {
                Swal.fire({
                    // position: 'top-end',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                });

            });

            Livewire.on('alert-sucursal-add', function(message) {

                Swal.fire({
                    // position: 'top-end',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#modalElegir').modal('hide'); // Cierra el modal usando jQuery

            });
            $('#modalShow').on('hidden.bs.modal', function() {
                // console.log('El modal se ha cerrado');
                @this.dispatch('limpiar');
            });
            Livewire.on('error', function([message]) {



                if (message[1]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/ventas';
                        }
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });


        });
    </script>
@endpush
</div>
