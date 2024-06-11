<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de ruta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<style>
    .small-text {
        font-size: 0.8em;
        /* Ajusta este valor según lo necesites */
    }
</style>

<body>
    <div class="row">
        <div class="col-md-2 col-1">
            <img src="{{ public_path() . '/img/logospdf.png' }}" alt="Nombre alternativo" class="mb-3"
                style="max-width: 100px; float: left;">
        </div>
        <div class="col-md-12 col-12">
            <h2 class="text-center mt-3">Servicios Integrados PRO-BLM de México S.A. de C.V.</h2>
        </div>
        <div class="col-md-12 col-12">
            @php
                $fecha_creacion = \Carbon\Carbon::parse(now());
                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F'));
                $dia = $fecha_creacion->format('d');
                $anio = $fecha_creacion->format('Y');
            @endphp
            <h6 class="text-dark text-right mt-3 mr-8">CIUDAD DE MÉXICO, A {{ $dia }} DE {{ $nombre_mes }}
                DEL {{ $anio }}.</h6>
        </div>
        <div class="col-md-2 col-4">
            <table class="table table-bordered border-dark small-text mt-3">
                <th class="text-center">Hoja de ruta</th>
            </table>
        </div>
        <div class="col-md-2 col-4">
            <table class="small-text">
                <tr>
                    <th class="text-center">UNIDAD: _________________</th>
                </tr>
                <tr>
                    <th class="text-center">KM: _____________________</th>
                </tr>
            </table>
        </div>
    </div>

    <table class="table table-bordered small-text mt-4">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">CLIENTE</th>
                <th rowspan="2">DIRECCÓN</th>
                <th rowspan="2">T/SERV.</th>
                <th rowspan="2">LLAVES</th>
                <th colspan="3" class="text-center">HORARIOS</th>
                <th colspan="1" class="text-center">RUTA</th>
                <th rowspan="2">FIRMA</th>
                <th rowspan="2">E/B</th>
                <th rowspan="2">E/D</th>
                <th rowspan="2">C/V</th>
                <th rowspan="2">I/V</th>
            </tr>
            <tr>
                <th>SERVICIO</th>
                <th>INICIO</th>
                <th>TERMINO</th>
                <th>CONSIGNATARIO</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach ($ruta->rutaServicios as $servicio)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$servicio->servicio->cliente->razon_social}}</td>
                    <td>
                        {{ $servicio->servicio->sucursal->sucursal->direccion .
                            ' ' .
                            $servicio->servicio->sucursal->sucursal->cp->cp .
                            '' .
                            $servicio->servicio->sucursal->sucursal->cp->estado->name }}
                       
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
            <tr>
                
                <td colspan="9"></td>
                <td>total</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>


    <div class="col-md-2 col-4">
        <table class="small-text">
            <tr>
                <th class="text-center">OPERADOR: _________________</th>
            </tr>
            <tr>
                <th class="text-center">AUXILIAR: _____________________</th>
            </tr>
            <tr>
                <th class="text-center">CUSAEM: _____________________</th>
            </tr>
        </table>
    </div>

</body>

</html>
