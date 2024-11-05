    <style>
        /* Estilos para el encabezado */
        .header {
            width: 100%;
            text-align: center;
            background-color: gray;
            color: white;
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }
    </style>

    <div class="header">
        <img src="{{ public_path('img/logo_blm.png') }}" width="50" height="60" alt="Logo">
        <div>
            <p>Reporte general de cliente</p>
            <p>{{ $cliente->razon_social }}</p>
        </div>
    </div>
