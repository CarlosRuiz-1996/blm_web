<div>
    <div class="card card-outline card-info m-3">
        <div class="card-body">
            <button type="button" class="btn btn-primary float-right mb-2" wire:click='modalherramienta' data-toggle="modal" data-target="#ModalHerramientas">
                Nuevo <i class="fas fa-plus"></i>
            </button>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-info">
                        <tr>
                            <th>Herramienta de trabajo</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Fecha entrega</th>
                            <th>Fecha devolución</th>
                            <th>fecha cambio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empleadoHerramientas as $herramientasempleado)
                        <tr>
                            <td>{{$herramientasempleado->herramienta->nombre}}</td>
                            <td>{{$herramientasempleado->herramienta->descripcion}}</td>
                            <td>
                                <span class="badge 
                                    {{ $herramientasempleado->status_asignacion_herramienta == 1 ? 'bg-success' : 
                                        ($herramientasempleado->status_asignacion_herramienta == 2 ? 'bg-danger' : 
                                        ($herramientasempleado->status_asignacion_herramienta == 3 ? 'bg-warning' : 'bg-secondary')) }}">
                                    {{ $herramientasempleado->status_asignacion_herramienta == 1 ? 'Activo' : 
                                        ($herramientasempleado->status_asignacion_herramienta == 2 ? 'Dado de Baja' : 
                                        ($herramientasempleado->status_asignacion_herramienta == 3 ? 'Cambiado' : 'Desconocido')) }}
                                </span>
                            </td>
                            <td>{{$herramientasempleado->fecha_entrega}}</td>
                            <td>{{$herramientasempleado->fecha_devolucion}}</td>
                            <td>{{$herramientasempleado->fecha_cambio}}</td>
                            <td>
                                <!-- Botón para cambiar -->
                                <button wire:click="cambiar({{ $herramientasempleado->id }})" class="btn btn-sm btn-primary" title="Cambiar"><i class="fas fa-exchange-alt"></i></button>
                            
                                <!-- Botón para dar de baja -->
                                <button wire:click="darDeBaja({{ $herramientasempleado->id }})" class="btn btn-sm btn-warning" title="Dar de baja"><i class="fas fa-arrow-down"></i></button>
                            
                                <!-- Botón para eliminar -->
                                <button wire:click="eliminar({{ $herramientasempleado->id }})" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                            </td>
                                                      
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   {{--modal herramienta--}}
    <div class="modal fade" wire:ignore.self id="ModalHerramientas" tabindex="-1" role="dialog" aria-labelledby="ModalHerramientasLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Encabezado del modal -->
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="ModalHerramientasLabel"><i class="fa fa-toolbox mr-1"></i>Nueva Herramienta de Trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" hidden>
                            <x-input-validado label="idempleado:" placeholder="Ingrese idempleado"
                                wire-model="idempleado" wire-attribute="idempleado" type="text" />
                        </div>
                        <div class="col-md-12">
                            <x-select-validado label="Herramienta:" placeholder="Seleccione"
                            wire-model="ctgherramienta" required>
                            @foreach ($herramientas as $ctgHerramientas)
                            <option value="{{ $ctgHerramientas->id }}">{{ $ctgHerramientas->nombre }}/{{ $ctgHerramientas->descripcion }}</option>
                            @endforeach
                        </x-select-validado>
                        </div>
                    </div>
                </div>
                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click='modalherramientaGuardar'>Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('success', function([message]) {
                Swal.fire({
                    icon: 'success',
                    title: message[0],
                    showConfirmButton: false,
                    timer: 3000
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
        });
    </script>
@endpush
</div>