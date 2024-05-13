<div wire:init='loadPersonalCajero'>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Personal Cajero</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#personalCajero" wire:click='getPersonalCajero()'>
            Agregar Elementos
            <i class="fa fa-plus" aria-hidden="true"></i>

        </button>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex col-md-12 mb-3">



                            <input type="text" class="form-control w-full" placeholder="Buscar cajero"
                                wire:model.live='form.searchPersonalCajero'>

                            {{-- {{ $form->searchVehiculo }} --}}

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_empleadosCajeros && count($ruta_empleadosCajeros))
                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Puesto</th>
                                        <th>Sexo</th>
                                        <th>Teléfono</th>
                                        <th>Eliminar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($ruta_empleadosCajeros as $empleadoCajeroRuta)
                                            <tr>

                                                <td>{{ $empleadoCajeroRuta->empleado->id }}</td>
                                                <td>{{ $empleadoCajeroRuta->empleado->user->name.' '.$empleadoCajeroRuta->empleado->user->paterno.' '.$empleadoCajeroRuta->empleado->user->materno }}</td>
                                                <td>Guardia</td>
                                                <td>{{ $empleadoCajeroRuta->empleado->sexo }}</td>
                                                <td>{{ $empleadoCajeroRuta->empleado->phone }}</td>
                                                <td>
                                                    <button class="btn text-danger" title="Eliminar"
                                                        wire:click="$dispatch('confirm-delete-personal',{{ $empleadoCajeroRuta }})">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if ($readyToLoadCajero)
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
    <x-adminlte-modal wire:ignore.self id="personalCajero" title="Agregar cajero a la ruta" theme="info"
        icon="fas fa-car" size='lg' disable-animations>


        <div class="py-6 px-4 bg-gray-200 d-flex mb-3">

           

            <input type="text" class="form-control w-full ml-4" placeholder="Buscar cajero"
                wire:model.live='form.searchPersonalCajeroModal'>

        </div>

        @if ($empleadosCajeros && count($empleadosCajeros))
        <table class="table table-hover table-striped">
            <thead class="table-info">
                <th></th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Sexo</th>
                <th>Teléfono</th>
            </thead>
            <tbody>
                @foreach ($empleadosCajeros as $empleadocajero)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="selectPersonalCajero.{{ $empleadocajero->id }}" />
                        </td>
                        <td>{{ $empleadocajero->id }}</td>
                        <td>{{ $empleadocajero->user->name.' '.$empleadocajero->user->paterno.' '.$empleadocajero->user->materno }}</td>
                        <td>Cajero</td>
                        <td>{{ $empleadocajero->sexo }}</td>
                        <td>{{ $empleadocajero->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($empleadosCajeros->hasPages())
            <div class="d-flex justify-content-center">
                {{ $empleadosCajeros->links() }}
            </div>
        @endif



        <div class="col-md-12 mb-3">
            <button type="submit" class="btn btn-info btn-block" wire:click="$dispatch('confirm-personal-cajero')">Agregar
                personal a ruta</button>
        </div>
    @else
        @if ($readyToLoadCajero)
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

                @this.on('confirm-personal-cajero', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Los empleados se agregaran a la ruta",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('add-personal-ruta-cajero');
                        }
                    })
                })
                @this.on('confirm-delete-personal', (personal) => {

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
                            @this.dispatch('delete-personal', {
                                personal: personal
                            });
                        }
                    })
                })

                Livewire.on('success-personal-cajero', function(message) {
                    $('#personalCajero').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-personal-cajero', function(message) {
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
                $('#personalCajero').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-personalCajero');
                });
            });
        </script>
    @endpush
</div>
