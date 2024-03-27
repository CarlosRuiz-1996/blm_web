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
                <a href="exportPDF_Cotizacion.php?id=" target="_blank">
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
                            @php $total=0; @endphp
                            @foreach ($cotizacion->cotizacion_servicio as $servicio)
                                <tr>
                                    <td>{{ $servicio->servicios->id }}</td>
                                    <td>{{ $servicio->servicios->ctg_servicio->descripcion }}</td>
                                    <td>{{ $servicio->servicios->ctg_servicio->unidad }}</td>
                                    <td>{{ $servicio->servicios->cantidad }}</td>
                                    <td>${{ $servicio->servicios->precio_unitario }}</td>
                                    <td>${{ $servicio->servicios->subtotal }}</td>
                                    @php $total += $servicio->servicios->subtotal @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"></td>
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
