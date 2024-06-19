<div>
    <div class="card-outline card-info info-box">
        <div class="info-box-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>

                        @foreach ($ruta->rutaServicios as $servicio)
                        <tr>
                            <td>{{$servicio->servicio->cliente->razon_social}}</td>
                            <td>{{$servicio->servicio->ctg_servicio->descripcion}}</td>
                            <td>{{$servicio->tipo_servicio==1?'ENTREGA':'RECOLECCION'}}</td>
                            <td>
                                <button class="btn btn-info">Verificar monto</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>