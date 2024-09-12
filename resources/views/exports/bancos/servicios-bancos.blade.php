<table >
    <thead >
        <tr>
            <th>Razon Social</th>
            <th>Servicio</th>
            <th>Monto</th>
            <th>Papeleta</th>
            <th>Fecha de entrega</th>
            <th>Tipo servicio</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($servicios as $servicio)
            <tr>
                <td>{{ $servicio->servicio->cliente->razon_social ?? 'N/A' }}</td>
                <td>{{ $servicio->servicio->ctg_servicio->descripcion ?? 'N/A' }}</td>
                <td>$ {{ number_format($servicio->monto, 2, '.', ',') }}
                </td>
                <td>
                    {{ $servicio->papeleta }}
                </td>
                <td>
                    {{ $servicio->fecha_entrega }}
                </td>
                <td>
                    {{ $servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolección' }}
                </td>
                <td>
                    {{ $servicio->status_bancos_servicios == 1 ? 'Pendiente' : 'Finalizado' }}
                </td>

            </tr>
        @endforeach


    </tbody>
</table>