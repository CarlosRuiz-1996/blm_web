<div>
    <div class="card-outline card-info info-box">
        <div class="info-box-content">
            <div class="table-responsive">
                <table class="table">
                    <!-- Encabezados de la tabla -->
                    <thead class="table-info">
                        <tr>
                            <th>Servicio</th>
                            <th>Resguardo Antes de Movimiento</th>
                            <th>Cantidad Movimiento</th>
                            <th>Resguardo Despues de Movimiento</th>
                            <th>Tipo de Servicio</th>
                            <th>Fecha Movimiento</th>
                        </tr>
                    </thead>
                    <!-- Cuerpo de la tabla -->
                    <tbody>
                        @foreach ($repotes as $repotesresguardo)
                            <tr>
                                <td>{{ $repotesresguardo->servicio->ctg_servicio->descripcion }}</td>
                                <td>$ {{ number_format($repotesresguardo->resguardo_actual, 2, '.', ',') }}</td>
                                <td>$ {{ number_format($repotesresguardo->cantidad, 2, '.', ',') }}</td>
                                <td>$ {{ number_format(($repotesresguardo->resguardo_actual-$repotesresguardo->cantidad), 2, '.', ',') }}</td>
                                <td>
                                    <span class="badge {{ $repotesresguardo->tipo_servicio == 1 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $repotesresguardo->tipo_servicio == 1 ? 'Servicio Entrega' : 'Servicio Recoleccion' }}
                                </span>
                                </td>
                                <td>{{ $repotesresguardo->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            {{ $repotes->links() }}
        </div>
    </div>
</div>
