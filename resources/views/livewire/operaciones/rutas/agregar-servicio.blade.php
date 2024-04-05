<div>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Servicios</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#servicios" wire:click='getServicios()'>
            Agregar Servicio
            <i class="fa fa-plus" aria-hidden="true"></i>

        </button>
    </div>


    {{-- Modal vehiculos --}}
    <x-adminlte-modal wire:ignore.self id="servicios" title="Agregar servicios a la ruta" theme="info"
        icon="fas fa-car" size='xl' disable-animations>

            
            <div class="d-flex mb-3">



                <input type="text" class="form-control" placeholder="Buscar por RFC o Razon social"
                    wire:model.live='form.searchClienteModal'>

                
                <select class="form-control ml-2" wire:model.live='form.searchClienteSelect'>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social.'-'.$cliente->rfc_cliente }}</option>
                    @endforeach
                </select>
                <button title="Limpiar Filtros" wire:click='limpiarFiltro' class="btn btn-sm btn-primary ml-2"><i class="fa fa-eraser" aria-hidden="true"></i></button>
            </div>
            
        <div>
            <table class="table table-hover table-striped">
                <thead class="table-info">
                    <th></th>
                    <th>Servcicio</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Monto</th>
                    <th>Papeleta</th>
                    <th>Contenedor</th>

                </thead>
                <tbody>


                    @foreach ($servicios as $servicio)
                        <tr x-data="{ checkServicio: false, monto: '', folio: '', contenedor: '' }">
                            <td>
                                <input type="checkbox" wire:model='selectServicios.{{ $servicio->id }}'
                                    x-model="checkServicio" wire:click="resetError('{{ $servicio->id }}')" />

                            </td>
                            <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                            <td>{{ $servicio->anexo->cliente->razon_social }}</td>
                            <td>{{ $servicio->sucursal->direccion .
                                ' ' .
                                $servicio->sucursal->cp->cp .
                                '' .
                                $servicio->sucursal->cp->estado->name }}
                            </td>
                            <td>
                                <x-input-validado x-bind:value="checkServicio ? monto : ''"  style="margin-top: -20%"
                                    x-bind:disabled="!checkServicio" placeholder="Monto"
                                    wire-model='montoArray.{{ $servicio->id }}' type="number" />
                            </td>
                            <td>
                                <x-input-validado x-bind:value="checkServicio ? folio : ''" style="margin-top: -20%"
                                    x-bind:disabled="!checkServicio" placeholder="Papeleta"
                                    wire-model='folioArray.{{ $servicio->id }}' type="text" />
                            </td>
                            <td>
                                <x-input-validado x-bind:value="checkServicio ? contenedor : ''" style="margin-top: -20%"
                                    x-bind:disabled="!checkServicio" placeholder="Envases"
                                    wire-model='envaseArray.{{ $servicio->id }}' type="number" />
                            </td>
                        </tr>
                    @endforeach

                  

                </tbody>
            </table>
            {{ $servicios->links() }}
            <div class="text-center col-md-12 mb-3">
                <button class="btn btn-info btn-xl " wire:click='$dispatch("confirm-servicio")'>Guardar</button>
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
                @this.on('confirm-delete-vehiculo', (vehiculo) => {

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
                            @this.dispatch('delete-vehiculo', {
                                vehiculo: vehiculo
                            });
                        }
                    })
                })

                Livewire.on('success-servicio', function(message) {
                    $('#servicios').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-servicio', function(message) {
                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });

            // detecto cuando cierra modal y limpio array
            $(document).ready(function() {
                $('#vehiculos').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-vehiculos');
                });
            });
        </script>
    @endpush

</div>
