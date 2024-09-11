<table >
    <thead >
        <tr>
            <th>Cantidad</th>
            <th>Papeleta</th>
            <th>Fecha de entrada</th>
            <th>Folio/ticket</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($acreditaciones as $acreditacion)
            <tr>
                <td>$ {{ number_format($acreditacion->envase->cantidad, 2, '.', ',') }}</td>
                <td>
                    {{ $acreditacion->envase->folio }}
                </td>
                <td>
                    {{ $acreditacion->created_at }}
                </td>
                <td>
                    {{ $acreditacion->folio ?? 'Sin folio' }}
                </td>
                <td>
                    
                        {{ $acreditacion->status_acreditacion == 2 ? 'Finalizado' : 'Pendiente' }}
                </td>
               
            </tr>
        @endforeach


    </tbody>
</table>