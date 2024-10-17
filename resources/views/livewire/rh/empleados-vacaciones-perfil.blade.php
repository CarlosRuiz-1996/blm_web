<div>
    <div class="card card-outline card-info m-3">
        <div class="card-body">

            <div>
                <h3>Solicitudes de Vacaciones del Empleado</h3>
            
                @if($solicitudesVacaciones->isEmpty())
                    <p>No hay solicitudes de vacaciones para este empleado.</p>
                @else
                    <table class="table">
                        <thead class="table-info">
                            <tr>
                                <th>ID</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Motivo</th>
                                <th>Estado Vacaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($solicitudesVacaciones as $solicitud)
                                <tr>
                                    <td>{{ $solicitud->id }}</td>
                                    <td>{{ $solicitud->fecha_inicio }}</td>
                                    <td>{{ $solicitud->fecha_fin }}</td>
                                    <td>{{$solicitud->motivo->motivo}}</td>
                                    <td>
                                        {{ $solicitud->status_vacaciones == 1 ? 'Aprobada' : ($solicitud->status_vacaciones == 2 ? 'Rechazada' : ($solicitud->status_vacaciones == 3 ? 'Pendiente' : 'Finalizada')) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            
                    <!-- Mostrar los links de paginación -->
                    {{ $solicitudesVacaciones->links() }}
                @endif
            </div>
            
        </div>
    </div>
</div>