<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictamen Aceptado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ public_path() . '/img/logospdf.png' }}" alt="Nombre alternativo" class="mb-3"
                    style="max-width: 50px; float: left; margin-right: 10px;">
            </div>
            <div class="col-md-10">
                <h5 class="text-primary text-center" style="margin-top: 15px;">Servicios Integrados PRO-BLM de México
                    S.A. de C.V.</h5>
            </div>
        </div>

        <p class="text-justify mt-5 mb-5" style="font-size: 12px">
            Equipo Comercial <br>
            Servicios Integrados PRO-BLM de Mexico, S.A. de C.V.
            <br>
            <br>
            <br>
            Despues de haber revisado los datos en la Carta de Ley Antilavado, validado los documentos para la
            integracion del expediente unico y realizar
            la verificacion del cliente y sus relacionados en el Web Service de Q&Q, confirmando que no existen
            antecedentes negativos en listas negras:
            <br>
            <br>
            Se otorga el VoBo para continuar con el proceso de contratacion del cliente: {{ $razonSocial }}
        </p>
        <!-- Aquí puedes incluir la tabla con los radio buttons, si así lo deseas -->
        <table class="table table-bordered mb-5" style="font-size: 12px">
            <thead class="table-info">
                <tr>
                    <th>El cliente es PEP</th>
                    <th class="text-center">Seleccionado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cumplimentovalidado as $resultado)
                    <tr>
                        <td class="text-justify">{{ $resultado->name }}</td>
                        <td class="text-center"><b>{{ $resultado->status_cumplimiento_aceptado == 1 ? 'SI' : 'NO' }}</b>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-justify mt-2" style="font-size: 12px">
            Sin mas por el momento, atento a cualquier duda o aclaracion. rz:{{ $razonSocial }}
            <br>
            <br>
            Responsable de Cumplimiento.
            <br>
            Servicios Integrados PRO-BLM de Mexico, S.A. de C.V.
        </p>
    </div>

</body>

</html>
