<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Cliente</title>
    <style>
        /* Estilos CSS específicos para el Excel */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-header {
            background-color: #a058589d;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        th, td {
            border: 1px solid black; /* Borde para las celdas */
            padding: 8px; /* Espaciado interno */
        }
        .logo {
            width: 50px; /* Ajustar el tamaño del logo */
            height: auto; /* Mantener proporción */
        }
    </style>
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="8" style="text-align: center; vertical-align: middle; height: 100px;"> <!-- Ajusta la altura según sea necesario -->
                    <img src="{{ public_path('img/logospdf.png') }}" width="50px" height="60px" alt="Logo">
                    <p>Reporte general de cliente</p>
                    <p>{{ $cliente->razon_social }}</p>
                </th>
                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" style="height: 40px;">Información del Cliente</td>
            </tr>
        </tbody>
    </table>
    
    <table class="table">
        <thead>
            <tr class="table-header">
                <th>ID</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" style="background-color: #a058589d">{{ $cliente->id }}</td>
                <td>{{ $cliente->razon_social }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
