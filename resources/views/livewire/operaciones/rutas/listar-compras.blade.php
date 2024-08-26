<div>
    @if ($ruta->ruta_compra && count($ruta->ruta_compra))

        <div class="d-sm-flex align-items-center justify-content-between">

            <h1 class="ml-3">Compra de efectivo</h1>

        </div>

        {{-- Stop trying to control. --}}

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">

                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Detalles</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($ruta->ruta_compra as $ruta)

                                        @if($ruta->status_ruta_compra_efectivos!=4)
                                            <tr>

                                                
                                                <td>$ {{ number_format($ruta->compra->total, 2, '.', ',') }}

                                                <td>{{ $ruta->compra->fecha_compra }}</td>
                                                <td>
                                                    <button class="btn btn-primary m-2" data-toggle="modal"
                                                        data-target="#compra"
                                                        wire:click='showCompraDetail({{ $ruta->compra }})'>
                                                        Detalles

                                                    </button>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                          
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Modal vehiculos --}}
    <x-adminlte-modal wire:ignore.self id="compra" title="Detalles de la compra" theme="info" icon="fas fa-car"
        size='md' disable-animations>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-info">
                <tr>
                    <th>Banco/Consignatario</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @if ($readyToLoadModal)

                    @if ($compra_detalle && count($compra_detalle->detalles))
                        @foreach ($compra_detalle->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->consignatario->name }}</td>
                                <td>${{ number_format($detalle->monto, 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="8"> No hay movimientos</td>
                        </tr>
                    @endif
                @else
                    <tr class="text-center">
                        <td colspan="8">
                            <div class="spinner-border" role="status"></div>
                        </td>
                    </tr>

                @endif
            </tbody>
        </table>


    </x-adminlte-modal>
</div>
