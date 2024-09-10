<div wire:init='loadServicios'>
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
                @if (count($serviciosTerinados))
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
                                <a href="{{ route('boveda.procesa-ruta', [$serviciosTerinado]) }}"
                                    class="btn btn-info">Procesar</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    @if ($readyToLoad)
                        <tr>
                            <td colspan="8">No hay datos disponibles</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                </div>
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    @if ($serviciosTerinados && $serviciosTerinados->hasPages())
    <div class="col-md-12 text-center">
        {{ $serviciosTerinados->links('pagination::bootstrap-4') }}
    </div>
@endif
</div>
