<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anexo 1</title>
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

        <style>
            .table-dark-border {
                border: 2px solid #333;
                /* Color del borde oscuro */
            }
        </style>

        {{-- datos cliente --}}
        <table class="text-center table table-bordered mt-4 table-dark-border" width="100%" cellspacing="0"
            style="font-size:100%">
            <tr class="table-dark-border">
                <td colspan="4" class="text-center " style="background-color: #808080;">
                    <h6 class="font-weight-bold">ANEXO 1</h6>
                </td>

            </tr>
            <tr class="table-dark-border" style="background-color: #808080;">
                <td class="text-center" colspan="4"></td>

            </tr>
            <tr class="table-dark-border">
                <td class="text-center" colspan="4"></td>

            </tr>
            <tr>
                <td style="background-color: #DCDCDC;">
                    <h6 class="font-weight-bold">CLIENTE:</h6>
                </td>
                <td>{{ Str::upper($anexo->cliente->razon_social) }}</td>
                <td style="background-color: #DCDCDC;">

                    <h6 class="font-weight-bold">CLAVE CLIENTE:</h6>

                </td>
                <td>{{ $anexo->cliente->id }}
                </td>

            </tr>
            <tr>
                <td style="background-color: #DCDCDC;">
                    <h6 class="font-weight-bold">RFC:</h6>
                </td>
                <td>{{ Str::upper($anexo->cliente->rfc_cliente) }}</td>
                <td style="background-color: #DCDCDC;">

                    <h6 class="font-weight-bold">FECHA:</h6>

                </td>
                <td>{{ $anexo->created_at }}
                </td>

            </tr>
            <tr>
                <td style="background-color: #DCDCDC;">
                    <h6 class="font-weight-bold">DIRECCIÓN</h6>
                </td>
                <td colspan="3">
                    {{ Str::upper(
                        $anexo->cliente->direccion . ' ' . $anexo->cliente->cp->cp . ' ' . $anexo->cliente->cp->estado->name,
                    ) }}
                </td>

            </tr>
            <tr>
                <td style="background-color: #DCDCDC;">
                    <h6 class="font-weight-bold">FACTURADO A:</h6>
                </td>
                <td colspan="3">{{ Str::upper($anexo->cliente->razon_social) }}</td>
            </tr>


        </table>

        <table class="text-center table table-bordered mt-4 table-dark-border" width="100%" cellspacing="0"
            style="font-size:100%">

            <tr>
                <td style="background-color: #808080;">
                    <h6 class="font-weight-bold ">CONTACTO:</h6>
                </td>
                <td style="background-color: #808080;">
                    <h6 class="font-weight-bold">CARGO:</h6>
                </td>
                <td style="background-color: #808080;">
                    <h6 class="font-weight-bold">TELEFONO:</h6>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold ">

                        {{ Str::upper($anexo->cliente->user->name . ' ' . $anexo->cliente->user->paterno . ' ' . $anexo->cliente->user->materno) }}
                    </h6>
                </td>
                <td>
                    <h6 class="font-weight-bold">
                        {{ Str::upper($anexo->cliente->puesto) }}
                    </h6>
                </td>
                <td>
                    <h6 class="font-weight-bold">{{ $anexo->cliente->phone }}</h6>
                </td>
            </tr>
        </table>



        <table class="table table-bordered" width="100%" cellspacing="0" style="font-size:100%">
            <thead>
                <th class="font-weight-bold" style="background-color: gray;">CANTIDAD:</th>
                <th class="font-weight-bold" style="background-color: gray;">CONCEPTO:</th>
                <th class="font-weight-bold" style="background-color: gray;">PRECIO POR SERVICIO:
                </th>
                <th class="font-weight-bold" style="background-color: gray;">IMPORTE:
                </th>

            </thead>
            <tbody>
                @php $total=0; @endphp
                @foreach ($anexo->sucursal_servicio as $servicio)
                    <tr>
                        <td>{{ $servicio->servicio->cantidad }}</td>
                        <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                        <td>${{ $servicio->servicio->precio_unitario }}</td>
                        <td>${{ $servicio->servicio->subtotal }}</td>
                        @php $total += $servicio->servicio->subtotal @endphp
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-center font-weight-bold">
                        ESTOS PRECIOS MAS 16 % DE I V A</td>
                    <td style="background-color: gray; " class="text-center font-weight-bold">
                        ${{ $total }}</td>
                </tr>
            </tbody>

        </table>



        <table class="text-center table table-bordered mt-4 mb-5" width="100%" cellspacing="0" style="font-size:100%">

            <tr style="background-color: gray;">
                <td>
                    <h6 class="font-weight-bold">OBSERVACIONES:</h6>
                </td>

            </tr>
            <tr>
                <td>S/N</td>
            </tr>

            <tr>
                <td></td>
            </tr>
            <tr>
                <td>LOS SERVICIOS NO ESPECIFICADOS EN ESTA COTIZACIÓN SE COBRARAN A PRECIOS DE LISTA
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </table>

        <style>
            .firma-table {
                table-layout: fixed; /* Fuerza a que las columnas tengan el mismo ancho */
            }
        
            .firma-table td {
                height: 50px; /* Altura predeterminada para las celdas */
                vertical-align: bottom; /* Alinea el contenido en la parte inferior de las celdas */
            }
        
            .firma-table .taller-info td {
                height: 150px; /* Altura mayor para la segunda fila */
                padding-top: 30px; /* Agrega espacio en la parte superior de las celdas para mover el contenido hacia abajo */
            }
        </style>
        
        <table class="text-center table table-bordered mt-4 mb-5 firma-table" width="100%" cellspacing="0" style="font-size:100%">
            <tr style="background-color: gray;">
                <td>
                    <h6 class="font-weight-bold">POR LA COMPAÑIA</h6>
                </td>
                <td>
                    <h6 class="font-weight-bold">POR EL CLIENTE</h6>
                </td>
            </tr>
            <tr class="taller-info">
                <td>SILVESTRE OCTAVIANO GARCIA CARRILLO</td>
                <td>{{ Str::upper($anexo->cliente->user->name . ' ' . $anexo->cliente->user->paterno . ' ' . $anexo->cliente->user->materno) }}</td>
            </tr>
            <tr>
                <td>SERVICIOS INTEGRADOS PRO-BLM DE MEXICO, S.A. DE C.V.</td>
                <td>{{ $anexo->cliente->razon_social }}</td>
            </tr>
        </table>
        
        
    </div>

</body>

</html>
