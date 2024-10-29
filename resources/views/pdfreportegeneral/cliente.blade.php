<!-- resources/views/pdfreportegeneral/cliente.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Cliente</title>
    <style>
        /* Estilos para el PDF */
    </style>
</head>
<body>
    <h1>Detalles del Cliente</h1>
    <p><strong>Razón Social:</strong> {{ $cliente->razon_social }}</p>
    <p><strong>Estatus:</strong> {{ $cliente->status_cliente == 1 ? 'Activo' : 'Inactivo' }}</p>
    <!-- Agrega más detalles del cliente según sea necesario -->
</body>
</html>
