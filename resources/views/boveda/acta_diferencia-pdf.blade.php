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

            <p> ACTA NO. __________</p>

            <p> EN LA CIUDAD DE MÉXICO SIENDO LAS ____ HRS DEL DIA __ DE ___ DEL 2024.</p>

            <p class="justify-text ">
                EN LA EMPRESA "SERVICIOS INTEGRADOS PRO-BLM DE MÉXICO, SA DE C.V" UBICADA EN CALLE
                CUAUHTEMOC No. 12, COL. PUEBLO DE SANTA CRUZ MEYEHUALCO, DEL. IZTAPALAPA, CP. 09700
                EN EL ÁREA DE RECUENTO Y PROCESO DE VALORES DE ESTA EMPRESA, SE LEVANTA LA PRESENTE ACTA PARA DEJAR
                CONSTANCIA
                DE LA DIFERENCIA DETALLADA A CONTUNUACIÓN:
            </p>

            <p>FECHA DEL COMPROBANTE: ________</p>
            <p>ORIGEN DEL DEPÓSITO:  (CLIENTE)_________________________</p>
            <p>NÚMERO DE FOLIO DE PAPELETA: ________________________</p>
            <p>IMPORTE QUE DICE CONTENER: $ _________________</p>
            <p>IMPORTE COMPROBADO: $ _________________</p>
            <p>DIFERENCIA
            <p>FALTANTE __ SOBRANTE __ DE $ _____________</p>

            <p>OBSERVACIONES:_________________________________________________________________
                _________________________________________________________________________________
            </p>

            <p class="justify-text">LOS INVOLUCRADOS BIEN IMPUESTOS DEL CONTENIDO DE LA PRESENTE ACTA Y DE LOS ALCANCES DE LA MISMA SE
                MANIFIESTAN CONFORMES, CONSTATANDO MEDIANTE NOMBRE Y FIRMA.</p>





            <table align="center" style="margin-top: 130px">
                <th >
                    <p class="mr-3">_____________________________</p>
                    <p class="mr-3">NOMBRE Y FIRMA DEL CAJERO</p>
                </th>
                <th >
                    <p class="ml-5">_________________________________</p>
                    <p class="ml-5">NOMBRE Y FIRMA DEL SUPERVISOR</p>
                </th>
            </table>

        </div>


    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

</body>

</html>
