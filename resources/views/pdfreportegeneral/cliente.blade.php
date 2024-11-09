<html>
<head>
    <style>
        @page {
            margin: 100px 25px; /* Márgenes para el header y el footer */
        }

        header {
            position: fixed;
            top: -60px; /* Se coloca el encabezado fuera del contenido */
            height: 50px;
            background-color: #b5bbd7; /* Color de fondo */
            color: black;
            left: 0px; /* Asegúrate de que ocupe todo el ancho */
            right: 0px; /* Asegúrate de que ocupe todo el ancho */
            align-items: center; /* Alineamos verticalmente los elementos */
        }

        footer {
            position: fixed;
            bottom: -60px; /* Se coloca el pie de página fuera del contenido */
            left: 0; /* Asegúrate de que ocupe todo el ancho */
            right: 0; /* Asegúrate de que ocupe todo el ancho */
            height: 50px;
            background-color: #b5bbd7;
            color: white;
            text-align: right;
            line-height: 35px;
        }

        .pagenum:before {
            content: counter(page); 
            text-align: center;
            padding-right: 12%;
            color: black;/* Muestra el número de página */
        }

        main {
            margin-top: 15px; /* Espacio suficiente para el header */
            margin-bottom: 60px; /* Espacio suficiente para el footer */
            height: 80vh; /* Ocupa el 80% de la altura de la ventana de impresión */
            width: 100%; /* Ocupa el 100% del ancho de la página */
            text-align: center; /* Centra el texto dentro del main */
        }

        .header {
            display: flex; /* Usamos flexbox para alinear los elementos */
            align-items: center; /* Alineamos verticalmente los elementos */
            width: 100%; /* Asegúrate de que el contenedor ocupe todo el ancho */
        }

        .header img {
            margin-right: 10px; /* Espacio entre la imagen y el texto */
            vertical-align: middle; /* Asegura que la imagen se alinee verticalmente con el texto */
        }

        .header div {
            flex-grow: 1; /* Permite que el div del texto ocupe el espacio restante */
            text-align: center; /* Centra el texto dentro de su contenedor */
        }

        .header p {
            margin: 0; /* Elimina márgenes por defecto para los párrafos */
            line-height: 1.5; /* Mejora la legibilidad vertical */
        }
        .alinear-izquierda{
            float: left;
            }

            .romper-float{
            clear: both;
            }

            /* Detalles adicionales */
            img.alinear-izquierda{
            padding: 5px 5px 5px 35px;
            
            }
            .ultracompact-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px; /* Tamaño de fuente reducido */
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Estilo para las celdas */
        .ultracompact-table th,
        .ultracompact-table td {
            padding: 4px 6px; /* Padding reducido */
            border: 1px solid #ddd;
        }

        /* Celdas destacadas */
        .highlight-cell {
            background-color: #3b4252; /* Gris oscuro */
            color: #ffffff;
            font-weight: bold;
            padding: 4px 6px;
        }

        /* Filas alternadas */
        .ultracompact-table tbody tr:nth-child(even) {
            background-color: #f1f3f5;
        }

        /* Estatus dinámico */
        .status-cell {
            text-align: center;
            font-weight: bold;
            padding: 3px 5px;
            font-size: 11px; /* Tamaño de fuente menor */
            border-radius: 3px;
            color: #ffffff;
        }

        .status-active {
            background-color: #28a745;
        }

        .status-inactive {
            background-color: #dc3545;
        }

        /* Encabezados de columna */
        .ultracompact-table th {
            background-color: #3b4252;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 5px;
            font-size: 12px; /* Tamaño de fuente menor */
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background-color: #f8f9fa;
            margin-top: 15px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .custom-table th,
        .custom-table td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
        }

        /* Encabezado principal */
        .custom-table thead th {
            background-color: #343a40;
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            padding: 8px;
        }

        /* Estilo para las celdas de "Servicios" */
        .service-row {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        /* Estilo para las celdas "Ruta" */
        .route-header-row {
            background-color: #6c757d;
            color: #ffffff;
            text-align: center;
            padding: 5px;
        }

        /* Filas alternadas para mayor legibilidad */
        .custom-table tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }

        /* Estilo para subtítulos de llaves y envases */
        .subtitle-row {
            background-color: #495057;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            padding: 4px;
        }

        /* Filas de tabla para "Cantidad", "Folio", etc. */
        .secondary-header-row th {
            background-color: #6c757d;
            color: #ffffff;
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
            <img src="{{ public_path('img/logo_blm.png') }}" class="alinear-izquierda" width="40" height="40" alt="Logo">
                <p style="text-align: center;padding-right: 15%;font-size: 12px">Reporte general de cliente<br>
                {{ $cliente->razon_social }}</p>
    </header>

    <footer>
        <span style="color: black">Página :</span><span class="pagenum"></span>
    </footer>

    <main>
        <table class="ultracompact-table">
            <tbody>
                <tr>
                    <td class="highlight-cell">Razón Social</td>
                    <td>{{ $cliente->razon_social ?? 'Sin información' }}</td>
                    <td class="highlight-cell">Estado</td>
                    <td>{{ $cliente->cp->estado->name ?? 'Sin dirección asignada' }}</td>
                </tr>
                <tr>
                    <td class="highlight-cell">Municipio</td>
                    <td>{{ $cliente->cp->municipio->municipio ?? 'Sin dirección asignada' }}</td>
                    <td class="highlight-cell">Dirección</td>
                    <td>{{ $cliente->direccion ?? 'Sin dirección asignada' }}</td>
                </tr>
                <tr>
                    <td class="highlight-cell">RFC</td>
                    <td>{{ $cliente->rfc_cliente ?? 'Sin RFC asignado' }}</td>
                    <td class="highlight-cell">Teléfono</td>
                    <td>{{ $cliente->phone ?? 'Sin número telefónico asignado' }}</td>
                </tr>
                <tr>
                    <td class="highlight-cell">Estatus</td>
                    <td colspan="3" class="status-cell {{ $cliente->status_cliente == 1 ? 'status-active' : 'status-inactive' }}">
                        {{ $cliente->status_cliente == 1 ? 'Activo' : 'Inactivo' }}
                    </td>
                </tr>
            </tbody>
        </table>



        <table class="custom-table">
            <tbody>
                @foreach ($cliente->servicios as $servicios)
                    <tr class="text-center">
                        <td colspan="12" class="service-row">{{ $servicios->ctg_servicio->descripcion }}</td>
                    </tr>
                    @if($servicios->ruta_servicios->count() > 0) 
                        
                        @foreach ($servicios->ruta_servicios as $serviciosRuta)
                        <tr style=" background-color: #495057;color: #ffffff;text-align: center;padding: 5px;">
                            <th colspan="2">Ruta</th>
                            <th colspan="1">Monto</th>
                            <th colspan="2">Tipo de Servicio</th>
                            <th colspan="2">Papeleta</th>
                            <th colspan="1">Cantidad de Envases</th>
                            <th colspan="1">Cantidad de Llaves</th>
                            <th colspan="1">Fecha de Servicio</th>
                            <th colspan="2">Sucursal</th>
                        </tr>
                            <tr>
                                <td colspan="2">{{ $serviciosRuta->ruta->nombre->name }}</td>
                                <td colspan="1">${{ number_format($serviciosRuta->monto, 2) }}</td>
                                <td colspan="2">{{ $serviciosRuta->tipo_servicio == 2 ? 'Recolección' : 'Entrega' }}</td>
                                <td colspan="2">{{ $serviciosRuta->folio }}</td>
                                <td colspan="1">{{ $serviciosRuta->envases_servicios->count() ?? '0'}}</td>
                                <td colspan="1">{{ $serviciosRuta->servicioKeys->count() }}</td>
                                <td colspan="1">{{ $serviciosRuta->fecha_servicio ? \Carbon\Carbon::parse($serviciosRuta->fecha_servicio)->format('d/m/Y') : 'Sin fecha asignada' }}</td>
                                <td colspan="2">{{$serviciosRuta->servicio->sucursal->sucursal->sucursal ?? 'Sin sucursal',}}</td>
                            </tr>
                            @if($serviciosRuta->servicioKeys->count() > 0)
                                <tr>
                                    <td colspan="12" class="subtitle-row">LLAVES</td>
                                </tr>
                                @foreach ($serviciosRuta->servicioKeys as $llaves)
                                    <tr>
                                        <td colspan="12" class="text-center">{{ $llaves->key }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($serviciosRuta->envases_servicios->count() > 0)
                                <tr>
                                    <td colspan="12" class="subtitle-row">Envases</td>
                                </tr>
                                <tr class="secondary-header-row">
                                    <th colspan="4">Cantidad</th>
                                    <th colspan="4">Folio</th>
                                    <th colspan="4">Sello de Seguridad</th>
                                </tr>
                                @foreach ($serviciosRuta->envases_servicios as $envase)
                                    <tr>
                                        <td colspan="4">${{ number_format($envase->cantidad,2) }}</td>
                                        <td colspan="4">{{ $envase->folio }}</td>
                                        <td colspan="4">{{ $envase->sello_seguridad }}</td>
                                    </tr>
                                    <tr>
                                        @if($envase->tipo_servicio==1)
                                            @if($envase->evidencia_entrega && !is_null($envase->evidencia_entrega->id))
                                                <tr>
                                                    <td colspan="12" class="text-center" ><img width="400" height="400" src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_entrega_'.$envase->evidencia_entrega->id.'_evidencia.png') }}" alt="Ejemplo de Imagen"> </td>
                                                </tr>
                                            @endif
                                        @else
                                            @if($envase->evidencia_recolecta && !is_null($envase->evidencia_recolecta->id))
                                                <tr>
                                                    <td colspan="12" class="text-center" ><img width="400" height="400" src="{{ asset('storage/evidencias/EntregasRecolectas/Servicio_'.$envase->ruta_servicios_id.'_recolecta_'.$envase->evidencia_recolecta->id.'_evidencia.png') }}" alt="Ejemplo de Imagen"> </td>
                                                </tr>
                                            @endif
                                        @endif

                                    </tr>
                                @endforeach
                            @endif  
                            <tr>
                                <td colspan="12" class="text-center" style="background-color: white;border:white" ></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="text-center" style="background-color: white;border:white" ></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="text-center" style="background-color: white;border:white" ></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="text-center" style="background-color: white;border:white" ></td>
                            </tr>                          
                        @endforeach 
                    @else
                        <tr>
                            <td colspan="12" class="text-center" > Sin servicios realizados</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>
