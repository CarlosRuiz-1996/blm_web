<div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Generar Contratos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-10 mb-3">
                                <x-input-validado :readonly="false" label="Cliente:"
                                    placeholder="Ingrese el cliente a buscar" wire-model="clientenombre"
                                    wire-attribute="clientenombre" type="text" />
                            </div>
                            <div class="col-md-2 mb-3 mt-3">
                                <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal"
                                    wire:click='BuscarCliente' data-target="#exampleModal">Buscar</button>
                            </div>

                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Cliente</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-select-validado label="Tipo de contrato:" placeholder="Seleccione"
                                    wire-model="ctg_tipocontrato" required>
                                    @foreach ($contratos as $ctgcontratos)
                                    <option value="{{$ctgcontratos->id}}">{{$ctgcontratos->nombre}}</option>
                                    @endforeach

                                </x-select-validado>
                            </div>

                            <div class="col-md-4 mb-3" hidden>
                                <x-input-validado :readonly="true" label="id:" placeholder="Ingrese la id"
                                    wire-model="id" wire-attribute="id" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Razón Social:"
                                    placeholder="Ingrese la Razón Social" wire-model="razonSocial"
                                    wire-attribute="razonSocial" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="RFC" placeholder="Ingrese el rfc"
                                    wire-model="rfc" wire-attribute="rfc" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Tipo Cliente"
                                    placeholder="Ingrese el tipo de cliente" wire-model="tipocliente"
                                    wire-attribute="tipocliente" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Puesto:" placeholder="Ingrese el Puesto"
                                    wire-model="puesto" wire-attribute="puesto" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Datos Fiscales</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Apoderado/a:"
                                    placeholder="Ingrese Apoderado/a" wire-model="apoderado" wire-attribute="apoderado"
                                    type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Escritura:" placeholder="Ingrese Escritura"
                                    wire-model="escritura" wire-attribute="escritura" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Licenciado:" placeholder="Ingrese Licenciado"
                                    wire-model="licenciado" wire-attribute="licenciado" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Folio Mercantil:"
                                    placeholder="Ingrese Folio Mercantil" wire-model="foliomercantil"
                                    wire-attribute="foliomercantil" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Fecha de Registro:"
                                    placeholder="Ingrese fecha de Registro" wire-model="fecharegistro"
                                    wire-attribute="fecharegistro" type="date" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Lugar de registro:"
                                    placeholder="Ingrese Lugar de registro" wire-model="lugarregistro"
                                    wire-attribute="lugarregistro" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Poder notarial</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-8 mb-3">
                                <x-input-validado :readonly="false" label="Notario:" placeholder="Ingrese Notario"
                                    wire-model="notario" wire-attribute="notario" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group mt-5">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model.live='datosextra' id="datosextraSwitch" name="datosextra">
                                            <label class="custom-control-label" for="datosextraSwitch">Datos
                                                extras</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" {{ $camposextra ? '' : 'hidden' }}>
                                <x-input-validado :readonly="false" label="No.Licenciado de la notaria:"
                                    placeholder="Ingrese No.Licenciado de la notaria" wire-model="numnotario"
                                    wire-attribute="numnotario" type="text" />
                            </div>
                            <div class="col-md-4 mb-3" {{ $camposextra ? '' : 'hidden' }}>
                                <x-input-validado :readonly="false" label="Fecha de poder notarial:"
                                    placeholder="Ingrese Fecha de poder notarial" wire-model="fechapodernotarial"
                                    wire-attribute="fechapodernotarial" type="date" />
                            </div>
                            <div class="col-md-4 mb-3" {{ $camposextra ? '' : 'hidden' }}>
                                <x-input-validado :readonly="false" label="Ciudad de la notaria:"
                                    placeholder="Ingrese Ciudad de la notaria" wire-model="ciudadnotaria"
                                    wire-attribute="ciudadnotaria" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-select-validado label="Cotización:" placeholder="Seleccione"
                                    wire-model="seleccioncotiza" required>
                                    @foreach ($cotizaciones as $cotizacion)
                                    <option value="{{$cotizacion->id}}">Cotización con No.{{$cotizacion->id}}/ con fecha
                                        :{{ $cotizacion->created_at->format('d/m/Y') }}</option>
                                    @endforeach

                                </x-select-validado>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Documentos</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre Contrato</th>
                                                <th>Cotizacíon</th>
                                                <th>Documento</th>
                                                <th>Estatus</th>
                                                <th>Fecha Creación</th>
                                                <th>Fecha Actualización</th>
                                                <th>Acción</th>
                                                <!-- Agrega más <th> según sea necesario para tus columnas -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contratosList as $contrato)
                                            <tr>
                                                <td>{{ $contrato->ctg_contratos->nombre}}</td>
                                                <td>{{ $contrato->cotizacion_id}}</td>

                                                <td>
                                                    @if($contrato->status_editado == 1)
                                                    <!-- Si es vigente, muestra un ícono para descargar el archivo -->
                                                    <button wire:click="descargarArchivo({{ $contrato->id }})"
                                                        style="background: none; border: none; padding: 0;">
                                                        <i class="fas fa-file-word" style="color: #2B579A;"></i>
                                                    </button>
                                                    @else
                                                    <!-- Si no es vigente, ejecuta un método del controlador para generarlo -->
                                                    <button wire:click="generarArchivo({{ $contrato->id }})"
                                                        style="background: none; border: none; padding: 0;">
                                                        <i class="fas fa-file-word" style="color: #2B579A;"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $contrato->status_contrato == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $contrato->status_contrato == 1 ? 'Vigente' : 'Sin vigencia'
                                                        }}
                                                    </span>
                                                </td>
                                                <td>{{ $contrato->created_at}}</td>
                                                <td>{{ $contrato->updated_at}}</td>
                                                <td>
                                                    <!-- Botón de Editar -->
                                                    <button wire:click="editarContrato({{ $contrato->id }})"
                                                        class="btn btn-primary btn-sm" data-toggle="modal"
                                                        data-target="#ModalEditarContrato">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <!-- Botón de Eliminar -->
                                                    @if($contrato->status_contrato == 1)
                                                    <button wire:click="CancelarContrato({{ $contrato->id }})"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @else
                                                    <button wire:click="ReactivarContrato({{ $contrato->id }})"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    @endif
                                                    <!-- Botón de Resubir -->
                                                    <button wire:click="resubirContrato({{ $contrato->id }})"
                                                        class="btn btn-success btn-sm" data-toggle="modal"
                                                        data-target="#ModalSubirNuevoDoc">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button class="btn btn-info btn-block" wire:click='generarpdf'>Generar Contrato</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Datos de Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <h5 class="modal-title" id="tema">Seleccione cliente</h5>
                        <div class="row">
                            <div class="col-md-12 text-center justify-content-center">
                        <div wire:loading wire:target="BuscarCliente">
                            <div class="d-flex justify-content-center align-items-center" style="min-height: 100px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Buscando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                        <!-- Mostrar el mensaje de "Sin resultados" si no se encuentran coincidencias -->
                        @if($buscarRealizado == true)
                        @if(empty($data) && $buscarRealizado)
                            <p class="text-center">Sin resultados</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-info">
                                        <tr>
                                            <th>RFC</th>
                                            <th>Razón Social</th>
                                            <th>Contacto</th> <!-- Agrega más columnas según tus necesidades -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $cliente)
                                            <tr wire:click="mostrarCliente({{ $cliente->id }})"
                                                style="cursor: pointer;" onmouseover="this.style.backgroundColor='#f0f0f0';" onmouseout="this.style.backgroundColor='';"
                                                >
                                                <td>{{ $cliente->rfc_cliente }}</td>
                                                <td>{{ $cliente->razon_social }}</td>
                                                <td>{{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                
                            <!-- Agregar controles de paginación si hay más de una página -->
                            <div class="mt-4">
                                {{ $data->links() }}
                            </div>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal editar contrato-->
    <!-- Modal -->
    <div class="modal fade" id="ModalEditarContrato" tabindex="-1" aria-labelledby="ModalEditarContratoLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalEditarContratoLabel">Editar Datos de Contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3" hidden>
                            <x-input-validado :readonly="false" label="id" placeholder="id" wire-model="idEditar"
                                wire-attribute="idEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Apoderado/a:" placeholder="Ingrese Apoderado/a"
                                wire-model="apoderadoEditar" wire-attribute="apoderadoEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Escritura:" placeholder="Ingrese Escritura"
                                wire-model="escrituraEditar" wire-attribute="escrituraEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Licenciado:" placeholder="Ingrese Licenciado"
                                wire-model="licenciadoEditar" wire-attribute="licenciadoEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Folio Mercantil:"
                                placeholder="Ingrese Folio Mercantil" wire-model="foliomercantilEditar"
                                wire-attribute="foliomercantilEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Fecha de Registro:"
                                placeholder="Ingrese fecha de Registro" wire-model="fecharegistroEditar"
                                wire-attribute="fecharegistroEditar" type="date" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-input-validado :readonly="false" label="Lugar de registro:"
                                placeholder="Ingrese Lugar de registro" wire-model="lugarregistroEditar"
                                wire-attribute="lugarregistroEditar" type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <h3 class="text-center">Poder notarial</h3>
                        </div>
                        <div class="col-md-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-md-8 mb-3">
                            <x-input-validado :readonly="false" label="Notario:" placeholder="Ingrese Notario"
                                wire-model="notarioEditar" wire-attribute="notarioEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group mt-5">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-xl">
                                        <input type="checkbox" class="custom-control-input"
                                            wire:model.live="datosextraEditar" id="datosextraSwitchEditar"
                                            name="datosextraEditar">
                                        <label class="custom-control-label" for="datosextraSwitchEditar">Datos
                                            extras</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3" {{ $camposextraeditar ? '' : 'hidden' }}>
                            <x-input-validado :readonly="false" label="No.Licenciado de la notaria:"
                                placeholder="Ingrese No.Licenciado de la notaria" wire-model="numnotarioEditar"
                                wire-attribute="numnotarioEditar" type="text" />
                        </div>
                        <div class="col-md-4 mb-3" {{ $camposextraeditar ? '' : 'hidden' }}>
                            <x-input-validado :readonly="false" label="Fecha de poder notarial:"
                                placeholder="Ingrese Fecha de poder notarial" wire-model="fechapodernotarialEditar"
                                wire-attribute="fechapodernotarialEditar" type="date" />
                        </div>
                        <div class="col-md-4 mb-3" {{ $camposextraeditar ? '' : 'hidden' }}>
                            <x-input-validado :readonly="false" label="Ciudad de la notaria:"
                                placeholder="Ingrese Ciudad de la notaria" wire-model="ciudadnotariaEditar"
                                wire-attribute="ciudadnotariaEditar" type="text" />
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click='editarContratoAceptar'>Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal subir nuevo documento-->
    <div class="modal fade" id="ModalSubirNuevoDoc" tabindex="-1" aria-labelledby="ModalSubirNuevoDocLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-center modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalSubirNuevoDocLabel">Subir Documento Editado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12 mb-3" hidden>
                            <x-input-validado label="Nombre Contrato:" placeholder="nombre contrato"
                                wire-model="idcontratoEditar" type="text" />
                        </div>
                        <div class="col-md-12 mt-3 mb-2">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile"
                                        wire:model.live="docword" wire:key='{{$imageKey}}'>
                                    <label class="custom-file-label" for="customFile">{{ $fileName ? $fileName
                                        :'Seleccione documento'}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3 mb-2 text-center">
                            <!-- Agregado text-center -->
                            <div wire:loading wire:target="docword">
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

                    <div class="modal-footer">
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Cancelar</button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-info" wire:loading.remove
                                    wire:target="docword" wire:click="$dispatch('confirm',{{1}})">Subir</button>
                                <button type="button" class="btn btn-info" disabled wire:loading
                                    wire:target="docword">Subir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('confirm', () => {

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "La cotizacion se generara y comenzara el proceso de contratación.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('save-cotizacion');
                    }
                })
            })

            Livewire.on('success-cotizacion', function([message]) {
                Swal.fire({
                    icon: 'success',
                    title: message[0],
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {


                        window.location.href = '/ventas';

                    }
                });
            });


            Livewire.on('error', function([message]) {
                Swal.fire({
                    icon: 'error',
                    title: message[0],
                    showConfirmButton: false,
                    timer: 3000
                });
            });
            
            Livewire.on('cerrarModal', function([message]) {
            $('#exampleModal').modal('hide');
            Swal.fire({
                    icon: 'success',
                    text: message,
                    title: "Cliente:",
                    showConfirmButton: false,
                    timer: 3000
                });
        });
        @this.on('confirm', (opcion) => {

        Swal.fire({
            title: '¿Estas seguro?',
            text: 'Se actualizara el contrato',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, adelante!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.dispatch(opcion == 1 ? 'guardardocumento' : 'update-dia');
            }
        })
        });
        Livewire.on('agregarArchivocre', function(params) {
                        const nombreArchivo = params[0].nombreArchivo;
                        const tipomensaje = params[1].tipomensaje;
                        Swal.fire({
                            position: 'center',
                            icon: tipomensaje,
                            title: nombreArchivo,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });


        });
    </script>
    @endpush
</div>