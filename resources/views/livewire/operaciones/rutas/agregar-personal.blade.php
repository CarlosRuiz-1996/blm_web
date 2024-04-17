<div wire:init='loadPersonal'>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Personal de seguridad</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#personal" wire:click='getPersonal()'>
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



                            <input type="text" class="form-control w-full" placeholder="Buscar guardia"
                                wire:model.live='form.searchPersonal'>

                            {{-- {{ $form->searchVehiculo }} --}}

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_empleados && count($ruta_empleados))
                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Puesto</th>
                                        <th>Sexo</th>
                                        <th>Teléfono</th>
                                        <th>Armado</th>
                                        <th>Eliminar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($ruta_empleados as $empleado)
                                            <tr>

                                                <td>{{ $empleado->empleado->id }}</td>
                                                <td>{{ $empleado->empleado->user->name.' '.$empleado->empleado->user->paterno.' '.$empleado->empleado->user->materno }}</td>
                                                <td>Guardia</td>
                                                <td>{{ $empleado->empleado->sexo }}</td>
                                                <td>{{ $empleado->empleado->phone }}</td>
                                                <td>NO</td>
                                                <td>
                                                    <button class="btn text-danger" title="Eliminar"
                                                        wire:click="$dispatch('confirm-delete-personal',{{ $empleado }})">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
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
    <x-adminlte-modal wire:ignore.self id="personal" title="Agregar personal de seguridad a la ruta" theme="info"
        icon="fas fa-car" size='lg' disable-animations>


        <div class="py-6 px-4 bg-gray-200 d-flex mb-3">

           

            <input type="text" class="form-control w-full ml-4" placeholder="Buscar guardia"
                wire:model.live='form.searchPersonalModal'>

        </div>

        @if ($empleados && count($empleados))
        <table class="table table-hover table-striped">
            <thead class="table-info">
                <th></th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Sexo</th>
                <th>Teléfono</th>
                <th>Armado</th>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="selectPersonal.{{ $empleado->id }}" />
                        </td>
                        <td>{{ $empleado->id }}</td>
                        <td>{{ $empleado->user->name.' '.$empleado->user->paterno.' '.$empleado->user->materno }}</td>
                        <td>Guardia</td>
                        <td>{{ $empleado->sexo }}</td>
                        <td>{{ $empleado->phone }}</td>
                        <td>NO</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($empleados->hasPages())
            <div class="d-flex justify-content-center">
                {{ $empleados->links() }}
            </div>
        @endif



        <div class="col-md-12 mb-3">
            <button type="submit" class="btn btn-info btn-block" wire:click="$dispatch('confirm-personal')">Agregar
                personal a al ruta</button>
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

                @this.on('confirm-personal', () => {

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
                            @this.dispatch('add-personal-ruta');
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

                Livewire.on('success-personal', function(message) {
                    $('#personal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                });


                Livewire.on('error-personal', function(message) {
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
                $('#personal').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-personal');
                });
            });
        </script>
    @endpush
</div>
