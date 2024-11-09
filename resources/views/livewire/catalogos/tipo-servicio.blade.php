<div wire:init='loadTable'>
    <div class="d-sm-flex align-items-center justify-content-between">


        <h1 class="ml-2">
            <a href="{{ route('catalogo') }}" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>Catalogo de tipos de servicio
        </h1>

        <button type="button" class="btn btn-primary" wire:click='limpiar()' data-toggle="modal" data-target="#data">
            Nuevo
        </button>

    </div>
    <div class="row">

        <div class="col-md-12">
           
            <div class="card card-outline card-info">


                <div class="card-body" wire:ignore.self>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">

                                <i class="fa fa-search" aria-hidden="true"></i>

                            </span>
                        </div>
                        <input type="text" class="form-control"
                            aria-label="Dollar amount (with dot and two decimal places)" wire:model.live='search'>
                    </div>
                    @if (count($datas))

                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>

                                    <th>Estatus</th>
                                    <th style="width: 120px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                            <tr>
                                <td>
                                    {{ $data->id }}
                                </td>
                                <td>
                                    {{ $data->name }}

                                </td>
                                <td>

                                    <i class="fa fa-circle" aria-hidden="true"
                                        style="color:{{ $data->status_ctg_tipo_servicio == 1 ? 'green' : 'red' }};"></i>
                                </td>
                                <td>

                                    <div class="btn-group">

                                        @if ($data->status_ctg_tipo_servicio == 1)
                                            <button class="btn text-success" title="Editar"
                                                wire:click="setData({{ $data }})">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </button>

                                            <button class="btn text-danger" title="Dar de baja"
                                                wire:click="$dispatch('confirm-baja',{{ $data }})">

                                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-primary" title="Reactivar"
                                                wire:click="$dispatch('confirm-reactivar',{{ $data }})">
                                                Reactivar
                                            </button>
                                        @endif

                                        <button class="btn text-danger" title="Eliminar"
                                            wire:click="$dispatch('confirm-delete',{{ $data }})">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>

                                    </div>

                                </td>
                            </tr>
                        @endforeach

                            </tbody>
                        </table>


                        @if ($datas->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $datas->links() }}
                            </div>
                        @endif
                    @else
                        @if ($readyToLoad)
                            <h3 class="col-md-12 text-center">No hay datos disponibles</h3>
                        @else
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                </div>
                            </div>
                        @endif
                    @endif


                </div>
            </div>
        </div>

    </div>



    <x-adminlte-modal wire:ignore.self id="data"
        title=" {{ $data_id != 0 ? 'Editar nombre' : 'Agregar registro' }}"
        theme="info" icon="fas fa-bolt" size='md' disable-animations>
        <div class="col-md-12 card card-outline card-info">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">

                        <x-input-validado label="Nombre:" placeholder="nombre" wire-model="name" type="text" />
                    </div>

                </div>
            </div>


        </div>
        <button type="button" class="btn btn-info"
            wire:click="$dispatch('confirm',{{ $data_id != 0 ? 2 : 1 }})">Guardar</button>
    </x-adminlte-modal>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (opcion) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: opcion == 1 ?
                            "El registro se guardara en la base de datos" :
                            "El registro se actualizara",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(opcion == 1 ? 'save-data' : 'update-data');
                        }
                    })
                })
                @this.on('confirm-delete', (data) => {

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
                            @this.dispatch('delete-data', {
                                data: data
                            });
                        }
                    })
                })
                @this.on('confirm-baja', (data) => {

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
                            @this.dispatch('baja-data', {
                                data: data
                            });
                        }
                    })
                })
                @this.on('confirm-reactivar', (data) => {

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
                            @this.dispatch('delete-reactivar', {
                                data: data
                            });
                        }
                    })
                })
                Livewire.on('success-data', function(message) {
                    $('#data').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500

                    });

                });
               
                Livewire.on('edit-data', function() {
                    $('#data').modal('show');
                });


                Livewire.on('error', function(message) {

                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                });
            });


            
        </script>
    @endpush
</div>
