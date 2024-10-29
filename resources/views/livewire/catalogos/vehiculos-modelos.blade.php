<div wire:init='loadTable'>
    <div class="d-sm-flex align-items-center justify-content-between">


        <h1 class="ml-2">
            <a href="{{ route('catalogo') }}" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>Modelos de vehiculos
        </h1>

        <button type="button" class="btn btn-primary" wire:click='limpiar()' data-toggle="modal" data-target="#modelo">
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
                    @if (count($modelos))

                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Estatus</th>
                                    <th style="width: 120px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modelos as $modelo)
                                <tr>
                                    <td>
                                        {{ $modelo->id }}
                                    </td>
                                    <td>
                                        {{ $modelo->name }}
                                    </td>
                                    <td>
                                        {{$modelo->marca? $modelo->marca->name:''}}
                                    </td>
                                    <td>
    
                                        <i class="fa fa-circle" aria-hidden="true"
                                            style="color:{{ $modelo->status_ctg_vehiculos_modelos == 1 ? 'green' : 'red' }};"></i>
                                    </td>
                                    <td>
    
                                        <div class="btn-group">
    
                                            @if ($modelo->status_ctg_vehiculos_modelos == 1)
                                                <button class="btn text-success" title="Editar"
                                                    wire:click="setModelo({{ $modelo }})">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </button>
    
                                                <button class="btn text-danger" title="Dar de baja"
                                                    wire:click="$dispatch('confirm-baja',{{ $modelo }})">
    
                                                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-primary" title="Reactivar"
                                                    wire:click="$dispatch('confirm-reactivar',{{ $modelo }})">
                                                    Reactivar
                                                </button>
                                            @endif
    
                                            <button class="btn text-danger" title="Eliminar"
                                                wire:click="$dispatch('confirm-delete',{{ $modelo }})">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
    
                                        </div>
    
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>


                        @if ($modelos->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $modelos->links() }}
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



    <x-adminlte-modal wire:ignore.self id="modelo"
        title=" {{ $modelo_id != 0 ? 'Editar modelo' : 'Agregar modelo' }}" theme="info" icon="fas fa-bolt"
        size='md' disable-animations>
        <div class="col-md-12 card card-outline card-info">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <x-select-validado label="Marca:" placeholder="Selecciona"
                            wire-model="form.ctg_vehiculo_marca_id" required>

                            <option value="0" selected>Selecciona</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                            @endforeach
                        </x-select-validado>
                        <x-input-validado label="Nombre:" placeholder="nombre" wire-model="form.name" type="text" />
                    </div>

                </div>
            </div>


        </div>
        <button type="button" class="btn btn-info"
            wire:click="$dispatch('confirm',{{ $modelo_id != 0 ? 2 : 1 }})">Guardar</button>
    </x-adminlte-modal>


    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                console.log('Livewire ha cargado la página');

                // Aquí puedes poner cualquier código JavaScript que deseas ejecutar después de que Livewire haya actualizado la página
            });

            document.addEventListener('livewire:initialized', () => {
                console.log('Livewire ha cargado la página');
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
                            @this.dispatch(opcion == 1 ? 'save-modelo' : 'update-modelo');
                        }
                    })
                })
                @this.on('confirm-delete', (modelo) => {

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
                            @this.dispatch('delete-modelo', {
                                modelo: modelo
                            });
                        }
                    })
                })
                @this.on('confirm-baja', (modelo) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Se dara de baja y ya no podra ser usado por futuros vehiculos, pero seguira dentro de la base de datos con un estatus inactivo.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('baja-modelo', {
                                modelo: modelo
                            });
                        }
                    })
                })
                @this.on('confirm-reactivar', (modelo) => {

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
                                modelo: modelo
                            });
                        }
                    })
                })
                Livewire.on('success-modelo', function(message) {
                    $('#modelo').modal('hide');

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
                Livewire.on('edit-modelos', function() {
                    $('#modelo').modal('show');
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
            Livewire.hook('message.processed', (message, component) => {
        if (component.fingerprint.name === 'vehiculos-modelos') {
            $('#table1').DataTable().destroy();
            $('#table1').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        }
    });
        </script>
    @endpush
</div>
