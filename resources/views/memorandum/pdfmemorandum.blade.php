<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEMORANDUM</title>
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
    .text-centero{
        text-align: center;
    }
</style>

<body>
<div>
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
</div>

    <table class="table table-sm small-text table-bordered mt-3">
        <thead>
            <tr>
                <th colspan="2" class="text-centero bg-dark text-white">Solicitud de Servicio</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="small-text">Razón Social</th>
                <td class="small-text">{{ $razon_social }}</td>
            </tr>
            <tr>
                <th class="small-text">RFC</th>
                <td class="small-text">{{ $rfc_cliente }}</td>
            </tr>
            <tr>
                <th class="small-text">Fecha Solicitud</th>
                <td class="small-text">{{ $fecha_solicitud }}</td>
            </tr>
            <tr>
                <th class="small-text">Grupo Comercial</th>
                <td class="small-text">{{ $grupo }}</td>
            </tr>
            <tr>
                <th class="small-text">Tipo de Solicitud</th>
                <td class="small-text">{{ $ctg_tipo_solicitud_id }}</td>
            </tr>
            <tr>
                <th class="small-text">Tipo de Servicio</th>
                <td class="small-text">{{ $ctg_tipo_servicio_id }}</td>
            </tr>
            <tr>
                <th class="small-text">Observaciones</th>
                <td class="small-text">{{ $observaciones }}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-sm small-text table-bordered mt-3">
        <thead class="bg-dark text-white">
            <tr>
                <th class="small-text">Sucursal</th>
                <th class="small-text">Remitente del Servicio</th>
                <th class="small-text">Descripción del Servicio</th>
                <th class="small-text">Horario de Entrega</th>
                <th class="small-text">Día de Entrega</th>
                <th class="small-text">Horario de Servicio</th>
                <th class="small-text">Consignatario</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sucursales as $sucursal)
                @php
                    // Variable para verificar si hay memo asociado a la sucursal
                    $hasMemo = false;
                @endphp
                @foreach ($memo_servicio as $memo)
                    @if ($memo->sucursal_servicio->sucursal_id == $sucursal->id)
                        @php
                            $hasMemo = true;
                        @endphp
                        <tr>
                            <td class="small-text">{{ $sucursal->sucursal }}</td>
                            <td class="small-text">{{ $sucursal->direccion . ' ' . $sucursal->cp->cp . ' ' . $sucursal->cp->estado->name }}</td>
                            <td class="small-text">{{ $memo->sucursal_servicio->servicio->ctg_servicio->descripcion }}</td>
                            <td class="small-text">{{ $memo->hora_entrega->name }}</td>
                            <td class="small-text">{{ $memo->dia_entrega->name }}</td>
                            <td class="small-text">{{ $memo->hora_servicio->name }}</td>
                            <td class="small-text">{{ $memo->consignatario->name }}</td>
                        </tr>
                    @endif
                @endforeach
                @if (!$hasMemo)
                    <tr>
                        <td class="small-text">{{ $sucursal->sucursal }}</td>
                        <td class="small-text">{{ $sucursal->direccion . ' ' . $sucursal->cp->cp . ' ' . $sucursal->cp->estado->name }}</td>
                        <td colspan="5" class="small-text">Sin servicio asociado</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    
    
    @php
    // contador para las firmas
    $no_firmas = 0;
@endphp
<h6>VALIDACIÓN</h6>
<table class="table table-sm small-text table-hover table-bordered" width="100%" cellspacing="0" style="font-size:100%">
    <thead class="table-secondary">
        <tr>
            <th class="small-text" colspan="2">VENTAS</th>
            <th class="small-text" colspan="2">OPERACIONES</th>
            <th class="small-text" colspan="2">BOVEDA</th>
            <th class="small-text" colspan="2">PROCESO</th>
        </tr>
    </thead>
    <tbody>
        @php
            $tiene_firma = [];
        @endphp

        <tr>
        @foreach ([1, 2, 3, 4] as $IdArea)
            @php
                $found = false; // Flag para verificar si se encontró una firma
            @endphp
            
            @foreach ($firmas as $firma)
                @if ($IdArea == $firma->revisor_areas->area->id && !in_array($IdArea, $tiene_firma))
                    <td class="small-text" colspan="2">
                        <span style="display:inline-block; width:10px; height:10px; background-color: green; border-radius: 50%; margin-right: 5px;"></span>
                        {{ $firma->revisor_areas->area->name }}
                    </td>
                    @php
                        $tiene_firma[] = $IdArea; // Agrega IdArea a la lista de áreas que ya tienen firma
                        $no_firmas++;
                        $found = true; // Marca que se encontró una firma
                    @endphp
                    @break; // Sale del bucle una vez que se encuentra la firma
                @endif
            @endforeach
            
            @unless ($found)
                <td class="small-text" colspan="2">
                    <span style="display:inline-block; width:10px; height:10px; background-color: orange; border-radius: 50%; margin-right: 5px;"></span>Aún no validado {{$IdArea}}
                </td>
            @endunless
        @endforeach
        </tr>
    </tbody>
    <thead class="table-secondary">
        <tr>
            <th class="small-text" colspan="2">CONTABILIDAD</th>
            <th class="small-text" colspan="2">FACTURACIÓN</th>
            <th class="small-text" colspan="2">COBRANZA</th>
            <th class="small-text" colspan="2">Vo Bo GERENCIA</th>
        </tr>
    </thead>
    <tbody>
        @php
            $tiene_firma = [];
        @endphp

        <tr>
        @foreach ([5, 6, 7, 9] as $IdArea)
            @php
                $found = false; // Flag para verificar si se encontró una firma
            @endphp
            
            @foreach ($firmas as $firma)
                @if ($IdArea == $firma->revisor_areas->area->id && !in_array($IdArea, $tiene_firma))
                    <td class="small-text" colspan="2">
                        <span style="display:inline-block; width:10px; height:10px; background-color: green; border-radius: 50%; margin-right: 5px;"></span>
                        {{ $firma->revisor_areas->area->name }}
                    </td>
                    @php
                        $tiene_firma[] = $IdArea; // Agrega IdArea a la lista de áreas que ya tienen firma
                        $no_firmas++;
                        $found = true; // Marca que se encontró una firma
                    @endphp
                    @break; // Sale del bucle una vez que se encuentra la firma
                @endif
            @endforeach
            
            @unless ($found)
                <td class="small-text" colspan="2">
                    <span style="display:inline-block; width:10px; height:10px; background-color: orange; border-radius: 50%; margin-right: 5px;"></span>Aún no validado
                </td>
            @endunless
        @endforeach
        </tr>
    </tbody>
</table>


    
</div>
</body>

</html>
