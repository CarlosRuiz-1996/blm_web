<div>
    <div class="table-responsive">
        <table class="table">
            <!-- Encabezados de la tabla -->
            <thead class="table-info">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dia</th>
                    <th>Riesgo</th>
                    <th>Estado</th>
                    <th>Hora Inicio</th>
                    <th>Hora Finalización</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <!-- Cuerpo de la tabla -->
            <tbody>
                @foreach ($serviciosTerinados as $serviciosTerinado)
                    <tr>
                        <td>{{ $serviciosTerinado->id }}</td>
                        <td>{{ $serviciosTerinado->nombre->name }}</td>
                        <td>{{ $serviciosTerinado->dia->name }}</td>
                        <td>{{ $serviciosTerinado->riesgo->name }}</td>
                        <td>{{ $serviciosTerinado->estado->name }}</td>
                        <td>{{ $serviciosTerinado->hora_inicio }}</td>
                        <td>{{ $serviciosTerinado->hora_fin }}</td>
                        <td>
                            <a href="{{route('boveda.procesa-ruta', [$serviciosTerinado])}}"
                            class="btn btn-info"
                            >Procesar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    {{ $serviciosTerinados->links() }}
</div>
