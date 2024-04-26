<div class="row">

    <div class="col-md-12">
        <div class="card card-outline card-info">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                                <th>Id</th>
                                <th>Razon Social</th>
                                <th>RFC</th>
                                <th>Contacto</th>
                                <th>Fecha de Solicitud</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($terminadas as $solicitud)
                            <td>{{ $solicitud->id }}</td>
                            <td>{{ $solicitud->cliente->razon_social }}</td>
                            <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                            <td>{{ $solicitud->cliente->user->name .
                                ' ' .
                                $solicitud->cliente->user->paterno .
                                ' ' .
                                $solicitud->cliente->user->materno }}
                            </td>
                            <td>{{ $solicitud->updated_at }}</td>
                            <td>
                                pdf
                            </td>
                        @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
