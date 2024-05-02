<div class="">
    <div class="d-sm-flex align-items-center justify-content-between">


        <h1 class="ml-2">
            <a href="{{route('catalogo')}}" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>Catalogo Documentos
        </h1>

        <button type="button" class="btn btn-primary" wire:click='limpiar()' data-toggle="modal" data-target="#dias">
            Nuevo
        </button>

    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">


                <div class="card-body">
<div class="table-responsive">
    <table class="table">
        <thead class="table-info">
            <tr>
                <td>id</td>
                <td>nombre contrato</td>
                <td>Documento</td>
                <td>Acción</td>
            </tr>
        </thead>
        <tbody>
            
            @foreach($contratos as $datos)
            <tr>
                <td>{{$datos->id}}</td>
                <td>{{$datos->nombre}}</td>
                <td>{{$datos->path}}</td>
                <td>{{$datos->status_contrato}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
                </div>
            </div>
        </div>
    </div>
    <x-adminlte-modal wire:ignore.self id="dias" title=""
    theme="info" icon="fas fa-bolt" size='md' disable-animations>
    <div class="col-md-12 card card-outline card-info">

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        This is a warning alert—check it out!
                      </div>
                </div>
                <div class="col-md-12 mb-3">
                    <x-input-validado label="Nombre Contrato:" placeholder="nombre contrato" wire-model="nombredocumento" type="text"  />
                </div>
                <div class="col-md-12 mb-3">
                    <input type="file" class="form-control" wire:model="docword" wire:key='{{$imageKey}}'>
 
                    <div wire:loading wire:target="docword">
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 50px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


    </div>
    <button type="button" class="btn btn-info btn-block" wire:loading.remove wire:target="docword" wire:click="$dispatch('confirm',{{1}})">Guardar</button>
    <button type="button" class="btn btn-info btn-block" disabled wire:loading wire:target="docword">Guardar</button>
</x-adminlte-modal>





    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (opcion) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: opcion == 1 ? "Los datos del contrato se guardaran en la base de datos" :
                            "Los datos del contrato se actualizaran",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(opcion == 1 ? 'save-dia' : 'update-dia');
                        }
                    })
                })
                @this.on('confirm-delete', (dia) => {

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
                            @this.dispatch('delete-dia', {
                                dia: dia
                            });
                        }
                    })
                })
                @this.on('confirm-baja', (dia) => {

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
                            @this.dispatch('baja-dia', {
                                dia: dia
                            });
                        }
                    })
                })
                @this.on('confirm-reactivar', (dia) => {

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
                                dia: dia
                            });
                        }
                    })
                })
                Livewire.on('success-dia', function(message) {
                    $('#dias').modal('hide');

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
                Livewire.on('edit-dias', function() {
                    $('#dias').modal('show');
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

                Livewire.on('agregarArchivocre', function(params) {
                        const nombreArchivo = params[0].nombreArchivo;
                        const tipomensaje = params[1].tipomensaje;
                        Swal.fire({
                            position: 'top-end',
                            icon: tipomensaje,
                            title: nombreArchivo,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
            });
        </script>
    @endpush
</div>
