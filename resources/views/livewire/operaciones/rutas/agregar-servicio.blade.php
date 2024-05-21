<div wire:init='loadServicios'>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Servicios</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#servicios" wire:click='getServicios()'>
            Agregar Servicio
            <i class="fa fa-plus" aria-hidden="true"></i>

        </button>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex col-md-12 mb-3">



                            <input type="text" class="form-control w-full" placeholder="Buscar servicio"
                                wire:model.live='form.searchServicio'>

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_servicios && count($ruta_servicios))
                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Monto</th>
                                        <th>Folio</th>
                                        <th>Envases</th>
                                        <th>Tipo servicio</th>
                                        <th>Acciones</th>

                                    </thead>
                                    <tbody>
                                        @foreach ($ruta_servicios as $servicio)
                                            <tr>

                                                <td>{{ $servicio->id }}</td>
                                                <td>{{ $servicio->servicio->cliente->razon_social }}</td>
                                                <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>

                                                <td>{{ $servicio->monto }}</td>
                                                <td>{{ $servicio->folio }}</td>
                                                <td>{{ $servicio->envases }}</td>
                                                <td>{{ $servicio->tipo_servicio == 1 ? 'ENTREGA' : 'RECOLECCIÓN' }}</td>
                                                <td>
                                                    <button class="btn text-danger" title="Eliminar"
                                                        wire:click="$dispatch('confirm-delete-servicio',{{ $servicio }})">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>


                                                    <button class="btn text-success" title="Editar"
                                                        wire:click="servicioEdit({{ $servicio }})"
                                                        data-toggle="modal" data-target="#servicios_edit">
                                                        <i class="fa fa-lg fa-fw fa-pen" aria-hidden="true"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if ($readyToLoad)
                                    <div class="alert alert-secondary" role="alert">
                                        No hay datos disponibles </div>
                                @else
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            {{-- <span class="visually-hidden">Loading...</span> --}}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal servicios --}}
    <x-adminlte-modal wire:ignore.self id="servicios" title="Agregar servicios a la ruta" theme="info"
        icon="fas fa-car" size='xl' disable-animations>


        <div class="d-flex mb-3">



            <input type="text" class="form-control" placeholder="Buscar por RFC o Razon social"
                wire:model.live='form.searchClienteModal'>


            <select class="form-control ml-2" wire:model.live='form.searchClienteSelect'>
                <option value="">Selecciona un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->razon_social . '-' . $cliente->rfc_cliente }}
                    </option>
                @endforeach
            </select>
            <button title="Limpiar Filtros" wire:click='limpiarFiltro' class="btn btn-sm btn-primary ml-2"><i
                    class="fa fa-eraser" aria-hidden="true"></i></button>
        </div>

        <div>
            @if ($servicios && count($servicios))
                <table class="table table-hover table-striped">
                    <thead class="table-info">
                        <th></th>
                        <th>Servcicio</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Monto</th>
                        <th>Papeleta</th>
                        <th>Contenedor</th>
                        <th>Recolección</th>

                    </thead>
                    <tbody>


                        @foreach ($servicios as $servicio)
                            <tr x-data="{ checkServicio: false, monto: '', folio: '', contenedor: '', tipo:'0' }">
                                <td>
                                    <input type="checkbox" wire:model='selectServicios.{{ $servicio->id }}'
                                        x-model="checkServicio" wire:click="resetError('{{ $servicio->id }}')" />
                                </td>
                                <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                <td>{{ $servicio->cliente->razon_social }}</td>
                                <td>{{ $servicio->sucursal->sucursal->direccion .
                                    ' ' .
                                    $servicio->sucursal->sucursal->cp->cp .
                                    '' .
                                    $servicio->sucursal->sucursal->cp->estado->name }}


                                </td>
                                <td>
                                    <x-input-validado x-bind:value="checkServicio ? monto : ''" style="margin-top: -20%"
                                        x-bind:disabled="!checkServicio" placeholder="Monto"
                                        wire-model='montoArray.{{ $servicio->id }}' type="number" />
                                </td>
                                <td>
                                    <x-input-validado x-bind:value="checkServicio ? folio : ''" style="margin-top: -20%"
                                        x-bind:disabled="!checkServicio" placeholder="Papeleta"
                                        wire-model='folioArray.{{ $servicio->id }}' type="text" />
                                </td>
                                <td>
                                    <x-input-validado x-bind:value="checkServicio ? contenedor : ''"
                                        style="margin-top: -20%" x-bind:disabled="!checkServicio" placeholder="Envases"
                                        wire-model='envaseArray.{{ $servicio->id }}' type="number" />
                                </td>
                                <td>
                                    <input type="checkbox"  x-bind:value="checkServicio ? tipo : '1'"  x-bind:disabled="!checkServicio"
                                    wire:model='selectServiciosRecolecta.{{ $servicio->id }}'
                                     wire:click="resetError('{{ $servicio->id }}')" />
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                </table>
                @if ($servicios->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $servicios->links() }}
                    </div>
                @endif
                <div class="text-center col-md-12 mb-3">
                    <button class="btn btn-info btn-xl " wire:click='$dispatch("confirm-servicio")'>Guardar</button>
                </div>
            @else
                @if ($readyToLoad)
                    <div class="alert alert-secondary" role="alert">
                        No hay datos disponibles </div>
                @else
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            {{-- <span class="visually-hidden">Loading...</span> --}}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </x-adminlte-modal>
    <x-adminlte-modal wire:ignore.self id="servicios_edit" title="Editar datos del servicio" theme="info"
        icon="fas fa-car" size='xl' disable-animations>



        <div>
            <x-input-validado label="Descripción:" :readonly="true" placeholder="Descripción"
                wire-model='form.servicio_desc' type="text" />

            <x-input-validado label="Monto:" placeholder="Monto" wire-model='form.monto' type="number" />
            <x-input-validado label="Papeleta:" placeholder="Papeleta" wire-model='form.folio' type="text" />
            <x-input-validado label="Envases:" placeholder="Envases" wire-model='form.envases' type="number" />

            <div class="text-center col-md-12 mb-3">
                <button class="btn btn-info btn-xl " wire:click='$dispatch("confirm-edit-servicio")'>Guardar</button>
            </div>

        </div>
    </x-adminlte-modal>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm-servicio', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Los servicios se agregaran a la ruta",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('add-servicio-ruta');
                        }
                    })
                })
                @this.on('confirm-delete-servicio', (servicio) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Se borrara de la base de datos, esto puede traer problemas con el sistema",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('delete-servicio', {
                                servicio: servicio
                            });
                        }
                    })
                })
                @this.on('confirm-edit-servicio', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Los cambios echos se apliacaran al servicio seleccionado",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('update-servicio');
                        }
                    })
                })
                Livewire.on('success-servicio', function(message) {
                    $('#servicios').modal('hide');
                    $('#servicios_edit').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-servicio', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un problema, intenta mas tarde',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });

            // detecto cuando cierra modal y limpio array
            $(document).ready(function() {
                $('#servicios').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
                $('#servicios_edit').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
            });
        </script>
    @endpush

</div>
