@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')


    <table class="table " width="100%" cellspacing="0" style="font-size:100%">
        <tr>
            <td align="left">
                <a href="/ventas" title="ATRAS">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </td>
            <td align="center">
                <h1 class="font-weight-bold">Cotización - Detalle</h1>
            </td>
            <td align="right">
                <a href="{{route('cotizacion.pdf',$cotizacion)}}" target="_blank">
                    <i title="Descargar Archivo" style="color: red;" class="fas fa-file-pdf fa-2x" aria-hidden="true"></i>
                </a>
            </td>

        </tr>

    </table>
@stop
@section('content')
    <livewire:cliente-cabecera :cliente="$cotizacion->cliente" />

    <div>
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    @php $valorcolspan = "4"; @endphp
                    @php $total = 0; @endphp
                    @php $mostrarTablaNormal = true; @endphp
                    @foreach ($cotizacion->cotizacion_servicio as $servicio)
                        @if ($servicio->servicio->servicio_foraneo == 1)
                             @php $valorcolspan = "6"; @endphp
                            @php $mostrarTablaNormal = false; @endphp
                            <div class="alert alert-warning" role="alert">
                                Este servicio es foráneo.
                            </div>
                            <!-- Aquí puedes mostrar tu otra tabla para servicios foráneos -->
                            <!-- Agrega el código para la tabla de servicios foráneos aquí -->
                            <!-- Por ejemplo: -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>PDA</th>
                                        <th>NOMBRE DEL SERVICIO</th>
                                        <th>INICIO</th>
                                        <th>DESTINO</th>
                                        <th>CostoTotal/KM</th>
                                        <th>CostoTotal/Miles</th>
                                        <th>G/Operacion</th>
                                        <th>iva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $servicio->servicio->id }}</td>
                                        <td>Servicio Foraneo</td>
                                        <td>{{ $servicio->servicio->foraneo_inicio }}</td>
                                        <td>{{ $servicio->servicio->foraneo_destino }}</td>
                                        <td>${{ ($servicio->servicio->kilometros_costo * $servicio->servicio->kilometros) }}</td>
                                        <td>${{ ($servicio->servicio->miles_costo *  $servicio->servicio->miles) }}</td>
                                        <td>${{ $servicio->servicio->gastos_operaciones }}</td>
                                        <td>${{ $servicio->servicio->iva }}</td>
                                        @php $total += $servicio->servicio->subtotal; @endphp
                                    </tr>
                        @endif
                        @if ($mostrarTablaNormal)
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size:100%">
                                <thead>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">PDA</th>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">DESCRIPCIÓN</th>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">UNIDAD DE MEDIDA</th>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">CANTIDAD SOLICITADA</th>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">PRECIO UNITARIO</th>
                                    <th class="font-weight-bold" style="background-color: gray; color: white; ">TOTAL</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $servicio->servicio->id }}</td>
                                        <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                        <td>{{ $servicio->servicio->ctg_servicio->unidad }}</td>
                                        <td>{{ $servicio->servicio->cantidad }}</td>
                                        <td>${{ $servicio->servicio->precio_unitario }}</td>
                                        <td>${{ $servicio->servicio->subtotal }}</td>
                                        @php $total += $servicio->servicio->subtotal; @endphp
                                    </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="{{$valorcolspan}}"></td>
                        <td style="background-color: gray; color: white; " class="text-center font-weight-bold">Total:</td>
                        <td style="background-color: gray; color: white; " class="text-center font-weight-bold">${{ $total }}</td>
                    </tr>
                </tbody>
            </table>
                </div>
            </div>
        </div>
        
    </div>
@stop
