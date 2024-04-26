<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-2">
                <img src="{{ public_path() . '/img/logospdf.png' }}" alt="Nombre alternativo" class="mb-3"
                    style="max-width: 50px; float: left; margin-right: 10px;">
            </div>
            <div class="col-md-10 col-10">
                <h5 class="text-primary text-center" style="margin-top: 15px;">Servicios Integrados PRO-BLM de México
                    S.A. de C.V.</h5>
            </div>
            <div class="col-md-12 col-12">
                @php
                $fecha_creacion = \Carbon\Carbon::parse($cotizacion->created_at);
                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F'));
                $dia = $fecha_creacion->format('d');
                $anio = $fecha_creacion->format('Y');
                @endphp
                <h6 class="text-dark text-right" style="margin-top: 15px;margin-right: 8%">CIUDAD DE MÉXICO, A {{ $dia .
                    ' DE ' . $nombre_mes . ' DEL ' . $anio }}.</h6>
            </div>
        </div>

        {{-- fecha --}}


        {{-- datos cliente --}}
        <div class="row">
            <div class="col-md-11 col-11">
                <table class="table table-bordered" style="margin-top: 15px; font-size: 8px;">
                    <tr>
                        <td style="background-color: #a9a9a9;">
                            <span class="font-weight-bold">RAZÓN SOCIAL:</span>
                        </td>
                        <td class="text-uppercase">{{ $cotizacion->cliente->razon_social }}</td>
                        <td style="background-color: #a9a9a9;">

                            <span class="font-weight-bold">CONTACTO:</span>

                        </td>
                        <td class="text-uppercase">
                            {{ $cotizacion->cliente->user->name . ' ' . $cotizacion->cliente->user->paterno . ' ' .
                            $cotizacion->cliente->user->materno}}
                        </td>

                    </tr>
                    <tr>
                        <td style="background-color: #a9a9a9;">
                            <span class="font-weight-bold">RFC:</span>
                        </td>
                        <td class="text-uppercase">{{ $cotizacion->cliente->rfc_cliente }}</td>
                        <td style="background-color: #a9a9a9;">

                            <span class="font-weight-bold">TELÉFONO:</span>

                        </td>
                        <td>{{ $cotizacion->cliente->phone }}
                        </td>

                    </tr>
                    <tr>
                        <td style="background-color: #a9a9a9;">
                            <span class="font-weight-bold">CONDICIONES DE PAGO:</span>
                        </td>
                        <td class="text-uppercase">{{ $cotizacion->tipo_pago->name }}</td>
                        <td style="background-color: #a9a9a9;">

                            <span class="font-weight-bold">TIEMPO DE VALIDES DE LA COTIZACION:</span>

                        </td>
                        <td>{{ $cotizacion->vigencia }}
                        </td>

                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-11" style="font-size: 10px;">
                <p>PRESENTE</p>
                <p>
                    {{ Str::upper($cotizacion->cliente->user->name) }}, buenos dias de acuerdo a nuestra platica me
                    permito
                    enviar cotiazacion del servicio de traslado de valores, que a continuacion se detalla.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-11">
                @php $valorcolspan = "4"; @endphp
                @php $total = 0; @endphp
                @php $totalforaneosconcepto = 0; @endphp
                @php $mostrarTablaNormal = true; @endphp

                <!-- Mostrar alerta si hay servicios foráneos -->
                @foreach ($cotizacion->cotizacion_servicio as $servicio)
                @if ($servicio->servicio->servicio_foraneo == 1)
                @php $totallleva = $servicio->servicio->montotransportar_foraneo; @endphp
                @php $valorcolspan = "2"; @endphp
                @php $mostrarTablaNormal = false; @endphp
                <div class="alert alert-warning" role="alert">
                    Este servicio es foráneo.
                </div>
                <div class="alert alert-success text-center" role="alert">
                    {{ number_format($totallleva, 2, '.', ',') }}
                </div>
                @break
                <!-- Salir del bucle después de encontrar el primer servicio foráneo -->
                @endif
                @endforeach

                <!-- Tabla para servicios foráneos -->
                @if (!$mostrarTablaNormal)
                @foreach ($cotizacion->cotizacion_servicio as $servicio)
                @if ($servicio->servicio->servicio_foraneo == 1)
                <table class="table table-bordered" style="margin-top: 15px; font-size: 8px;">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center text-uppercase">Destino : {{
                                $servicio->servicio->foraneo_inicio }} A {{
                                $servicio->servicio->foraneo_destino }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #a9a9a9;">
                            <td colspan="2">CONCEPTO</td>

                            <td>PRECIO UNITARIO</td>
                            <td>TOTAL</td>
                        </tr>
                        <tr>
                            <td>KILOMETROS</td>
                            <td>{{$servicio->servicio->kilometros}}</td>
                            <td>${{$servicio->servicio->kilometros_costo}}</td>
                            <td>${{ ($servicio->servicio->kilometros_costo * $servicio->servicio->kilometros) }}</td>

                        </tr>
                        <tr>
                            <td>MILES</td>
                            <td>{{$servicio->servicio->miles}}</td>
                            <td>${{$servicio->servicio->miles_costo}}</td>
                            <td>${{ ($servicio->servicio->miles_costo * $servicio->servicio->miles) }}</td>
                        </tr>
                        <tr>
                            <td>G/OPERACIÓN</td>
                            <td></td>
                            <td>${{ $servicio->servicio->gastos_operaciones }}</td>
                            <td>${{ $servicio->servicio->gastos_operaciones }}</td>
                        </tr>

                        @if($servicio->servicio->conceptosForaneos)
                        @foreach ($servicio->servicio->conceptosForaneos as $concepto)
                        <tr>
                            <td colspan="2">{{ $concepto->concepto }}</td>
                            <td>${{ $concepto->costo }}</td>
                            <td>${{ $concepto->costo }}</td>
                        </tr>
                        @php $totalforaneosconcepto += $concepto->costo; @endphp
                        @endforeach
                        @endif
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="background-color: #a9a9a9;">Subtotal</td>
                            <td>{{($servicio->servicio->kilometros_costo * $servicio->servicio->kilometros)+
                                ($servicio->servicio->miles_costo * $servicio->servicio->miles)+
                                ($servicio->servicio->gastos_operaciones+$totalforaneosconcepto )}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="background-color: #a9a9a9;">I.V.A.</td>
                            <td>${{ $servicio->servicio->iva }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="background-color: #a9a9a9;">TOTAL:</td>
                            <td>{{$servicio->servicio->subtotal}}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
                @endforeach
                @endif

                <!-- Tabla para servicios normales -->
                @if ($mostrarTablaNormal)
                <table class="table table-bordered" style="margin-top: 15px; font-size: 8px;">
                    <thead>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">PDA</th>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">DESCRIPCIÓN</th>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">UNIDAD DE MEDIDA
                        </th>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">CANTIDAD SOLICITADA
                        </th>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">PRECIO UNITARIO</th>
                        <th class="font-weight-bold" style="background-color: gray; color: white; ">TOTAL</th>
                    </thead>
                    <tbody>
                        @foreach ($cotizacion->cotizacion_servicio as $servicio)
                        @if ($servicio->servicio->servicio_foraneo != 1)
                        <tr>
                            <td>{{ $servicio->servicio->id }}</td>
                            <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                            <td>{{ $servicio->servicio->ctg_servicio->unidad }}</td>
                            <td>{{ $servicio->servicio->cantidad }}</td>
                            <td>${{ $servicio->servicio->precio_unitario }}</td>
                            <td>${{ $servicio->servicio->subtotal }}</td>
                            @php $total += $servicio->servicio->subtotal; @endphp
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                            <td colspan="{{$valorcolspan}}"></td>
                            <td style="background-color: gray; color: white; " class="text-center font-weight-bold">
                                Total:</td>
                            <td style="background-color: gray; color: white; " class="text-center font-weight-bold">${{
                                $total }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
            </div>

        </div>


        <p class=" text-center mt-1" style="font-size: 10px;"> ESTOS PRECIOS SON MAS DE 16% DE IVA</p>
        <div class="row">
            <div class="col-md-11 col-11">
                <table class="table table-bordered" style="margin-top: 15px; font-size: 8px;">
                    <tr style="background-color: #a9a9a9;">
                        <td>
                            <span class="font-weight-bold">DOMICILIO FISCAL:</span>
                        </td>

                    </tr>
                    <tr>
                        <td>{{ Str::upper(
                            $cotizacion->cliente->direccion . ' ' . $cotizacion->cliente->cp->cp . ' ' .
                            $cotizacion->cliente->cp->estado->name,
                            ) }}
                        </td>
                    </tr>
                    <tr style="background-color: #a9a9a9;">
                        <td>
                            <span class="font-weight-bold">OBSERVACIONES:</span>
                        </td>

                    </tr>
                    <tr>
                        <td>S/N</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>