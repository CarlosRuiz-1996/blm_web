<div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Dirección</th>
                                <th>Monto</th>
                                <th>Papeleta</th>
                                <th>Envases</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Detalles</th>
                            </thead>
                            <tbody>
                                @foreach ($reprogramacion as $repro)
                                    <tr>
                                        <td>{{ $repro->servicio->cliente->razon_social }}</td>
                                        <td>{{ $repro->servicio->ctg_servicio->descripcion }}</td>
                                        <td>
                                            Calle
                                            {{ $repro->servicio->sucursal->sucursal->direccion .
                                                ' ' .
                                                $repro->servicio->sucursal->sucursal->cp->cp .
                                                ' ' .
                                                $repro->servicio->sucursal->sucursal->cp->estado->name .
                                                ' ' }}


                                        </td>
                                        <td>{{ $repro->monto }}</td>
                                        <td>{{ $repro->folio }}</td>
                                        <td>{{ $repro->envases }}</td>
                                        <td>{{ $repro->tipo_servicio }}</td>
                                        <td>{{ $repro->updated_at }}</td>
                                        <td>
                                            <button class="btn btn-info">Asignar Ruta</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
