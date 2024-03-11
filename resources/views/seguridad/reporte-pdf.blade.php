<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Reporte de factibilidad</title>
</head>

<body>

    {{-- encabezado --}}
    <div style="text-align: center;">
        <img src="{{ asset('img/logo_blm.png') }}" height="60" width="60" alt="Logo" style="float: left;">
        <div style="margin-left: -5%">
            <h3 class="font-weight-bold">Reporte de Factibilidad</h3>
            <h6 class="font-weight-bold" style="margin-top: -10px">Seguridad interna</h6>
        </div>
    </div>

    {{-- datos cliente --}}
    <table class="text-center table table-bordered mt-4" width="100%" cellspacing="0" style="font-size:100%">
        <tr>
            <td style="background-color: #a9a9a9;">
                <h6 class="font-weight-bold">Razón social:</h6>
            </td>
            <td>{{ $sucursal->cliente->razon_social }}</td>
            <td style="background-color: #a9a9a9;">

                <h6 class="font-weight-bold">Contacto:</h6>

            </td>
            <td>{{ $sucursal->contacto }}</td>

        </tr>
        <tr>
            <td style="background-color: #a9a9a9;">
                <h6 class="font-weight-bold">Sucursal:</h6>
            </td>
            <td>{{ $sucursal->sucursal }}</td>
            <td style="background-color: #a9a9a9;">

                <h6 class="font-weight-bold">Evaluador:</h6>

            </td>
            <td>{{ $evaluador->name.' '.  $evaluador->paterno.' '. $evaluador->materno}}</td>

        </tr>
        <tr>
            <td style="background-color: #a9a9a9;">

                <h6 class="font-weight-bold">Domicilio:</h6>

            </td>
            <td colspan="3">{{ $direccion }}</td>


        </tr>
    </table>
    {{-- formulario --}}
    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="font-size:100%">
        <tbody>

            <tr>
                <td>
                    <h6 class="font-weight-bold">1. Indique el tipo de servicio que se le realizará al cliente:</h6>
                </td>
                <td>
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->tiposervicio ? 'R.V.' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tiposervicio ? 'E.V.' : '' }}</li>
                        <li> {{ 2 == $factibilidad->tiposervicio ? 'E. MON. MET.' : '' }}</li>
                        <li>{{ 3 == $factibilidad->tiposervicio ? $factibilidad->otro_tiposervicio : '' }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">2. Indique cómo se realizará el servicio:</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->comohacerservicio ? 'Recorrido' : '' }}</li>
                        <li> {{ 1 == $factibilidad->comohacerservicio ? 'Negocio del cliente' : '' }}</li>
                        <li> {{ 2 == $factibilidad->comohacerservicio ? 'En unidad blindada' : '' }}</li>
                    </ul>
                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">3. Día y horario de servicio:</h6>
                </td>
                {{-- <td> {{ $factibilidad->horario->name }}</td> --}}
                <td>LUN A VIE 18:0 A 20:00</td>
            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">Numero de personas que se requieren para el servicio:</h6>
                </td>
                <td> {{ $factibilidad->personalparaservicio }}</td>
            </tr>
            <tr>
                <td>

                    <h6 class="font-weight-bold">4. Tipo de construcción en donde se realizará el servicio:</h6>
                </td>
                <td class="text-end">
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->tipoconstruccion ? 'Concreto' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tipoconstruccion ? 'Madera' : '' }}</li>
                        <li> {{ 2 == $factibilidad->tipoconstruccion ? 'Tabla roca' : '' }}</li>
                        <li> {{ 3 == $factibilidad->tipoconstruccion ? 'Vidrio' : '' }}</li>
                        <li> {{ 4 == $factibilidad->tipoconstruccion ? $factibilidad->tipoconstruccion : '' }}</li>
                    </ul>

                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">5. Nivel de protección con el que cuenta el lugar:</h6>
                </td>
                <td>
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->nivelproteccionlugar ? 'NIVEL I' : '' }}</li>
                        <li> {{ 1 == $factibilidad->nivelproteccionlugar ? 'NIVEL II' : '' }}</li>
                        <li> {{ 2 == $factibilidad->nivelproteccionlugar ? 'NIVEL III' : '' }}</li>
                        <li> {{ 3 == $factibilidad->nivelproteccionlugar ? 'NIVEL IV' : '' }}</li>
                    </ul>
                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">El perímetro de la instalación se encuentra bardeado</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li>{{ 0 == $factibilidad->perimetro ? 'No' : '' }}</li>
                        <li>{{ 1 == $factibilidad->perimetro ? 'Sí' : '' }}</li>
                    </ul>
                </td>

            </tr>
            {{-- 6 --}}
            <tr>
                <td>
                    <h6 class="font-weight-bold">6. Indique el número de accesos para llegar al lugar de la recolección
                        o
                        entrega de
                        valores:</h6>
                </td>
                <td class="text-end">
                    <h6 class="font-weight-bold">Peatonales:{{ $factibilidad->peatonales }}</h6>
                    <h6 class="font-weight-bold">Vehiculares:{{ $factibilidad->vehiculares }}</h6>

                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">7. Cuenta con control de accesos y registro de visitantes en bitácora:
                    </h6>
                </td>
                <td class="text-end">
                    <ul class="list-unstyled">
                        <li>{{ 0 == $factibilidad->ctrlacesos ? 'No' : '' }}</li>
                        <li>{{ 1 == $factibilidad->ctrlacesos ? 'Sí' : '' }}</li>
                    </ul>
                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">8. Cuenta con servicio de guardias de seguridad:</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->guardiaseg ? 'Propios' : '' }}</li>
                        <li> {{ 1 == $factibilidad->guardiaseg ? 'Seguridad privada' : '' }}</li>
                        <li> {{ 2 == $factibilidad->guardiaseg ? $factibilidad->otros_guardiaseg : '' }}</li>
                    </ul>

                </td>


            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">Armados:</h6>
                </td>
                <td class="text-end">

                    <ul class="list-unstyled">
                        <li>{{ 0 == $factibilidad->armados ? 'No' : '' }}</li>
                        <li>{{ 1 == $factibilidad->armados ? $factibilidad->corporacion_armados : '' }}</li>
                    </ul>
                </td>
            </tr>
            {{-- 9 --}}
            <tr>
                <td>
                    <h6 class="font-weight-bold">9. Las instalaciones del cliente cuentan con algún tipo de alarma:</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->alarma ? 'Botón de pánico, incendio, sismo.' : '' }}</li>
                        <li> {{ 1 == $factibilidad->alarma ? 'NINGUNA' : '' }}</li>
                    </ul>
                </td>


            </tr>
            {{-- 10 --}}
            <tr>
                <td>
                    <h6 class="font-weight-bold">10. El sistema de alarma transmite la señal a:</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->tiposenial ? 'Seguridad pública.' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tiposenial ? 'C.E.R.I.' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tiposenial ? 'Central alarmas' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tiposenial ? $factibilidad->otros_tiposenial : '' }}</li>
                    </ul>
                </td>


            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">11. Tiempo de respuesta que tiene para atender el llamado de
                        alarma:</h6>
                </td>
                <td>
                    {{ $factibilidad->tipoderespuesta }}
                </td>

            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">12. En caso de falla de energía eléctrica cuenta con:</h6>
                </td>
                <td class="text-end">

                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->tipodefalla ? 'Generador de luz de emergencia.' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tipodefalla ? 'Lámparas de iluminación de emergencia' : '' }}</li>
                        <li>{{ 1 == $factibilidad->tipodefalla ? 'Bloqueos de seguridad en puertas de área segura ' : '' }}
                        </li>
                    </ul>
                </td>


            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">13. Cuenta con cámaras de seguridad el establecimiento:</h6>
                </td>
                <td>

                    <ul class="list-unstyled">
                        <li>{{ 0 == $factibilidad->camaras ? 'CIRCUITO CERRADO DE TELEVISIÓN ' : '' }}</li>
                        <li> {{ 1 == $factibilidad->camaras ? 'VIDEOGRABACIÓN' : '' }}</li>
                        <li> {{ 1 == $factibilidad->camaras ? 'NINGUNA' : '' }}</li>
                    </ul>
                </td>


            </tr>
            {{-- 14 --}}
            <tr>
                <td>
                    <h6 class="font-weight-bold">14. Cuenta con cofre de seguridad operado por su personal o en
                        renta con PRO-BLM:</h6>
                </td>
                <td>
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->cofre ? 'Propio' : '' }}</li>
                        <li> {{ 1 == $factibilidad->cofre ? 'En renta' : '' }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="font-weight-bold">15. Mencione la siniestralidad (asaltos o intentos de asalto)
                        que ha sufrido el cliente, anotando fechas
                        aproximadas y en qué forma se realizaron:</h6>
                </td>
                <td class="text-end">
                    {{ $factibilidad->descripcion_asalto }}
                </td>


            </tr>
            <tr>
                <td class="text-end">
                    <h6 class="font-weight-bold">16. Indique el nombre del ejecutivo de ventas que
                        realizó la contratación del servicio:</h6>
                </td>
                <td>
                    nombre
                </td>


            </tr>
            <tr>
                <td class="text-end">
                    <h6 class="font-weight-bold">17. Evalúe la zona donde se encuentra ubicada la recolección
                        de valores, de acuerdo a los siguientes parámetros:</h6>
                </td>
                <td>
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->tipodezona ? 'De riesgo' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tipodezona ? 'Regular' : '' }}</li>
                        <li> {{ 1 == $factibilidad->tipodezona ? 'Segura' : '' }}</li>
                    </ul>
                </td>


            </tr>
            <tr>
                <td class="text-end">
                    <h6 class="font-weight-bold">18. Indique si es conveniente para SERVICIOS INTEGRADOS PRO-BLM
                        DE MÉXICO S.A. DE C.V., la realización del servicio en mención.</h6>
                </td>
                <td class="text-end">
                    <ul class="list-unstyled">
                        <li> {{ 0 == $factibilidad->conviene ? 'No' : '' }}</li>
                        <li>{{ 1 == $factibilidad->conviene ? 'Si' : '' }}</li>
                    </ul>
                </td>


            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="font-size:100%">
        <tbody>
            {{-- observaciones  --}}
            <tr>
                <td colspan="2" class="text-center">
                    <h6 class="font-weight-bold">Observaciones</h6>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-end">{{ $factibilidad->observaciones }}</td>
            </tr>
            {{-- imagenes  --}}

            <tr>
                <td colspan="2" class="text-center">
                    <h6 class="font-weight-bold">Fotografias del establecimiento.</h6>
                </td>
            </tr>
            @if ($factibilidad->image)
                @php
                    $rutaImagen = 'storage/documentos/' . $sucursal->cliente->rfc_cliente . '/fotografias_establecimiento/';

                @endphp
                @foreach ($factibilidad->image as $foto)
                    <tr>
                        <td colspan="2" class="text-center">
                            <img src="{{ asset($rutaImagen.$foto->imagen) }}" height="100" width="100" alt="" />
                        </td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>


</body>

</html>
