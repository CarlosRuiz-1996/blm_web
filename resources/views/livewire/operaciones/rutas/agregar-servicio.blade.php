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
                {{-- x-data="{ enabled: false, monto: '' }" --}}
                    <tr >
                        <td>
                            {{-- @click="enabled = !enabled; if (!enabled) { monto = '' }" --}}
                            <input type="checkbox" 
                                wire:model='selectServicios.{{$servicio->id}}'
                                
                            />
                            
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
                        <x-input-validado label="" placeholder="Ingrese monto" wire-model='montoArray.{{ $servicio->id }}'
                        type="text" />
                        </td>
                        <td>
                            <x-input-validado label="" placeholder="Ingrese papeleta" wire-model='folioArray.{{ $servicio->id }}'
                                type="text" />
                        </td>
                        <td>
                            <x-input-validado label="" placeholder="Ingrese envases" wire-model='envaseArray.{{ $servicio->id }}'
                                type="text" />
                        </td>
                    </tr>
                @endforeach



            </tbody>
        </table>
        <button wire:click='addServicios()'>Guardar</button>
    </x-adminlte-modal>

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('confirm-vehiculo', () => {

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "Los vehiculos se agregaran a la ruta",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('add-vehiculos-ruta');
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
                $('#vehiculos').modal('hide');
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
