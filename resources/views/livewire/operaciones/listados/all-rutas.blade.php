    <div class="col-md-12">
        <div class="card card-outline card-info">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-primary">
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
                        <tbody>
                            @foreach ($rutas as $ruta)
                                <tr>
                                    <td>{{ $ruta->id }}</td>
                                    <td>{{ $ruta->nombre->name }}</td>
                                    <td>{{ $ruta->dia->name }}</td>
                                    <td>{{ $ruta->riesgo->name }}</td>
                                    <td>{{ $ruta->estado->name }}</td>
                                    <td>{{ $ruta->hora_inicio }}</td>
                                    <td>{{ $ruta->hora_fin?$ruta->hora_fin:'No especificada' }}</td>
                                    <td>
                                        <a href="{{ route('ruta.gestion', [2, $ruta]) }}">Detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
