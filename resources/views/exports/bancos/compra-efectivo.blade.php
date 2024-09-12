<table >
    <thead >
        <tr>
            <th>Total</th>
            <th>Fecha solicitada</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($compras as $compra)
            <tr>
                <td>$ {{ number_format($compra->total, 2, '.', ',') }}
                <td>{{ $compra->fecha_compra }}
                </td>
                <td>
                    {{ $compra->status_compra_efectivos == 1 ? 'Pendiente' : 'Finalizada' }}
                </td>
                
            </tr>
        @endforeach


    </tbody>
</table>