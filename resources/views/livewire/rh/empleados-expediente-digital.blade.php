<div>
    <div class="card card-outline card-info m-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-info">
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
                                @foreach($documentosPorEmpleado as $documento)
                                @if($tipoDocumento->id == $documento->ctg_documentos_expediente_empleados_id)
                                {{ $documento->nombre_archivo }}
                                @endif
                                @endforeach
                            </td>
                            <td>
                                <!-- Acciones -->
                                <button class="btn btn-sm btn-warning" href="#" title="Editar"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" href="#" title="Eliminar"><i
                                        class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-sm btn-info" href="#" title="Subir"><i class="fas fa-upload"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>