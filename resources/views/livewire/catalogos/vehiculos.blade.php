<div class="">
    <div class="d-sm-flex align-items-center justify-content-between">


        <h1 class="ml-2">
            <a href="{{ route('catalogo') }}" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>Catalogo de vehiculos
        </h1>

        <a class="btn btn-primary" data-toggle="modal" wire:click='limpiar()' data-target="#vehiculo">Nuevo
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">


                    @php
                        $heads = [
                            'ID',
                            'Placa',
                            'Marca',
                            'Año',
                            'Modelo',
                            'Serie',
                            'Descripcion',
                            'Estatus',
                            ['label' => 'Actiones', 'no-export' => true, 'width' => 20],
                        ];

                        $config = [
                            'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                        ];
                    @endphp

                    {{-- Minimal example / fill data using the component slot --}}
                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped
                        hoverable bordered compressed>
                        @foreach ($vehiculos as $vehiculo)
                            <tr>
                                <td>{{ $vehiculo->id }}</td>
                                <td>{{ $vehiculo->placas }}</td>
                                <td>{{ $vehiculo->modelo->marca->name }}</td>
                                <td>{{ $vehiculo->anio }}</td>
                                <td>{{ $vehiculo->modelo->name }}</td>
                                <td>{{ $vehiculo->serie }}</td>
                                <td>{{ $vehiculo->descripcion }}</td>
                                <td><i class="fa fa-circle" aria-hidden="true"
                                        style="color:{{ $vehiculo->status_ctg_vehiculos == 1 ? 'green' : 'red' }};"></i>
                                </td>
                                <td>

                                    <div class="btn-group">

                                        @if ($vehiculo->status_ctg_vehiculos == 1)
                                            <button class="btn text-success" title="Editar"
                                                wire:click="setVehiculo({{ $vehiculo }})">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>

                                            <button class="btn text-danger" title="Dar de baja"
                                                wire:click="$dispatch('confirm-baja',{{ $vehiculo }})">

                                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-primary" title="Reactivar"
                                                wire:click="$dispatch('confirm-reactivar',{{ $vehiculo }})">
                                                Reactivar
                                            </button>
                                        @endif

                                        <button class="btn text-danger" title="Eliminar"
                                            wire:click="$dispatch('confirm-delete',{{ $vehiculo }})">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>

                                    </div>

                                </td>
                            </tr>
                        @endforeach


                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>



    {{-- Themed --}}
    <x-adminlte-modal wire:ignore.self id="vehiculo" title="Agregar vehiculo" theme="info" icon="fas fa-bolt"
        size='lg' disable-animations>
        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    {{-- sucursal general --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">

                            <x-input-validado label="Placas:" placeholder="placas" wire-model="form.placas"
                                type="text" />
                        </div>
                        <div class="col-md-4 mb-3">


                            <x-select-validadolive label="Marca:" placeholder="Selecciona"
                                wire-model="form.ctg_vehiculo_marca_id" required>

                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                                @endforeach
                            </x-select-validadolive>
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Año:" placeholder="año" wire-model="form.anio" type="text" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-select-validado label="Modelo:" placeholder="" wire-model="form.ctg_vehiculo_modelo_id"
                                required>

                                <option value="0" selected>Selecciona</option>
                                @if($modelos)
                                @foreach ($modelos as $modelo)
                                    <option value="{{ $modelo->id }}">{{ $modelo->name }}</option>
                                @endforeach
                                @else 
                                <option value="0" disabled>Esperando que seleccione la marca</option>
                                @endif
                            </x-select-validado>
                        </div>


                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Serie:" placeholder="serie" wire-model="form.serie"
                                type="text" />
                        </div>


                        <div class="col-md-12 mb-3">
                            <x-input-validado label="Descripción:" placeholder="descripción"
                                wire-model="form.descripcion" type="text" />
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-info"
            wire:click="$dispatch('confirm',{{ $vehiculo_id != 0 ? 2 : 1 }})">Guardar</button>
    </x-adminlte-modal>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('confirm', (opcion) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: opcion == 1 ? "El modelo se guardara en la base de datos" :
                            "La modelo se actualizara",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(opcion == 1 ? 'save-vehiculo' : 'update-vehiculo');
                        }
                    })
                })
                @this.on('confirm-delete', (vehiculo) => {

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
                @this.on('confirm-baja', (vehiculo) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Se dara de baja y ya no podra ser usado por futuras rutas, pero seguira dentro de la base de datos con un estatus inactivo.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('baja-vehiculo', {
                                vehiculo: vehiculo
                            });
                        }
                    })
                })
                @this.on('confirm-reactivar', (vehiculo) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Esto puede traer problemas con el sistema",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('reactivar-vehiculo', {
                                vehiculo: vehiculo
                            });
                        }
                    })
                })
                Livewire.on('success', function(message) {
                    $('#vehiculo').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500

                    });

                    restar_table();
                });
                //inicializo de nuevo 
                Livewire.on('datatable', function() {
                    restar_table();
                });
                Livewire.on('edit-vehiculo', function() {
                    $('#vehiculo').modal('show');
                });


                Livewire.on('error', function(message) {

                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    restar_table();
                });
            });


            //inicializo de nuevo funcion
            function restar_table() {
                $('#table1').DataTable().destroy();

                $(() => {
                    $('#table1').DataTable(@json($config));
                })
            }
        </script>
    @endpush
</div>
