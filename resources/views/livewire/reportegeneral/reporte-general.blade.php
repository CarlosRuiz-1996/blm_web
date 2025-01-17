<div>
<style>
/* Estilos del Modal de Expansión de Imagen */
.image-modal {
    display: none;
    position: fixed;
    z-index: 1050; /* Debe ser más alto que el modal principal */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: center;
}

.image-modal-content {
    max-width: 90%;
    max-height: 90%;
}

.close-image-modal {
    position: absolute;
    top: 10px;
    right: 20px;
    color: #fff;
    font-size: 30px;
    cursor: pointer;
}

</style>
    <div>
        <div class="row mb-2 mt-2 align-items-end">
            <div class="col-3 col-sm-12 col-md-3">
                <label for="fechainicio">Fecha Inicio</label>
                <input class="form-control" type="date" wire:model.live='fechaInicio'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <label for="fechafin">Fecha Fin</label>
                <input class="form-control" type="date" wire:model.live='fechaFin'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <label for="razonsocial">Cliente:Razón Social</label>
                <input class="form-control" type="text" wire:model.live='razonsocial'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <label for="perPage">Mostrar:</label>
                <select wire:model.live="perPage" id="perPage" class="form-control" style="width: auto; display: inline-block;">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                registros por página.
            </div>
        </div>
        <!-- Selector de cantidad de resultados por página -->
       
        <!-- Tabla de clientes -->
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer; white-space: nowrap;">
                        ID
                        @if($sortColumn === 'id')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('razon_social')" style="cursor: pointer; white-space: nowrap;">
                        Razón Social
                        @if($sortColumn === 'razon_social')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th>
                        Servicios
                    </th>
                    <th wire:click="sortBy('status_cliente')" style="cursor: pointer; white-space: nowrap;">
                        Estatus
                        @if($sortColumn === 'status_cliente')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th>
                        Reportes
                    </th>
                    <!-- Puedes agregar más columnas aquí -->
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr>
                    <td class="text-center">{{ $cliente->id }}</td>
                    <td>
                        <a href="#" wire:click="loadCliente({{ $cliente->id }})"
                            class="text-primary text-decoration-underline cursor-pointer">
                            {{ $cliente->razon_social }}
                        </a>
                    </td>
                    <td><a href="#" wire:click="loadClienteServicios({{ $cliente->id }})"
                            class="text-primary text-decoration-underline cursor-pointer">
                            Servicios ({{$cliente->servicios->count()}})
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $cliente->status_cliente == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $cliente->status_cliente == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('descargarpdfgeneral.pdf', ['id' => $cliente->id, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]) }}" class="btn btn-link text-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        
                        <a href="{{ route('descargarexcelgeneral.excel', ['id' => $cliente->id, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]) }}" class="btn btn-link text-success" target="_blank">
                            <i class="fas fa-file-excel"></i>
                        </a>
                                           
                    </td>
                    
                    <!-- Agrega más campos aquí -->
                </tr>
                @endforeach
            </tbody>
        </table>


        {{ $clientes->links() }}
        <!-- Paginación -->
    </div>

<!--modal detalle cliente-->

    <div x-data="{ show: @entangle('isOpen') }" x-init="$watch('show', value => {
            if (value) {
                $('#ModalCliente').modal('show');
            } else {
                $('#ModalCliente').modal('hide');
            }
        });
        $('#ModalCliente').on('hidden.bs.modal', () => {
            if (show) {
                show = false;
            }
        });" id="ModalCliente" class="modal fade" tabindex="-1" aria-labelledby="ModalClienteLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white text-center">
                    <h5 class="modal-title" id="ModalClienteLabel">Detalle cliente</h5>
                </div>
                <div class="modal-body">
                    @if($clienteSeleccionado)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="bg-dark text-white">Razón Social</td>
                                    <td>{{ $clienteSeleccionado->razon_social ?? 'Sin información' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">Estatus</td>
                                    <td>{{ $clienteSeleccionado->status_cliente == 1 ? 'Activo' : 'Inactivo' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">Estado</td>
                                    <td>{{ $clienteSeleccionado->cp->estado->name ?? 'Sin dirección asignada' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">Municipio</td>
                                    <td>{{ $clienteSeleccionado->cp->municipio->municipio ?? 'Sin dirección asignada' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">Dirección</td>
                                    <td>{{ $clienteSeleccionado->direccion ?? 'Sin dirección asignada' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">RFC</td>
                                    <td>{{ $clienteSeleccionado->rfc_cliente ?? 'Sin RFC asignado' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-dark text-white">Teléfono</td>
                                    <td>{{ $clienteSeleccionado->phone ?? 'Sin número telefónico asignado' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>No se ha seleccionado ningún cliente.</p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" x-on:click="show = false; $wire.cerrarModal();">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

<!--modal detalle servicios cliente-->
<div x-data="{ show: @entangle('isOpenServicios') }" x-init="$watch('show', value => {
    if (value) {
        $('#ModalClienteServicios').modal('show');
    } else {
        $('#ModalClienteServicios').modal('hide');
    }
});
$('#ModalClienteServicios').on('hidden.bs.modal', () => {
    if (show) {
        show = false;
    }
});" id="ModalClienteServicios" class="modal fade" tabindex="-1" aria-labelledby="ModalClienteServiciosLabel" aria-hidden="true"
wire:ignore.self>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-info text-white text-center">
            <h5 class="modal-title" id="ModalClienteServiciosLabel">Detalle Cliente/Servicios</h5>
        </div>
        <div class="modal-body">
            @if($clienteServicios && count($clienteServicios) > 0)
                @foreach($clienteServicios as $servicio)
                <div id="accordion">
                    <div class="card">
                      <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block d-flex flex-column align-items-center text-center" data-toggle="collapse" data-target="#collapse{{$servicio->id}}" aria-expanded="true" aria-controls="collapse{{$servicio->id}}">
                                <p class="mb-0">{{$servicio->ctg_servicio->descripcion}} ({{$servicio->ruta_servicios->count()}})</p>
                                <p class="mb-0">Sucursal: {{$servicio->sucursal->sucursal->sucursal}}</p>
                            </button>
                        </h5>
                      </div>
                  
                      <div id="collapse{{$servicio->id}}" class="collapse" aria-labelledby="heading{{$servicio->id}}" data-parent="#accordion">
                        <div class="card-body">
                         @if($servicio->ruta_servicios)
                         <div class="table-responsive">
                         <table class="table  table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Ruta</th>
                                    <th>Monto</th>
                                    <th>Tipo</th>
                                    <th>Papeleta</th>
                                    <th>Envases</th>
                                    <th>llaves</th>
                                    <th>Fecha de Servicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicio->ruta_servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->id }}</td>
                                        <td>{{ $servicio->ruta->nombre->name }}</td>
                                        <td>${{ number_format($servicio->monto, 2) }}</td>
                                        <td>{{ $servicio->tipo_servicio == 2 ? 'Recoleccion':'Entrega' }}</td>
                                        <td>{{ $servicio->folio}}</td>
                                        <td>
                                            @if($servicio->envases_servicios->count() > 0)
                                                <div>
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseEnvases{{$servicio->id}}" aria-expanded="false" aria-controls="collapseEnvases{{$servicio->id}}">
                                                        Enveses ({{$servicio->envases_servicios->count()}})
                                                    </button>
                                                    <div class="collapse" id="collapseEnvases{{$servicio->id}}">
                                                        <div class="card card-body">
                                                            <table class="table table-xs">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-xs">Cantidad</th>
                                                                        <th class="text-xs">Folio</th>
                                                                        <th class="text-xs">Sello de Seguridad</th>
                                                                        <th class="text-xs">Evidencia</th>
                                                                    </tr>
                                                                </thead>
                                                                  <tbody>
                                                                    @foreach ($servicio->envases_servicios as $envase)
                                                                        <tr>
                                                                            <td class="text-xs">{{$envase->cantidad}}</td>
                                                                            <td class="text-xs">{{$envase->folio}}</td>
                                                                            <td class="text-xs">{{$envase->sello_seguridad}}</td>
                                                                            @if($envase->tipo_servicio == 1 && $envase->evidencia_entrega && !is_null($envase->evidencia_entrega->id))
                                                                                <td colspan="12" class="text-center">
                                                                                    <img onclick="expandImage('{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_entrega_'.$envase->evidencia_entrega->id.'_evidencia.png') }}')" width="50" height="50" src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_entrega_'.$envase->evidencia_entrega->id.'_evidencia.png') }}" alt="Imagen de Entrega">
                                                                                </td>
                                                                            @elseif($envase->evidencia_recolecta && !is_null($envase->evidencia_recolecta->id))
                                                                                <td colspan="12" class="text-center">
                                                                                    <img onclick="expandImage('{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_recolecta_'.$envase->evidencia_recolecta->id.'_evidencia.png') }}')" width="50" height="50" src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_recolecta_'.$envase->evidencia_recolecta->id.'_evidencia.png') }}" alt="Imagen de Recolecta">
                                                                                </td>
                                                                            @endif

                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <!-- Segundo Modal de Expansión para la Imagen -->

                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-success">Sin envases</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($servicio->servicioKeys->count() > 0)
                                                <div>
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseLlaves{{$servicio->id}}" aria-expanded="false" aria-controls="collapseLlaves{{$servicio->id}}">
                                                        Llaves ({{$servicio->servicioKeys->count()}})
                                                    </button>
                                                    <div class="collapse" id="collapseLlaves{{$servicio->id}}">
                                                        <div class="card card-body">
                                                            @foreach ($servicio->servicioKeys as $llaves)
                                                                <p>{{$llaves->key}}</p>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-success">Sin llaves</span>
                                            @endif
                                        </td>                                        
                                        <td>{{ $servicio->fecha_servicio ? \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d/m/Y') : 'Sin fecha asignada' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                         </div>
                        @else
                            <p>No se encontraron servicios realizados.</p>
                        @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
            @else
                <p>No se encontraron servicios para este cliente.</p>
            @endif
        </div>       

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" x-on:click="show = false; $wire.cerrarModalServicios();">
                Cerrar
            </button>
        </div>
    </div>
</div>
</div>
 <div id="imageModal" class="image-modal" onclick="closeImageModal()">
                                                                <span class="close-image-modal">&times;</span>
                                                                <img class="image-modal-content" id="expandedImage">
                                                            </div>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Define la función correctamente dentro del 'window'
        window.expandImage = function(src) {
            var imageModal = document.getElementById("imageModal");
            var expandedImage = document.getElementById("expandedImage");

            if (imageModal && expandedImage) {
                // Forza la actualización de la imagen agregando un timestamp como parámetro en el src para evitar cachés
                expandedImage.src = src + "?t=" + new Date().getTime();

                // Muestra el modal de imagen
                imageModal.style.display = "flex";
            } else {
                console.error("Los elementos 'imageModal' o 'expandedImage' no se encontraron en el DOM.");
            }
        }

        window.closeImageModal = function() {
            var imageModal = document.getElementById("imageModal");
            imageModal.style.display = "none";
        }

        // Detecta el clic fuera de la imagen ampliada para cerrar el modal
        document.addEventListener("click", function(event) {
            var imageModal = document.getElementById("imageModal");
            var modalContent = document.getElementById("modalContent");

            if (imageModal && modalContent && imageModal.style.display === "flex" && !modalContent.contains(event.target)) {
                window.closeImageModal();
            }
        });
    });
</script>




</div>