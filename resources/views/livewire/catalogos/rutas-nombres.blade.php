<div wire:init='loadTable'>
    <div class="d-sm-flex align-items-center justify-content-between">


        <h1 class="ml-2">
            <a href="{{route('catalogo')}}" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>Nombres de las rutas
        </h1>

        <button type="button" class="btn btn-primary" wire:click='limpiar()' data-toggle="modal" data-target="#ruta">
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
                    @if (count($rutas))

                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de lA ruta</th>
                                  
                                    <th>Estatus</th>
                                    <th style="width: 120px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rutas as $ruta)
                                <tr>
                                    <td>
                                        {{ $ruta->id }}
                                    </td>
                                    <td>
                                        {{ $ruta->name }}
                                        
                                    </td>
                                    <td>
    
                                        <i class="fa fa-circle" aria-hidden="true"
                                            style="color:{{ $ruta->status_ctg_ruta == 1 ? 'green' : 'red' }};"></i>
                                    </td>
                                    <td>
    
                                        <div class="btn-group">
    
                                            @if ($ruta->status_ctg_ruta == 1)
                                                <button class="btn text-success" title="Editar"
                                                    wire:click="setRuta({{ $ruta }})">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </button>
    
                                                <button class="btn text-danger" title="Dar de baja"
                                                    wire:click="$dispatch('confirm-baja',{{ $ruta }})">
    
                                                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-primary" title="Reactivar"
                                                    wire:click="$dispatch('confirm-reactivar',{{ $ruta }})">
                                                    Reactivar
                                                </button>
                                            @endif
    
                                            <button class="btn text-danger" title="Eliminar"
                                                wire:click="$dispatch('confirm-delete',{{ $ruta }})">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
    
                                        </div>
    
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>


                        @if ($rutas->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $rutas->links() }}
                            </div>
                        @endif
                    @else
                        @if ($readyToLoad)
                            <h3 class="col-md-12 text-center">No hay datos disponibles</h3>
                        @else
                            <!-- Muestra un spinner mientras los datos se cargan -->
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                    {{-- <span class="visually-hidden">Loading...</span> --}}
                                </div>
                            </div>
                        @endif
                    @endif


                </div>
            </div>
           
        </div>
    </div>



    <x-adminlte-modal wire:ignore.self id="ruta" title=" {{ $ruta_id != 0 ? 'Editar nombre de la ruta' : 'Agregar nombre de la ruta' }}"
        theme="info" icon="fas fa-bolt" size='md' disable-animations>
        <div class="col-md-12 card card-outline card-info">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">

                        <x-input-validado label="Nombre:" placeholder="nombre" wire-model="form.name" type="text" />
                    </div>

                </div>
            </div>


        </div>
        <button type="button" class="btn btn-info"
            wire:click="$dispatch('confirm',{{ $ruta_id != 0 ? 2 : 1 }})">Guardar</button>
    </x-adminlte-modal>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (opcion) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: opcion == 1 ? "El nombre de la ruta se guardara en la base de datos" :
                            "El nombre de la ruta se actualizara",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(opcion == 1 ? 'save-ruta' : 'update-ruta');
                        }
                    })
                })
                @this.on('confirm-delete', (ruta) => {

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
                            @this.dispatch('delete-ruta', {
                                ruta: ruta
                            });
                        }
                    })
                })
                @this.on('confirm-baja', (ruta) => {

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
                            @this.dispatch('baja-ruta', {
                                ruta: ruta
                            });
                        }
                    })
                })
                @this.on('confirm-reactivar', (ruta) => {

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
                                ruta: ruta
                            });
                        }
                    })
                })
                Livewire.on('success-ruta', function(message) {
                    $('#ruta').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500

                    });

                });
                //inicializo de nuevo 
              
                Livewire.on('edit-rutas', function() {
                    $('#ruta').modal('show');
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
