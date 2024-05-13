<div wire:init='loadPersonalOperador'>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Personal Operador</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#personalOperador" wire:click='getPersonalOperador()'>
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



                            <input type="text" class="form-control w-full" placeholder="Buscar Operador"
                                wire:model.live='form.searchPersonalOperador'>

                            {{-- {{ $form->searchVehiculo }} --}}

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_empleadosOperadores && count($ruta_empleadosOperadores))
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
                                        @foreach ($ruta_empleadosOperadores as $empleadoOperadorRuta)
                                            <tr>

                                                <td>{{ $empleadoOperadorRuta->empleado->id }}</td>
                                                <td>{{ $empleadoOperadorRuta->empleado->user->name.' '.$empleadoOperadorRuta->empleado->user->paterno.' '.$empleadoOperadorRuta->empleado->user->materno }}</td>
                                                <td>Guardia</td>
                                                <td>{{ $empleadoOperadorRuta->empleado->sexo }}</td>
                                                <td>{{ $empleadoOperadorRuta->empleado->phone }}</td>
                                                <td>
                                                    <button class="btn text-danger" title="Eliminar"
                                                        wire:click="$dispatch('confirm-delete-personal',{{ $empleadoOperadorRuta }})">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if ($readyToLoadOperador)
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
    <x-adminlte-modal wire:ignore.self id="personalOperador" title="Agregar Operador a la ruta" theme="info"
        icon="fas fa-car" size='lg' disable-animations>


        <div class="py-6 px-4 bg-gray-200 d-flex mb-3">

           

            <input type="text" class="form-control w-full ml-4" placeholder="Buscar Operador"
                wire:model.live='form.searchPersonalOperadorModal'>

        </div>

        @if ($empleadosOperadores && count($empleadosOperadores))
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
                @foreach ($empleadosOperadores as $empleadoOperador)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="selectPersonalOperador.{{ $empleadoOperador->id }}" />
                        </td>
                        <td>{{ $empleadoOperador->id }}</td>
                        <td>{{ $empleadoOperador->user->name.' '.$empleadoOperador->user->paterno.' '.$empleadoOperador->user->materno }}</td>
                        <td>Operador</td>
                        <td>{{ $empleadoOperador->sexo }}</td>
                        <td>{{ $empleadoOperador->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($empleadosOperadores->hasPages())
            <div class="d-flex justify-content-center">
                {{ $empleadosOperadores->links() }}
            </div>
        @endif



        <div class="col-md-12 mb-3">
            <button type="submit" class="btn btn-info btn-block" wire:click="$dispatch('confirm-personal-Operador')">Agregar
                personal a ruta</button>
        </div>
    @else
        @if ($readyToLoadOperador)
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

                @this.on('confirm-personal-Operador', () => {

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
                            @this.dispatch('add-personal-ruta-Operador');
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

                Livewire.on('success-personal-Operador', function(message) {
                    $('#personalOperador').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-personal-Operador', function(message) {
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
                $('#personalOperador').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-personalOperador');
                });
            });
        </script>
    @endpush
</div>
