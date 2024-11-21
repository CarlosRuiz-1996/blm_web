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
    <div class="p-4">
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
        <table class="text-center table table-bordered table-sm mt-4  " width="100%" cellspacing="0">
            <tr class="">
                <td colspan="4" class="text-center " style="background-color: #808080;">
                    ANEXO 1
                </td>

            </tr>
            <tr class="" style="background-color: #808080;">
                <td class="text-center" colspan="4"></td>

            </tr>
            <tr class="">
                <td class="text-center" colspan="4"></td>

            </tr>
            <tr>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    CLIENTE:
                </th>
                <td style="font-size:11px;">{{ Str::upper($anexo->cliente->razon_social) }}</td>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">

                    CLAVE CLIENTE:

                </th>
                <td style="font-size:11px;">{{ $anexo->cliente->id }}
                </td>

            </tr>
            <tr>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    RFC:
                </th>
                <td style="font-size:11px;">{{ Str::upper($anexo->cliente->rfc_cliente) }}</td>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">

                    FECHA:

                </th>
                <td style="font-size:11px;">{{ $anexo->created_at }}
                </td>

            </tr>
            <tr>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    DIRECCIÓN
                </th>
                <td style="font-size:11px;" colspan="3">
                    {{ Str::upper(
                        $anexo->cliente->direccion . ' ' . $anexo->cliente->cp->cp . ' ' . $anexo->cliente->cp->estado->name,
                    ) }}
                </td>

            </tr>
            <tr>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    FACTURADO A:
                </th>
                <td style="font-size:11px;" colspan="3">{{ Str::upper($anexo->cliente->razon_social) }}</td>
            </tr>


        </table>

        <table class="text-center table table-bordered table-sm mt-4 " width="100%" cellspacing="0">

            <tr>
               <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    CONTACTO:
                </th>
               <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    CARGO:
                </th>
               <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    TELEFONO:
                </th>
            </tr>
            <tr>
                <td style="font-size:11px;">
                    

                        {{ Str::upper($anexo->cliente->user->name . ' ' . $anexo->cliente->user->paterno . ' ' . $anexo->cliente->user->materno) }}
                    
                </td>
                <td style="font-size:11px;">
                    
                        {{ Str::upper($anexo->cliente->puesto) }}
                    
                </td>
                <td style="font-size:11px;">
                    {{ $anexo->cliente->phone }}
                </td>
            </tr>
        </table>



        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
            <thead>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">CANTIDAD:</th>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">CONCEPTO:</th>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">PRECIO POR SERVICIO:
                </th>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">IMPORTE:
                </th>

            </thead>
            <tbody>
                @php $total=0; @endphp
                @foreach ($anexo->sucursal_servicio as $servicio)
                    <tr>
                        <td style="font-size:11px;">{{ $servicio->servicio->cantidad }}</td>
                        <td style="font-size:11px;">{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                        <td style="font-size:11px;">${{ $servicio->servicio->precio_unitario }}</td>
                        <td style="font-size:11px;">${{ $servicio->servicio->subtotal }}</td>
                        @php $total += $servicio->servicio->subtotal @endphp
                    </tr>
                @endforeach
                <tr>
                    <td style="font-size:11px;" colspan="3" class="text-center font-weight-bold">
                        ESTOS PRECIOS MAS 16 % DE I V A</td>
                    <td style="background-color: gray;font-size:11px; " class="text-center font-weight-bold">
                        ${{ $total }}</td>
                </tr>
            </tbody>

        </table>



        <table class="text-center table table-bordered table-sm mt-4 mb-1 " width="100%" cellspacing="0" style="font-size:11px">

            <tr style="background-color: gray;">
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    OBSERVACIONES:
                </th>

            </tr>
            <tr>
                <td style="font-size:11px;">S/N</td>
            </tr>

            <tr>
                <td></td>
            </tr>
            <tr>
                <td style="font-size:11px;">LOS SERVICIOS NO ESPECIFICADOS EN ESTA COTIZACIÓN SE COBRARAN A PRECIOS DE LISTA
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
        
        <table class="text-center table table-bordered table-sm mt-1 mb-5 firma-table" width="100%" cellspacing="0">
            <tr >
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    POR LA COMPAÑIA
                </th>
                <th class="font-weight-bold" style="background-color: gray;font-size:11px;">
                    POR EL CLIENTE
                </th>
            </tr>
            <tr class="taller-info">
                <td style="font-size:11px;">SILVESTRE OCTAVIANO GARCIA CARRILLO</td>
                <td style="font-size:11px;">{{ Str::upper($anexo->cliente->user->name . ' ' . $anexo->cliente->user->paterno . ' ' . $anexo->cliente->user->materno) }}</td>
            </tr>
            <tr>
                <td style="font-size:11px;">SERVICIOS INTEGRADOS PRO-BLM DE MEXICO, S.A. DE C.V.</td>
                <td style="font-size:11px;">{{ $anexo->cliente->razon_social }}</td>
            </tr>
        </table>
        
        
    </div>

</body>

</html>
