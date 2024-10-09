<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Totales</title>
    <style>
        /* Estilos básicos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse; /* Colapsar bordes */
            table-layout: fixed; /* Fijar el ancho de las columnas */
        }
        th, td {
            border: 1px solid #000; /* Bordes negros */
            padding: 8px;
            text-align: left;
            white-space: nowrap; /* Evitar el salto de línea en celdas */
        }
        th {
            background-color: #f2f2f2; /* Color de fondo para el encabezado */
        }
    </style>
</head>
<body>
    <div>
        @foreach ($clientes as $cliente)
            <table>
                <thead>
                    <tr>
                        <th colspan="6" style="background-color: #ffcccc;">Cliente: {{ $cliente->razon_social }}</th> <!-- Ocupa todas las columnas -->
                    </tr>
                    <tr>
                        <th style="background-color: #d0bcbc;">Ruta</th>
                        <th style="background-color: #d0bcbc;">Sucursal</th>
                        <th style="background-color: #d0bcbc;">Papeleta</th>
                        <th style="background-color: #d0bcbc;">Tipo Servicio</th>
                        <th style="background-color: #d0bcbc;">Monto</th>
                        <th style="background-color: #d0bcbc;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cliente->servicios as $servicio)
                        @foreach ($servicio->ruta_servicios as $rutaServicio)
                            <tr>
                                <td style="border: 1px solid #000">{{ $rutaServicio->ruta->nombre->name }}</td>
                                <td style="border: 1px solid #000">{{ $rutaServicio->servicio->sucursal->sucursal->sucursal }}</td>
                                <td style="border: 1px solid #000">{{ $rutaServicio->folio }}</td>
                                <td style="border: 1px solid #000">{{ $rutaServicio->tipo_servicio == 1 ? 'Entrega' : ($rutaServicio->tipo_servicio == 2 ? 'Recolecta' : 'Desconocido') }}</td>
                                <td style="border: 1px solid #000">{{ number_format($rutaServicio->monto, 2) }}</td>
                                <td style="border: 1px solid #000">{{ $rutaServicio->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <br>
        @endforeach
    </div>
</body>
</html>
