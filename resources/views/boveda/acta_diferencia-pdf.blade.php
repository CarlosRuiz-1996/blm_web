<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diferencia de Valores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .justify-text {
            text-align: justify;
            /* Justificar el texto */
        }

        .small-text {
            font-size: 12px;
            /* Ajustar el tamaño del texto, puedes cambiar el valor a tu preferencia */
            max-width: 600px;
            /* Ancho máximo del contenedor del texto */
            margin: 0 auto;
            /* Centrar el contenedor del texto */
            line-height: 1.5;
            /* Espaciado entre líneas para mejorar la legibilidad */
        }

        .checkbox {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
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
        <div class="small-text mt-4">

            <p class="text-start">ACTA ADMINISTRATIVA DE DIFERENCIAS</p>

            <p> ACTA NO. <span class="checkbox font-weight-bold">{{ $diferencia->id }}</span></p>
            @php
                $fecha_creacion = \Carbon\Carbon::parse($diferencia->created_at);
                $nombre_mes = Str::upper($fecha_creacion->translatedFormat('F'));
                $dia = $fecha_creacion->format('d');
                $anio = $fecha_creacion->format('Y');
                $hora = $fecha_creacion->format('H:i'); // Formato de 24 horas (HH:MM)

            @endphp
            <p>EN LA CIUDAD DE MÉXICO SIENDO LAS {{ $hora }} HRS DEL DÍA {{ $dia }} DE
                {{ $nombre_mes }} DEL {{ $anio }}.</p>



            <p class="justify-text ">
                EN LA EMPRESA "SERVICIOS INTEGRADOS PRO-BLM DE MÉXICO, SA DE C.V" UBICADA EN CALLE
                CUAUHTEMOC No. 12, COL. PUEBLO DE SANTA CRUZ MEYEHUALCO, DEL. IZTAPALAPA, CP. 09700
                EN EL ÁREA DE RECUENTO Y PROCESO DE VALORES DE ESTA EMPRESA, SE LEVANTA LA PRESENTE ACTA PARA DEJAR
                CONSTANCIA
                DE LA DIFERENCIA DETALLADA A CONTUNUACIÓN:
            </p>

            <p>FECHA DEL COMPROBANTE: <span class="font-weight-bold checkbox">{{ $diferencia->fecha_comprobante }}</span class="font-weight-bold checkbox"></p>
            <p>ORIGEN DEL DEPÓSITO: (CLIENTE) <span class="font-weight-bold checkbox">{{ $diferencia->cliente->razon_social }}</span class="font-weight-bold checkbox"></p>
            <p>NÚMERO DE FOLIO DE PAPELETA: <span class="font-weight-bold checkbox">{{ $diferencia->folio }}</span class="font-weight-bold checkbox"></p>
            <p>SELLO DE SEGURIDAD: <span class="font-weight-bold checkbox">{{ $diferencia->sello_seguridad }}</span class="font-weight-bold checkbox"></p>
            <p>IMPORTE QUE DICE CONTENER: <span class="font-weight-bold checkbox">$ {{ number_format($diferencia->importe_indicado, 2, '.', ',') }} MXN</span class="font-weight-bold checkbox">
            </p>
            <p>IMPORTE COMPROBADO: <span class="font-weight-bold checkbox">$ {{ number_format($diferencia->importe_comprobado, 2, '.', ',') }} MXN </span class="font-weight-bold checkbox"></p>
            <p>DIFERENCIA</p>

            <p>
                FALTANTE <span class="checkbox">{{ $diferencia->tipo == 0 ? '_X_' : '___' }}</span>
                SOBRANTE <span class="checkbox">{{ $diferencia->tipo != 1 ? '___' : '_X_' }}</span>
                DE <span class="checkbox font-weight-bold">
                    $ {{ number_format($diferencia->diferencia, 2, '.', ',') }} MXN
                </span>
            </p>
            <p>OBSERVACIONES:</p>

            <textarea class="form-control w-full mb-5" cols="3" rows="2">{{ $diferencia->observacion }}</textarea>
            


            <p class="justify-text">LOS INVOLUCRADOS BIEN IMPUESTOS DEL CONTENIDO DE LA PRESENTE ACTA Y DE LOS ALCANCES
                DE LA MISMA SE
                MANIFIESTAN CONFORMES, CONSTATANDO MEDIANTE NOMBRE Y FIRMA.</p>





            <table align="center" style="margin-top: 130px">
                <th>
                    <p class="mr-3">_____________________________</p>
                    <p class="mr-3">NOMBRE Y FIRMA DEL CAJERO</p>
                </th>
                <th>
                    <p class="ml-5">_________________________________</p>
                    <p class="ml-5">NOMBRE Y FIRMA DEL SUPERVISOR</p>
                </th>
            </table>

        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

</body>

</html>
