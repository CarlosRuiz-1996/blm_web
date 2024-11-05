<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Cliente</title>
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="4" style="text-align: center; vertical-align: middle; height: 100px width:100%; background-color: gray; color: black;"></th>
                <th colspan="8" style="text-align: center; vertical-align: middle; height: 100px width:100%; background-color: gray; color: black;">
                    <img src="{{ public_path('img/logo_blm.png') }}" width="50px" height="60px" style="margin-bottom: 300px;" alt="Logo">
                    <div style="text-align: center;">
                        <p style="margin: 0;">Reporte general de cliente</p> <!-- Quita margen para un mejor centrado -->
                        <p style="margin: 0;">{{ $cliente->razon_social }}</p> <!-- Quita margen para un mejor centrado -->
                    </div>
                </th> 
                <th colspan="4" style="text-align: center; vertical-align: middle; height: 100px width:100%; background-color: gray; color: black;"></th>            
            </tr>            
        </thead>
        <tbody>
            <tr style="border: 10px">
                <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;">Información del Cliente</td>
            </tr>
            <tr>
                <th colspan="3" style="background-color: black;color:white;">Razón Social</th>
                <th colspan="2" style="background-color: black;color:white;">Estado</th>
                <th colspan="2" style="background-color: black;color:white;">Municipio</th>
                <th colspan="3" style="background-color: black;color:white;">Dirección</th>
                <th colspan="3" style="background-color: black;color:white;">RFC</th>
                <th colspan="2" style="background-color: black;color:white;">Teléfono</th>
                <th colspan="1" style="background-color: black;color:white;">Estatus</th>
            </tr>
            <tr>
                <td colspan="3">{{ $cliente->razon_social ?? 'Sin información' }}</td>
                <td colspan="2">{{ $cliente->cp->estado->name ?? 'Sin dirección asignada' }}</td>
                <td colspan="2">{{ $cliente->cp->municipio->municipio ?? 'Sin dirección asignada' }}
                <td colspan="3">{{ $cliente->direccion ?? 'Sin dirección asignada' }}</td>
                <td colspan="3">{{ $cliente->rfc_cliente ?? 'Sin RFC asignado' }}</td>
                <td colspan="2">{{ $cliente->phone ?? 'Sin número telefónico asignado' }}</td>
                <td colspan="1" style="background-color:{{ $cliente->status_cliente == 1 ? 'green;' : 'red' }}">{{ $cliente->status_cliente == 1 ? 'Activo' : 'Inactivo' }}</td>
            </tr>
            <tr style="border: 10px">
                <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;">Servicios</td>              
            </tr>
            @foreach ($cliente->servicios as $servicios)
                <tr>
                    <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">{{$servicios->ctg_servicio->descripcion}}</td> 
                </tr>
                    @if($servicios->ruta_servicios->count()>0) 
                       <tr>
                        <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Ruta</td>
                        <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Monto</td>
                        <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Tipo</td>
                        <td colspan="2" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Envases</td>
                        <td colspan="2" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">llaves</td>
                        <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Fecha de Servicio</td>
                       </tr>
                        @foreach ($servicios->ruta_servicios as $serviciosRuta)
                        <tr>
                            <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;">
                                {{$serviciosRuta->ruta->nombre->name}}
                            </td>   
                            <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;">
                                ${{ number_format($serviciosRuta->monto, 2) }}
                            </td>
                            <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;">
                                {{ $serviciosRuta->tipo_servicio == 2 ? 'Recoleccion':'Entrega' }}
                            </td>
                            <td colspan="2" style="height: 40px;text-align: center;vertical-align: middle;">
                                {{$serviciosRuta->envases_servicios->count()}}
                            </td>
                            <td colspan="2" style="height: 40px;text-align: center;vertical-align: middle;">
                                {{$serviciosRuta->servicioKeys->count()}}
                            </td>
                            <td colspan="3" style="height: 40px;text-align: center;vertical-align: middle;">
                                {{ $serviciosRuta->fecha_servicio ? \Carbon\Carbon::parse($serviciosRuta->fecha_servicio)->format('d/m/Y') : 'Sin fecha asignada' }}
                            </td>
                        </tr> 
                            @if($serviciosRuta->servicioKeys->count()>0)
                                <tr>
                                    <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">
                                    LLAVES
                                    </td>
                                </tr>
                                    @foreach ($serviciosRuta->servicioKeys as $llaves)
                                    <tr>
                                        <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;">{{$llaves->key}}</td>
                                    </tr>
                                    @endforeach
                            @endif
                            @if($serviciosRuta->envases_servicios->count()>0)
                                <tr>
                                    <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">
                                    Envases
                                    </td>
                                </tr>
                                <tr>
                                        <th colspan="5" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Cantidad</th>
                                        <th colspan="6" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Folio</th>
                                        <th colspan="5" style="height: 40px;text-align: center;vertical-align: middle;background-color: gray;">Sello de Seguridad</th>
                                </tr>
                                        @foreach ($serviciosRuta->envases_servicios as $envase)
                                        <tr>
                                            <td colspan="5" style="height: 40px;text-align: center;vertical-align: middle;">{{$envase->cantidad}}</td>
                                            <td colspan="6" style="height: 40px;text-align: center;vertical-align: middle;">{{$envase->folio}}</td>
                                            <td colspan="5" style="height: 40px;text-align: center;vertical-align: middle;">{{$envase->sello_seguridad}}</td>
                                        </tr>
                                        @endforeach
                            @endif
                        @endforeach 
                    @else
                    <tr>
                        <td colspan="16" style="height: 40px;text-align: center;vertical-align: middle;">
                                Sin servicios realizados
                        </td>
                    </tr>
                    @endif
            @endforeach

        </tbody>
    </table>
</body>
</html>
