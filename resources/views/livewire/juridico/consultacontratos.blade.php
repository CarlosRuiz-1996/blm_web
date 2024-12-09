<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <form wire:submit="buscar">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputId">Id</label>
                                    <input type="text" wire:model="inputId" class="form-control" id="inputId" placeholder="Ingresa el Id del contrato">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cliente_id">Clave Cliente</label>
                                    <input type="text" class="form-control" id="cliente_id"
                                         wire:model="cliente_id" placeholder="Ingrese id de cliente">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputFechaInicio">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="inputFechaInicio"
                                         wire:model="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputFechaInicio">Fecha Fin</label>
                                    <input type="date" class="form-control" id="inputFechaFin"
                                         wire:model="inputFechaFin" placeholder="Ingresa el Fecha Inicio">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="selectContratos">Tipo Contrato</label>
                                    <select class="form-control" id="selectContratos" wire:model="tipocontrato">
                                        <option value="">Seleccione</option>
                                        @foreach ($contratos as $ctgcontratos)
                                            <option value="{{ $ctgcontratos->id }}">{{ $ctgcontratos->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mt-2">
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>RFC Cliente</th>
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
                                @foreach($contratoslist as $contrato)
                                <tr>
                                    <td>{{ $contrato->id}}</td>
                                    <td>{{ $contrato->cliente->rfc_cliente}}</td>
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
                                            data-target="#ModalEditarConsulta">
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
                                            data-target="#ModalSubirNuevoDocConsulta">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $contratoslist->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    
    <!-- Modal -->
    <div class="modal fade" id="ModalEditarConsulta" tabindex="-1" aria-labelledby="ModalEditarConsultaLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalEditarConsultaLabel">Editar Datos de Contrato</h5>
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
    <div class="modal fade" id="ModalSubirNuevoDocConsulta" tabindex="-1" aria-labelledby="ModalSubirNuevoDocConsultaLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-center modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalSubirNuevoDocConsultaLabel">Subir Documento Editado</h5>
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
