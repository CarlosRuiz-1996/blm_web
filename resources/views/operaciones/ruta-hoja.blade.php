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
        font-size: 8px;
        /* Ajusta este valor según lo necesites */
    }
    .small-text-md {
        font-size: 10px;
        /* Ajusta este valor según lo necesites */
    }
    .small-texta{
        font-size: 8px;
        text-align: center;
        /* Ajusta este valor según lo necesites */
    }
</style>

<body>
    <div class="row">
        <div class="col-md-2 col-1">
            <img src="{{ public_path() . '/img/logospdf.png' }}" alt="Nombre alternativo" class="mb-3"
                style="max-width: 70px; float: left;">
        </div>
        <div class="col-md-12 col-12">
            <h4 class="text-center mt-3">Servicios Integrados PRO-BLM de México S.A. de C.V.</h4>
        </div>
        <div class="col-md-12 col-12">
            @php
                $fecha_creacion = \Carbon\Carbon::parse(now());
                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F'));
                $dia = $fecha_creacion->format('d');
                $anio = $fecha_creacion->format('Y');
            @endphp
            <h6 class="text-dark text-right mt-1 mr-8 pr-3">CIUDAD DE MÉXICO, A {{ $dia }} DE {{ $nombre_mes }}
                DEL {{ $anio }}.</h6>
        </div>
        <div class="col-md-2 col-4">
            <table class="table table-bordered border-dark small-text m-1">
                <th class="text-center p-0">Hoja de ruta</th>
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

    <div class="table-responsive">
        <table class="table table-bordered table-xs small text-xs mt-4">
            <thead class="thead-light">
                <tr>
                    <th class="p-1 small-texta" rowspan="2">#</th>
                    <th class="p-1 small-texta" rowspan="2" style="width: 60px;">CLIENTE</th>
                    <th class="p-1 small-texta" rowspan="2" style="width: 60px;">DIRECCIÓN</th>
                    <th class="p-1 small-texta" rowspan="2">T/SERV.</th>
                    <th class="p-1 small-texta" rowspan="2">LLAVES</th>
                    <th colspan="3" class="p-1 small-texta">HORARIOS</th>
                    <th class="p-1 small-texta" colspan="1">RUTA</th>
                    <th class="p-1 small-texta" rowspan="2">FIRMA</th>
                    <th class="p-1 small-texta" rowspan="2">E/B</th>
                    <th class="p-1 small-texta" rowspan="2">E/D</th>
                    <th class="p-1 small-texta" rowspan="2">C/V</th>
                    <th class="p-1 small-texta" rowspan="2">I/V</th>
                </tr>
                <tr>
                    <th class="p-1 small-texta">SERVICIO</th>
                    <th class="p-1 small-texta">INICIO</th>
                    <th class="p-1 small-texta">TÉRMINO</th>
                    <th class="p-1 small-texta">CONSIGNATARIO</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1; @endphp
                @foreach ($ruta->rutaServicios()->whereNotIn('status_ruta_servicios', [6, 0])->get() as $servicio)
                    <tr>
                        <td class="p-1 small-text-md">{{ $i }}</td>
                        <td class="p-1 small-text-md">{{ ucwords(strtolower($servicio->servicio->cliente->razon_social)) }}</td>
                        <td class="p-1 small-text-md">
                            {{ ucwords(strtolower(
                                $servicio->servicio->sucursal->sucursal->direccion . ' ' . 
                                $servicio->servicio->sucursal->sucursal->cp->cp . ' ' . 
                                $servicio->servicio->sucursal->sucursal->cp->estado->name
                            )) }}
                        </td>
                        
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
                <tr>
                    <td class="p-1" colspan="9"></td>
                    <td class="p-1 small-text-md">Total</td>
                    <td class="p-1"></td>
                    <td class="p-1"></td>
                    <td class="p-1"></td>
                    <td class="p-1"></td>
                </tr>
            </tbody>
        </table>
    </div>
    


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
