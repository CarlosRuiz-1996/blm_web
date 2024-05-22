<div wire:init='loadVehiculos'>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Vehículos</h1>
        @if ($this->form->ruta->ctg_rutas_estado_id == 1)
            <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
                data-target="#vehiculos" wire:click='getVehiculos()'>
                Agregar Vehículos
                <i class="fa fa-plus" aria-hidden="true"></i>

            </button>
        @endif
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex col-md-12 mb-3">



                            <input type="text" class="form-control w-full" placeholder="Busca un vehículo"
                                wire:model.live='form.searchVehiculo'>

                            {{-- {{ $form->searchVehiculo }} --}}

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_vehiculos && count($ruta_vehiculos))
                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>ID</th>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Año</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>
                                        <th>Descripcion</th>
                                        @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                                            <th>Eliminar</th>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach ($ruta_vehiculos as $vehiculo)
                                            <tr>

                                                <td>{{ $vehiculo->vehiculo->id }}</td>
                                                <td>{{ $vehiculo->vehiculo->placas }}</td>
                                                <td>{{ $vehiculo->vehiculo->modelo->marca->name }}</td>
                                                <td>{{ $vehiculo->vehiculo->anio }}</td>
                                                <td>{{ $vehiculo->vehiculo->modelo->name }}</td>
                                                <td>{{ $vehiculo->vehiculo->serie }}</td>
                                                <td>{{ $vehiculo->vehiculo->descripcion }}</td>
                                                @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                                                    <td>
                                                        <button class="btn text-danger" title="Eliminar"
                                                            wire:click="$dispatch('confirm-delete-vehiculo',{{ $vehiculo }})">
                                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
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
    {{-- Modal vehiculos --}}
    <x-adminlte-modal wire:ignore.self id="vehiculos" title="Agregar vehículos a la ruta" theme="info"
        icon="fas fa-car" size='lg' disable-animations>


        <div class="py-6 px-4 bg-gray-200 d-flex mb-3">

            <div class="d-flex align-items-center mr-3">
                <select class="form-control p-2" wire:model.live='list'>
                    @foreach ($entrada as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

            <input type="text" class="form-control w-full ml-4" placeholder="Busca un vehículo"
                wire:model.live='form.searchVehiculoModal'>

        </div>



        @if ($vehiculos && count($vehiculos))
            <table class="table table-hover table-striped">
                <thead class="table-info">
                    <th></th>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Año</th>

                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Descripcion</th>

                </thead>
                <tbody>
                    @foreach ($vehiculos as $vehiculo)
                        <tr>
                            <td>
                                <input type="checkbox" wire:model.live="selectVehiculos.{{ $vehiculo->id }}" />
                            </td>
                            <td>{{ $vehiculo->id }}</td>
                            <td>{{ $vehiculo->placas }}</td>
                            <td>{{ $vehiculo->modelo->marca->name }}</td>
                            <td>{{ $vehiculo->anio }}</td>
                            <td>{{ $vehiculo->modelo->name }}</td>
                            <td>{{ $vehiculo->serie }}</td>
                            <td>{{ $vehiculo->descripcion }}</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($vehiculos->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $vehiculos->links() }}
                </div>
            @endif



            <div class="col-md-12 mb-3">
                <button type="submit" class="btn btn-info btn-block" wire:click="$dispatch('confirm-vehiculo')">Agregar
                    vehiculos</button>
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

                Livewire.on('success-vehiculo', function(message) {
                    $('#vehiculos').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-vehiculo', function(message) {
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
