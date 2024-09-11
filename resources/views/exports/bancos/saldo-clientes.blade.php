<table >
    <thead >
        <tr>
            <th >Razon Social</th>
            <th>RFC</th>
            <th>Contacto</th>
            <th>Resguardo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
            <tr>
                <td >{{ $cliente->razon_social ?? 'N/A' }}</td>
                <td>{{ $cliente->rfc_cliente ?? 'N/A' }}</td>
                <td>{{ ($cliente->user->name ?? '') . ' ' . ($cliente->user->paterno ?? '') . ' ' . ($cliente->user->materno ?? '') }}
                </td>
                <td>
                    ${{ $cliente->resguardo > 0 ? number_format($cliente->resguardo, 2, '.', ',') : 0 }}
                </td>
                
            </tr>
        @endforeach


    </tbody>
</table>