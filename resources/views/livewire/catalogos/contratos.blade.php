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
                <td>
                    <!-- Botón de editar -->
                    <button  wire:click="abrirModalEditar({{ $datos->id }})" data-toggle="modal" data-target="#ModalEditar" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                
                    <!-- Botones condicionales de eliminar/reactivar -->
                    @if ($datos->status_contrato == 1)
                        <!-- Botón de eliminar -->
                        <button   class="btn btn-danger btn-sm" wire:click="eliminarContrato({{ $datos->id }})"><i class="fas fa-trash"></i></button>
                    @else
                        <!-- Botón de reactivar -->
                        <button   class="btn btn-success btn-sm" wire:click="reactivarContrato({{ $datos->id }})"><i class="fas fa-check"></i></button>
                    @endif
                </td>                
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
    theme="info" icon="fas fa-bolt" size='xl' disable-animations>
    <div class="col-md-12 card card-outline card-info">

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        <h6><span class="text-danger">*</span> El documento debe contar con los siguientes elementos para ser remplazados por los datos del cliente:</h6>
                        <h6>Ejemplo</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><label for="razon_social">Razón Social:</label> INGRESE LA RAZÓN SOCIAL</li>
                                    <li><label for="rfc">RFC:</label> INGRESE EL RFC</li>
                                    <li><label for="tipo_cliente">Tipo Cliente:</label> INGRESE EL TIPO DE CLIENTE</li>
                                    <li><label for="nombre_contacto">Nombre del contacto:</label> INGRESE EL NOMBRE DEL CONTACTO</li>
                                    <li><label for="puesto">Puesto:</label> INGRESE EL PUESTO</li>
                                    <li><label for="apoderado">Datos Fiscales - Apoderado/a:</label> INGRESE APODERADO/A</li>
                                    <li><label for="escritura">Datos Fiscales - Escritura:</label> INGRESE ESCRITURA</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><label for="licenciado">Datos Fiscales - Licenciado:</label> INGRESE LICENCIADO</li>
                                    <li><label for="folio_mercantil">Datos Fiscales - Folio Mercantil:</label> INGRESE FOLIO MERCANTIL</li>
                                    <li><label for="fecha_registro">Datos Fiscales - Fecha de Registro:</label> DD/MM/AAAA</li>
                                    <li><label for="lugar_registro">Datos Fiscales - Lugar de registro:</label> INGRESE LUGAR DE REGISTRO</li>
                                    <li><label for="notario">Poder notarial - Notario:</label> INGRESE NOTARIO</li>
                                    <li><label for="licenciado_notaria">Poder notarial - No.Licenciado de la notaria:</label> INGRESE NO.LICENCIADO DE LA NOTARIA</li>
                                    <li><label for="fecha_poder_notarial">Poder notarial - Fecha de poder notarial:</label> DD/MM/AAAA</li>
                                    <li><label for="ciudad_notaria">Poder notarial - Ciudad de la notaria:</label> INGRESE CIUDAD DE LA NOTARIA</li>
                                </ul>
                            </div>
                        </div>                       
                          
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
<!--modal editar-->
<x-adminlte-modal wire:ignore.self id="ModalEditar" title=""
theme="info" icon="fas fa-bolt" size='xl' disable-animations>
<div class="col-md-12 card card-outline card-info">

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <h6><span class="text-danger">*</span> El documento debe contar con los siguientes elementos para ser remplazados por los datos del cliente:</h6>
                    <h6>Ejemplo</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li><label for="razon_social">Razón Social:</label> INGRESE LA RAZÓN SOCIAL</li>
                                <li><label for="rfc">RFC:</label> INGRESE EL RFC</li>
                                <li><label for="tipo_cliente">Tipo Cliente:</label> INGRESE EL TIPO DE CLIENTE</li>
                                <li><label for="nombre_contacto">Nombre del contacto:</label> INGRESE EL NOMBRE DEL CONTACTO</li>
                                <li><label for="puesto">Puesto:</label> INGRESE EL PUESTO</li>
                                <li><label for="apoderado">Datos Fiscales - Apoderado/a:</label> INGRESE APODERADO/A</li>
                                <li><label for="escritura">Datos Fiscales - Escritura:</label> INGRESE ESCRITURA</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li><label for="licenciado">Datos Fiscales - Licenciado:</label> INGRESE LICENCIADO</li>
                                <li><label for="folio_mercantil">Datos Fiscales - Folio Mercantil:</label> INGRESE FOLIO MERCANTIL</li>
                                <li><label for="fecha_registro">Datos Fiscales - Fecha de Registro:</label> DD/MM/AAAA</li>
                                <li><label for="lugar_registro">Datos Fiscales - Lugar de registro:</label> INGRESE LUGAR DE REGISTRO</li>
                                <li><label for="notario">Poder notarial - Notario:</label> INGRESE NOTARIO</li>
                                <li><label for="licenciado_notaria">Poder notarial - No.Licenciado de la notaria:</label> INGRESE NO.LICENCIADO DE LA NOTARIA</li>
                                <li><label for="fecha_poder_notarial">Poder notarial - Fecha de poder notarial:</label> DD/MM/AAAA</li>
                                <li><label for="ciudad_notaria">Poder notarial - Ciudad de la notaria:</label> INGRESE CIUDAD DE LA NOTARIA</li>
                            </ul>
                        </div>
                    </div>                       
                      
                  </div>
            </div>
            <div class="col-md-12 mb-3" hidden>
                <x-input-validado label="idEditar:" placeholder="idEditar" wire-model="idEditar" type="text"/>
            </div>
            <div class="col-md-12 mb-3">
                <x-input-validado label="Nombre Contrato:" placeholder="nombre contrato" wire-model="nombredocumentoeditar" type="text"  />
            </div>
            <div class="col-md-12 mt-3 mb-2">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile"
                            wire:model.live="docwordeditar" wire:key='{{$imageKey2}}'>
                        <label class="custom-file-label" for="customFile">{{ $fileName ? $fileName
                            :'Seleccione documento'}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3 mb-2 text-center">
                <!-- Agregado text-center -->
                <div wire:loading wire:target="docwordeditar">
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 50px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando archivo...</span>
                        </div>
                        <span class="ml-2">Cargando archivo...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-info btn-block" wire:loading.remove wire:target="docwordeditar" wire:click="$dispatch('confirm',{{2}})">Guardar</button>
<button type="button" class="btn btn-info btn-block" disabled wire:loading wire:target="docwordeditar">Guardar</button>
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
                            @this.dispatch(opcion == 1 ? 'save-contrato' : 'update-contrato');
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
                            position: 'center',
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
