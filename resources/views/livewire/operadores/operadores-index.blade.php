<div>
    <div class="container-fluid pt-5">
        @if($identificado)
        <h3 class="ml-2">Operador-<span class="text-info">{{$nombreUsuario}}</span></h3>
        <div class="card">
            <div class="card-body">
                @if(count($rutaEmpleados)>0)
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>Ruta</th>
                                <th>Hora Inicio</th>
                                <th>Hora Termino</th>
                                <th>Dia</th>
                                <th>Riesgo</th>
                                <th>Estado</th>
                                <th>Iniciar/Terminar</th>
                                <!-- Agrega más encabezados según tus necesidades -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rutaEmpleados as $rutaServicio)
                            <tr>

                                <td>{{ $rutaServicio->nombre->name }}</td>
                                <td>{{ $rutaServicio->hora_inicio }}</td>
                                <td>{{ $rutaServicio->hora_fin }}</td>
                                <td>{{ $rutaServicio->dia->name }}</td>
                                <td>{{ $rutaServicio->riesgo->name }}</td>
                                <td>{{ $rutaServicio->estado->name }}</td>
                                <td>
                                    @if($rutaServicio->status_ruta == 1)
                                    <button type="button" wire:click="$dispatch('confirm',{{$rutaServicio->id}})" class="btn btn-primary"><i class="fas fa-play mr-1"></i>
                                        Iniciar</button>
                                    @elseif($rutaServicio->status_ruta == 2)
                                    <button type="button" wire:click="$dispatch('terminar',{{$rutaServicio->id}})" class="btn btn-danger"><i class="fas fa-stop mr-1"></i>
                                        Terminar</button>
                                    @elseif($rutaServicio->status_ruta == 3)
                                    <span style="color: green;"><i class="fas fa-check-circle"></i> Ruta
                                        completada</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="table-dark">
                                <th colspan="3">Servicio</th>
                                <th>Folio</th>
                                <th>Envases</th>
                                <th>Tipo de Servicio</th>
                                <th>Acción</th>
                            </tr>
                            @if($rutaServicio->status_ruta != 1)
                            @foreach ($rutaServicio->rutaServicios as $servicio)

                            <tr>
                                <td colspan="3">{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                <td>{{ $servicio->folio }}</td>
                                <td>{{ $servicio->envases }}</td>
                                <td>
                                    {{ $servicio->tipo_servicio == 1 ? 'Entrega' : ($servicio->tipo_servicio == 2 ?
                                    'Recolección' : 'Otro') }}
                                </td>
                                <td>
                                    @if($servicio->status_ruta_servicios == 2)
                                    <button type="button" class="btn btn-primary"
                                        wire:click="ModalEntregaRecolecta('{{$servicio->id}}','{{$servicio->tipo_servicio}}')"
                                        data-toggle="modal" data-target="#ModalEntregarRecolectar">
                                        <i class="fas fa-play mr-1"></i>
                                        {{ $servicio->tipo_servicio == 1 ? 'Entregar' : ($servicio->tipo_servicio == 2 ?
                                        'Recolectar' : 'Otro') }}
                                    </button>
                                    <button type="button" class="btn btn-danger"  wire:click="ModalReprogramarServicio('{{$servicio->id}}')"
                                        data-toggle="modal" data-target="#ModalReprogramarServicio"><i class="fas fa-clock mr-1"></i>
                                        Reprogramar</button>
                                    @else
                                    <span class="badge {{ $servicio->status_ruta_servicios == 3 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $servicio->status_ruta_servicios == 3 ? 'Terminado' : 'Reprogramado'}}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7">
                            <div class="card bg-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-text text-center">Sin iniciar ruta</h5>
                                    <p class="card-text text-center">Iniciar Ruta para ver Servicios</p>
                                </div>
                            </div>
                        </td>
                         </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            @else
            <div class="card bg-info">
                <div class="card-body text-center">
                    <h5 class="card-text text-center">Sin Rutas Asignadas</h5>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="card bg-danger">
            <div class="card-body text-center">
                <h5 class="card-text text-center">No autorizado</h5>
                <p class="card-text text-center">Área no apta para visualizar datos del operador.</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal fade" id="ModalEntregarRecolectar" wire:ignore.self tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">
                        @if($tiposervicio == 'Entrega')
                        <i class="fas fa-box mr-1"></i>
                        @elseif($tiposervicio == 'Recolección')
                        <i class="fas fa-hand-holding mr-1"></i>
                        @endif
                        {{$tiposervicio}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3" hidden>
                            <x-input-validado :readonly="true" label="idrecolecta:" placeholder="idrecolecta"
                                wire-model="idrecolecta" wire-attribute="idrecolecta" type="text" />
                        </div>
                        <div class="col-md-12 mb-3" hidden>
                            <x-input-validado :readonly="true" label="Monto:" placeholder="Ingrese Monto"
                                wire-model="MontoEntrega" wire-attribute="MontoEntrega" type="text" />
                        </div>
                        <div class="col-md-12 mb-1" {{$tiposervicio == 'Entrega'? 'hidden':''}}>
                            <x-input-validado :readonly="false" label="Envases:" placeholder="Ingrese cantidad de envases"
                                wire-model="envasescantidad" wire-attribute="envasescantidad" type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-validado :readonly="false" label="Monto:"
                                placeholder="Ingrese Monto a entregar" wire-model="MontoEntregado"
                                wire-attribute="MontoEntregado" type="text" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <!-- Input file que abre la cámara -->

                            @error('photo')
                            <div class="text-danger text-xs">{{ $message }}</div>
                            @enderror
                            <input type="file" accept="image/*" capture="camera" id="photo" wire:model.live="photo"
                                style="display: none;">
                            <button type="button" class="btn btn-primary btn-block"
                                onclick="document.getElementById('photo').click();"><i
                                    class="fas fa-camera"></i></button>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center align-items-center">
                            <!-- Vista previa de la foto tomada -->
                            @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Foto Tomada" class="img-fluid"
                                style="max-width: 25%; height: auto;">
                            @endif
                        </div>
                        <div class="col-md-12 mt-3 mb-2 text-center">
                            <div wire:loading wire:target="photo">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @if($tiposervicio == 'Entrega')
                    <button type="button" class="btn btn-primary" @if(!$photo) disabled @endif wire:loading.remove
                        wire:click='ModalAceptar'>Aceptar</button>
                    @elseif($tiposervicio == 'Recolección')
                    <button type="button" class="btn btn-primary" @if(!$photo) disabled @endif wire:loading.remove
                        wire:click='ModalAceptarRecolecta'>Aceptar</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
{{--modal reprogramar--}}
    <div class="modal fade" id="ModalReprogramarServicio" wire:ignore.self tabindex="-1" role="dialog"
    aria-labelledby="ModalReprogramarServicioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="ModalReprogramarServicioLabel">
                    <i class="fas fa-clock mr-1"></i>Reprogramar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3" hidden>
                        <x-input-validado :readonly="true" label="Id servicio" placeholder="Idservicio"
                            wire-model="IdservicioReprogramar" wire-attribute="IdservicioReprogramar" type="text" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <x-input-validado :readonly="false" label="Motivo Reprogramación:"
                            placeholder="Ingrese el motivo para reprogramar" wire-model="motivoReprogramarConcepto"
                            wire-attribute="motivoReprogramarConcepto" type="text" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <!-- Input file que abre la cámara -->

                        @error('photorepro')
                        <div class="text-danger text-xs">{{ $message }}</div>
                        @enderror
                        <input type="file" accept="image/*" capture="camera" id="photorepro" wire:model.live="photorepro"
                            style="display: none;">
                        <button type="button" class="btn btn-primary btn-block"
                            onclick="document.getElementById('photorepro').click();"><i
                                class="fas fa-camera"></i></button>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center align-items-center">
                        <!-- Vista previa de la foto tomada -->
                        @if ($photorepro)
                        <img src="{{ $photorepro->temporaryUrl() }}" alt="Foto Tomada" class="img-fluid"
                            style="max-width: 25%; height: auto;">
                        @endif
                    </div>
                    <div class="col-md-12 mt-3 mb-2 text-center">
                        <div wire:loading wire:target="photorepro">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" @if(!$photorepro) disabled @endif wire:loading.remove wire:click='ModalAceptarReprogramar'>Aceptar</button>
            </div>
        </div>
    </div>
</div>
    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('confirm', (contactId) => {

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "La ruta dara inicio.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('empezarRuta',contactId);
                    }
                })
            })
            @this.on('terminar', (contactId) => {

                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "La ruta terminara ,asegure que los servicios han sido concluidos o reprogramados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('TerminarRuta',contactId);
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
                    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('photo').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                // Mostrar la vista previa de la foto tomada
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = '100%';
                    document.getElementById('photoPreview').appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });



     //reprogramacion        
     document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('photorepro').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                // Mostrar la vista previa de la foto tomada
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = '100%';
                    document.getElementById('photoreproPreview').appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
             });
             $('#ModalEntregarRecolectar').on('hidden.bs.modal', function () {
            Livewire.dispatch('modalCerrado');
        });
        $('#ModalReprogramarServicio').on('hidden.bs.modal', function () {
            Livewire.dispatch('modalCerradoReprogramar');
        });
        });



       
    
    </script>
    @endpush

</div>