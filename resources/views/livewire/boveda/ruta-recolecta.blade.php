<div>
    <div class="table-responsive">
        <table class="table">
            <!-- Encabezados de la tabla -->
            <thead class="table-info">
                <tr>
                    <th>Ruta</th>
                    <th>Servicio</th>
                    <th>Tipo de servicio</th>
                    <th>Estatus</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <!-- Cuerpo de la tabla -->
            <tbody>
                @foreach ($serviciosTerinados as $serviciosTerinado)
                    <tr>
                        <td>{{ $serviciosTerinado->ruta_id }}</td>
                        <td>{{ $serviciosTerinado->servicio_id }}</td>
                        <td>{{ $serviciosTerinado->tipo_servicio == 1 ? 'Entrega' : 'Recolección'}}</td>
                        <td>
                        <span class="badge {{ $serviciosTerinado->status_ruta_servicio_reportes == 2 ? 'bg-success' : 'bg-danger' }}">
                            {{ $serviciosTerinado->status_ruta_servicio_reportes == 2 ? 'Servicio Autorizado para reprogramar' : 'Servicio no autorizado para esta ruta (reprogramar)' }}
                        </span>
                        </td>
                        <td>{{ $serviciosTerinado->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    {{ $serviciosTerinados->links() }}
</div>
