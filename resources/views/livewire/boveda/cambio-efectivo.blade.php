<div wire:init='loadServicios'>

    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-2"> Cambio de Efectivo
        </h1>

        <a href="#" data-toggle="modal" class="btn btn-info" data-target="#detalleModalEnvases">Cambiar
            efectivo</a>

    </div>
    <div class="table-responsive card">
        <table class="table  table-hover"> <!--table-striped-->


            <!-- Encabezados de la tabla -->
            <thead class="table-info">
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>A quien se le cambia</th>
                    <th>Monto a cambiar</th>
                    <th>Fecha del cambio</th>
                    <th>Quien realiza cambio</th>

                </tr>
            </thead>
            <!-- Cuerpo de la tabla -->
            <tbody x-data="{ openRow: false }">
                @if (count($cambios))
                    @foreach ($cambios as $cambio)
                        <tr @click="openRow === {{ $cambio->id }} ? openRow = false : openRow = {{ $cambio->id }}">
                            <td>{{ $cambio->from_change }}</td>
                            <td>
                                $ {{ number_format($cambio->monto, 2, '.', ',') }} MXN
                            </td>
                            <td>{{ $cambio->created_at }}</td>
                            <td>{{ $cambio->empleado->user->name }}</td>



                        </tr>
                        <tr class="table-info" x-show="openRow === {{ $cambio->id }}" >
                            <th colspan="2" >
                                Denominación
                            </th>
                            <th colspan="2">
                                Montos
                            </th>
                        </tr>
                        @foreach ($cambio->denominacions as $denominacion)
                            <tr class="" x-show="openRow === {{ $cambio->id }}">
                                <td colspan="2">

                                    {{
                                        $denominacion->tipo_denominacion->tipo_moneda->name .' de '.
                                        $denominacion->tipo_denominacion->denominacion }}</td>
                                <td colspan="2">

                                    $ {{ number_format($denominacion->monto, 2, '.', ',') }} MXN
                                </td>


                            </tr>
                        @endforeach
                    @endforeach
                @else
                    @if ($readyToLoad)
                        <tr>
                            <td colspan="8">No hay datos disponibles</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                </div>
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    @if ($cambios && $cambios->hasPages())
        <div class="col-md-12 text-center">
            {{ $cambios->links() }}
        </div>
    @endif


    <!--modal envases de cargar-->
    <div class="modal fade" id="detalleModalEnvases" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Capturar cambio de efectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click='clean'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-input-validadolive label="Cambio para:" :readonly="false"
                                placeholder="Ingresa a quien se le cambia el monto" wire-model="form.from_change"
                                wire-attribute="form.from_change" type="text" />

                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validadolive
                                label="Cantidad Total a Cambiar:$ {{ number_format($cantidadTotal ? $cantidadTotal : 0, 2, '.', ',') }} MXN"
                                :readonly="false" placeholder="Ingrese la cantidad total a cambiar" wire-model="cantidadTotal"
                                wire-attribute="cantidadTotal" type="number" />

                        </div>
                                <!-- Tipo de Cambio (Billetes o Monedas) -->
                                    <div class="col-md-12">
                                        <label for="tipoCambio">Tipo de Cambio:</label>
                                        <select id="tipoCambio" wire:model.live="tipoCambio" class="form-control">
                                            <option value="">Seleccione el tipo de cambio</option>
                                            <option value="moneda_a_billete">Moneda a Billete</option>
                                            <option value="moneda_a_bolsas">Moneda a Bolsas</option>
                                            <option value="moneda_a_menor_denominacion">Moneda a Menor Denominación</option>
                                            <option value="billete_a_moneda">Billete a Moneda</option>
                                            <option value="billete_a_menor_denominacion">Billete a Billete de Menor Denominación</option>
                                        </select>
                                    </div>
                        
                                <!-- Tipo de Cambio de Monedas a Bolsas -->
                                @if($tipoCambio == 'moneda_a_bolsas')
                                        <div class="col-md-12 mt-2">
                                            <label for="tipoCambioMonedas">Cambio de Monedas a:</label>
                                            <select id="tipoCambioMonedas" wire:model.live="tipoCambioMonedas" class="form-control">
                                                <option value="">Seleccione el cambio</option>
                                                <option value="billetes">Billetes</option>
                                            </select>
                                        </div>
                        
                                    <!-- Cantidad por Denominación Ingresada Manualmente para Bolsas -->
                                    @if($tipoCambioMonedas == 'bolsas')
                                            <div class="col-md-12 mt-2">
                                                <label for="denominaciones">Denominaciones para Bolsas:</label>
                                                @foreach($bolsasDisponibles as $bolsa)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6 mt-1">
                                                            <label>{{ $bolsa->denominacion }}</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" wire:model.live="cambioBolsas.{{ $bolsa->id }}" class="form-control" placeholder="Cantidad" min="0">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                    @endif
                                    @if($tipoCambioMonedas == 'monedas')
                                            <div class="col-md-12 mt-2">
                                                <label for="denominaciones">Denominaciones:</label>
                                                @foreach($monedasDisponibles as $moneda)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6 mt-1">
                                                            <label>{{ $moneda->denominacion }} (Moneda)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" wire:model.live="denominacionesPermitidas.{{ $moneda->id }}" class="form-control" placeholder="Cantidad de monedas" min="0">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                    @endif
                                    @if($tipoCambioMonedas == 'billetes')
                                            <div class="col-md-12 mt-2">
                                                <label for="denominaciones">Denominaciones:</label>
                                                @foreach($monedasDisponibles as $moneda)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6 mt-1">
                                                            <label>{{ $moneda->denominacion }} (Moneda)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" wire:model.live="cambioBolsas.{{ $moneda->id }}" class="form-control" placeholder="Cantidad de bolsas" min="0">
                                                        </div>
                                                    </div>
                                                @endforeach
                                                 <!-- Suma de las denominaciones -->
                                                <h3 class="mt-4">Suma Total Calculada de bolsas: ${{ number_format($sumaTotalbolsas, 2) }}</h3>
                                            </div>
                                            <div class="col-md-12 mt-4">
                                                 <!-- Suma de las denominaciones -->
                                                <h3 class="mt-4">Cambiar por:</h3>
                                                <label for="billetes">Denominaciones de Billetes:</label>
                                                @foreach($billetesDisponibles as $billete)
                                                    <div class="row mb-2">
                                                        <div class="col-md-6 mt-1">
                                                            <label>{{ $billete->denominacion }} (Billete)</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" wire:model.live="denominacionesPermitidas.{{ $billete->id }}" class="form-control" placeholder="Cantidad de billetes" min="0">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                    @endif
                                @elseif($tipoCambio == 'billete_a_moneda' || $tipoCambio == 'moneda_a_menor_denominacion')
                                    <!-- Cantidad por Denominación Ingresada Manualmente para Monedas -->
                                        <div class="col-md-12 mt-2">
                                            <label for="denominaciones">Denominaciones:</label>
                                            @foreach($monedasDisponibles as $moneda)
                                                <div class="row mb-2">
                                                    <div class="col-md-6 mt-1">
                                                        <label>{{ $moneda->denominacion }} (Moneda)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" wire:model.live="denominacionesPermitidas.{{ $moneda->id }}" class="form-control" placeholder="Cantidad de moneda" min="0">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                @elseif($tipoCambio == 'moneda_a_billete' || $tipoCambio == 'billete_a_menor_denominacion')
                                    <!-- Cantidad por Denominación Ingresada Manualmente para Billetes -->
                                        <div class="col-md-12 mt-2">
                                            <label for="denominaciones">Denominaciones:</label>
                                            @foreach($billetesDisponibles as $billete)
                                                <div class="row mb-2">
                                                    <div class="col-md-6 mt-1">
                                                        <label>{{ $billete->denominacion }} (Billete)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" wire:model.live="denominacionesPermitidas.{{ $billete->id }}" class="form-control" placeholder="Cantidad de billetes" min="0">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                @endif

                                <!-- Mensaje de error si la suma no es correcta -->
                                @if($sumaIncorrecta)
                                <div class="col-md-12">
                                    <div class="alert alert-danger mt-3">
                                        La suma de los billetes o monedas no coincide con la cantidad total ingresada.
                                    </div>
                                </div>
                                @endif
                            </form>
                        
                            <!-- Suma de las denominaciones -->
                            <h3 class="mt-4">Suma Total Calculada: ${{ number_format($sumaTotal, 2) }}</h3>
                        </div>
                        
                    </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" wire:click='clean' data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" wire:click="guardar">Aceptar</button>


                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alert', function(params) {
                    const msg = params[0].msg;
                    const tipomensaje = params[1].tipomensaje;
                    Swal.fire({
                        icon: tipomensaje,
                        title: msg,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    if (tipomensaje == 'success') {
                        $('#detalleModalEnvases').modal('hide');
                    }
                });
            });
        </script>
    @endpush
</div>
