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
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ asset('/img/logospdf.png') }}" alt="Nombre alternativo" class="mb-3"
                    style="max-width: 50px; float: left; margin-right: 10px;">
            </div>
            <div class="col-md-10">
                <h5 class="text-dark text-center" style="margin-top: 15px;">Servicios Integrados PRO-BLM de México
                    S.A. de C.V.</h5>
            </div>
        </div>

        {{-- fecha --}}
        <div>
            @php
                use Carbon\Carbon;
                // Crear un objeto Carbon a partir de la fecha de creación
                $fecha_creacion = Carbon::parse($cotizacion->created_at);
                // Obtener el nombre del mes en español,  Obtener el día, mes y año
                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F')); // Obtiene el nombre del mes
                $dia = $fecha_creacion->format('d');
                $anio = $fecha_creacion->format('Y');
            @endphp
            <h6 class=" text-right">
                CIUDAD DE MÉXICO, A {{ $dia . ' DE ' . $nombre_mes . ' DEL ' . $anio }};
            </h6>
        </div>

        {{-- datos cliente --}}
        <table class="text-center table table-bordered mt-4" width="100%" cellspacing="0" style="font-size:100%">
            <tr>
                <td style="background-color: #a9a9a9;">
                    <h6 class="font-weight-bold">Razón social:</h6>
                </td>
                <td>{{ Str::upper($cotizacion->cliente->razon_social) }}</td>
                <td style="background-color: #a9a9a9;">

                    <h6 class="font-weight-bold">Contacto:</h6>

                </td>
                <td>{{ Str::upper($cotizacion->cliente->user->name . ' ' . $cotizacion->cliente->user->paterno . ' ' . $cotizacion->cliente->user->materno) }}
                </td>

            </tr>
            <tr>
                <td style="background-color: #a9a9a9;">
                    <h6 class="font-weight-bold">RFC:</h6>
                </td>
                <td>{{ Str::upper($cotizacion->cliente->rfc_cliente) }}</td>
                <td style="background-color: #a9a9a9;">

                    <h6 class="font-weight-bold">TELÉFONO:</h6>

                </td>
                <td>{{ $cotizacion->cliente->phone }}
                </td>

            </tr>
            <tr>
                <td style="background-color: #a9a9a9;">
                    <h6 class="font-weight-bold">CONDICIONES DE PAGO:</h6>
                </td>
                <td>{{ $cotizacion->tipo_pago->name }}</td>
                <td style="background-color: #a9a9a9;">

                    <h6 class="font-weight-bold">TIEMPO DE VALIDES DE LA COTIZACION:</h6>

                </td>
                <td>{{ $cotizacion->vigencia }}
                </td>

            </tr>
        </table>
        <div>
            <p>PRESENTE</p>
            <p>
                {{ Str::upper($cotizacion->cliente->user->name) }}, buenos dias de acuerdo a nuestra platica me permito
                enviar cotiazacion del servicio de traslado de valores, que a continuacion se detalla.
            </p>
        </div>
        <table class="table table-bordered" width="100%" cellspacing="0" style="font-size:100%">
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
                @php $total=0; @endphp
                @foreach ($cotizacion->cotizacion_servicio as $servicio)
                    <tr>
                        <td>{{ $servicio->servicio->id }}</td>
                        <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                        <td>{{ $servicio->servicio->ctg_servicio->unidad }}</td>
                        <td>{{ $servicio->servicio->cantidad }}</td>
                        <td>${{ $servicio->servicio->precio_unitario }}</td>
                        <td>${{ $servicio->servicio->subtotal }}</td>
                        @php $total += $servicio->servicio->subtotal @endphp
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"></td>
                    <td style="background-color: gray; color: white; " class="text-center font-weight-bold">
                        Total:</td>
                    <td style="background-color: gray; color: white; " class="text-center font-weight-bold">
                        ${{ $total }}</td>
                </tr>
            </tbody>

        </table>


        <p class=" text-center mt-5"> ESTOS PRECIOS SON MAS DE 16% DE IVA</p>

        <table class="text-center table table-bordered mt-4 mb-5" width="100%" cellspacing="0" style="font-size:100%">
            <tr style="background-color: #a9a9a9;">
                <td>
                    <h6 class="font-weight-bold">DOMICILIO FISCAL:</h6>
                </td>

            </tr>
            <tr>
                <td>{{ Str::upper(
                    $cotizacion->cliente->direccion . ' ' . $cotizacion->cliente->cp->cp . ' ' . $cotizacion->cliente->cp->estado->name,
                ) }}
                </td>
            </tr>
            <tr style="background-color: #a9a9a9;">
                <td>
                    <h6 class="font-weight-bold">OBSERVACIONES:</h6>
                </td>

            </tr>
            <tr>
                <td>S/N</td>
            </tr>
        </table>
    </div>

</body>

</html>
