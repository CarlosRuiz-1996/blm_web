<div>
    <div class="card card-outline card-info m-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Documento</th>
                            <th>Documentos Cargados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tiposDocumentos as $tipoDocumento)
                            <tr>
                                <td>{{ $tipoDocumento->nombre }}</td>
                                <td>
                                    @php
                                        // Verificar si hay documentos para el tipo de documento actual
                                        $hasDocuments = $documentosPorEmpleado->contains('ctg_documentos_expediente_empleados_id', $tipoDocumento->id);
                                    @endphp

                                    @if ($hasDocuments)
                                        <!-- Botón para abrir el collapse individual de cada tipo de documento -->
                                        <button class="btn btn-sm btn-info" data-toggle="collapse" data-target="#collapseDocumentos{{ $tipoDocumento->id }}">
                                            <i class="fas fa-folder-open"></i> Ver Documentos
                                        </button>

                                        <!-- Collapse individual para los documentos del tipo de documento -->
                                        <div id="collapseDocumentos{{ $tipoDocumento->id }}" class="collapse mt-2">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre del Documento</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($documentosPorEmpleado as $doc)
                                                        @if($tipoDocumento->id == $doc->ctg_documentos_expediente_empleados_id)
                                                        @php
                                                            // Obtener la extensión del archivo
                                                            $extension = pathinfo($doc->url_archivo, PATHINFO_EXTENSION);
                                                        @endphp
                                                            <tr>
                                                                <td>{{ $doc->nombre_archivo }}</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-primary" wire:click="descargarDocumento({{ $doc->id }})">
                                                                        <i class="fas fa-download"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-danger" wire:click="eliminarDocumento({{ $doc->id }})">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                    @if(in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) <!-- Agrega otras extensiones de imagen según sea necesario -->
                                                                    <button class="btn btn-sm btn-info"  wire:click="openModaltres({{ $doc->id }})">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                     @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <span>No hay documentos cargados</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" wire:click="openModal({{ $tipoDocumento->id }})">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Bootstrap -->
    <div
        x-data="{ show: @entangle('isOpen') }"
        x-init="
            $watch('show', value => {
                if (value) {
                    $('#myModal').modal('show');
                } else {
                    $('#myModal').modal('hide');
                }
            });
            $('#myModal').on('hidden.bs.modal', () => {
                if (show) {
                    show = false;
                }
            });
        "
        id="myModal"
        class="modal fade @if($isOpen) show @endif"
        tabindex="-1"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
        wire:ignore.self
        style="@if($isOpen) display: block; @endif"
    >
        <div class="modal-dialog modal-dialog-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white text-center">
                    <h5 class="modal-title" id="myModalLabel">Subir Documento</h5>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="uploadDocument">
                        <div class="mb-3" wire:ignore>
                            <label for="archivos" class="form-label">Seleccionar archivo(s)</label>
                            <input type="file" class="form-control" id="archivos" wire:model="archivos" multiple>
                        </div>

                        @error('archivos.*') <span class="text-danger">{{ $message }}</span> @enderror

                        <!-- Muestra los nombres de los archivos seleccionados con opción para eliminarlos -->
                        <div>
                            <h6>Archivos seleccionados:</h6>
                            <ul class="list-group">
                                @if ($archivos && count($archivos) > 0)
                                    @foreach ($archivos as $index => $archivo)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            @php
                                                // Obtener la extensión del archivo
                                                $extension = strtolower($archivo->getClientOriginalExtension());
                                            @endphp

                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) <!-- Si es una imagen -->
                                                <img src="{{ $archivo->temporaryUrl() }}" alt="{{ $archivo->getClientOriginalName() }}" style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                            @elseif ($extension === 'pdf') <!-- Si es un PDF -->
                                                <span>{{ $archivo->getClientOriginalName() }} (PDF)</span>
                                            @elseif (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) <!-- Si es un documento de Office -->
                                                <span>{{ $archivo->getClientOriginalName() }} (Documento)</span>
                                            @else
                                                <span>{{ $archivo->getClientOriginalName() }} (Tipo desconocido)</span>
                                            @endif

                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeFile({{ $index }})">Eliminar</button>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item">No se han seleccionado archivos.</li>
                                @endif
                            </ul>
                        </div>

                        <input type="hidden" wire:model="documentId">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('isOpen', false)">Cerrar</button>
                    <button type="submit" class="btn btn-primary" wire:click='storeDocuments'>Subir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Bootstrap -->
    <div
        x-data="{ show: @entangle('isOpentres') }"
        x-init="
            $watch('show', value => {
                if (value) {
                    $('#myModaldos').modal('show');
                } else {
                    $('#myModaldos').modal('hide');
                }
            });
            $('#myModaldos').on('hidden.bs.modal', () => {
                if (show) {
                    show = false;
                }
            });
        "
        id="myModaldos"
        class="modal fade @if($isOpentres) show @endif"
        tabindex="-1"
        aria-labelledby="myModaldosLabel"
        aria-hidden="true"
        wire:ignore.self
        style="@if($isOpentres) display: block; @endif"
    >
        <div class="modal-dialog modal-dialog-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white text-center">
                    <h5 class="modal-title" id="myModaldosLabel">Vista previa</h5>
                </div>
                <div class="modal-body">
                    @if($url) <!-- Verifica si el nombre del archivo está disponible -->
                        @php
                            // Construir la URL completa del archivo
                            $url = asset('storage/' . $url);
                            $extension = pathinfo($url, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($extension, ['pdf'])) <!-- Si es PDF -->
                            <iframe src="{{ $url }}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                        @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) <!-- Si es imagen -->
                            <img src="{{ $url }}" style="width: 100%; height: auto;" />
                        @else
                            <p>Formato no soportado.</p>
                        @endif
                    @else
                        <p>No hay documento para mostrar.</p>
                    @endif              
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('isOpentres', false)">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('clear-file-input', () => {
                document.getElementById('archivos').value = null;
            });
        });
    </script>
</div>
