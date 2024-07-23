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
                        <div class="col-md-12 mb-3">
                            <x-input-validadolive label="Cambio para:" :readonly="false"
                                placeholder="Ingresa a quien se le cambia el monto" wire-model="form.from_change"
                                wire-attribute="form.from_change" type="text" />

                        </div>
                        <div class="col-md-10 mb-3">
                            <x-input-validadolive
                                label="Monto a cambiar: $ {{ number_format($form->monto ? $form->monto : 0, 2, '.', ',') }} MXN"
                                :readonly="false" placeholder="Ingresa monto a cambiar" wire-model="form.monto"
                                wire-attribute="form.monto" type="number" />

                        </div>

                        <div class="col-md-2 mt-3">
                            <button class="btn btn-info" wire:click='add'>
                                agregar cambio
                            </button>
                        </div>
                        @foreach ($form->cambios as $index => $cambio)
                            <div class="col-md-6 mb-3">
                                <x-select-validadolive label="Tipo de moneda:" placeholder="Seleccione"
                                    :readonly="true" wire-model="form.cambios.{{ $index }}.denominacion"
                                    required>
                                    @foreach ($denominaciones as $denominacion)
                                        <option value="{{ $denominacion->id }}">{{ $denominacion->denominacion }}
                                        </option>
                                    @endforeach
                                </x-select-validadolive>
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-input-validadolive label="Monto:"
                                    wire-model="form.cambios.{{ $index }}.monto" type="number" />

                            </div>
                        @endforeach
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
