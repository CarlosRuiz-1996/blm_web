<div>
    <div class="row g-3">
        <div class="col-md-12 mb-3">
            <button class="btn btn-success btn-block" title="Comprar efectivo" data-toggle="modal"
                data-target="#compraEfectivo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="40px"
                    height="25px">
                    <path
                        d="M2 6C2 5.44772 2.44772 5 3 5H19C19.5523 5 20 5.44772 20 6V8H3.5C2.94772 8 2.5 8.44772 2.5 9V15H3.5V16H20V18C20 18.5523 19.5523 19 19 19H3C2.44772 19 2 18.5523 2 18V6Z" />
                    <path
                        d="M12.5 12C13.3284 12 14 11.3284 14 10.5C14 9.67157 13.3284 9 12.5 9C11.6716 9 11 9.67157 11 10.5C11 11.3284 11.6716 12 12.5 12Z" />
                    <path d="M21 10H23V12H21V14H19V12H17V10H19V8H21V10Z" />
                    <path
                        d="M19 16C19.5523 16 20 15.5523 20 15C20 14.4477 19.5523 14 19 14C18.4477 14 18 14.4477 18 15C18 15.5523 18.4477 16 19 16Z" />
                    <path
                        d="M5 7C5.55228 7 6 7.44772 6 8C6 8.55228 5.55228 9 5 9C4.44772 9 4 8.55228 4 8C4 7.44772 4.44772 7 5 7Z" />
                    <path
                        d="M5 15C5.55228 15 6 14.5523 6 14C6 13.4477 5.55228 13 5 13C4.44772 13 4 13.4477 4 14C4 14.5523 4.44772 15 5 15Z" />
                </svg>
            </button>
        </div>
        <div class="col-md-12">

            <button class="btn btn-info btn-block" title="Agregar servicio" data-toggle="modal"
                data-target="#addServicio">

                <i class="fa fa-fw fa-car"></i>
                <i class="fas fa-plus"></i>
            </button>

        </div>
    </div>


    {{-- efectivo --}}
    <div class="modal fade" wire:ignore.self id="compraEfectivo" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Compra de efectivo.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="form-group  col-md-5" wire:ignore>

                            <x-select-select2 label="Clientes:" placeholder="" wire-model="cliente" required
                                classSelect2="cliente_compra" modalName='compraEfectivo'>
                                @if ($clientes && count($clientes) > 0)
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">

                                            {{ $cliente->rfc_cliente }}-{{ $cliente->razon_social }}</option>
                                    @endforeach
                                @else
                                    <option value="">Sin clientes</option>
                                @endif
                            </x-select-select2>
                        </div>
                        <div class="form-group  col-md-5">
                            <x-input-validadolive label="Monto " placeholder="Monto "
                                wire-model="monto" type="number" />
                        </div>

                        <div class="form-group  col-md-2" style="margin-top: 32px">
                            <button type="button" class="btn btn-info" wire:click='addCompra' wire:loading.remove>Agregar</button>
                        </div>
                    </div>
                    @if ($compras)
                        <table class="table table-striped">
                            <thead class="table-info">
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Eliminar</th>
                            </thead>
                            <tbody>
                                @foreach ($compras as $index => $compra)
                                    <tr>
                                        <td>{{ $compra['cliente_name'] }}</td>
                                        <td>

                                            {{ number_format($compra['monto'], 2, '.', ',') }} MXN
                                        </td>
                                        <td>
                                            <button class="btn btn-danger"
                                                wire:click="removeCompra({{ $index }})">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row g-3">
                            <div class="form-group  col-md-3">

                                <x-input-validado-date label="Fecha" placeholder="Fecha" wire-model="fecha"
                                    type="date" />

                            </div>
                            <div class="form-group  col-md-3">
                                <x-select-validado label="Cajero:" placeholder="" wire-model="cajero_id" required>
                                    @foreach ($consignatarios as $consignatario)
                                        <option value="{{ $consignatario->id }}">

                                            {{ $consignatario->name }}</option>
                                    @endforeach

                                </x-select-validado>
                            </div>
                        </div>
                    @else
                        <span class="progress-description ">
                            <b class="text-secondary"> No hay servicios agregados.</b>
                        </span>
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($compras)
                        <button type="button" class="btn btn-info" wire:click='finalizarCompra'
                            wire:loading.remove>Aceptar</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Agregar servicios --}}
    <div class="modal fade" wire:ignore.self id="addServicio" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Servicios.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="form-group  col-md-4" wire:ignore>

                            <x-select-select2 label="Clientes:" placeholder="" wire-model="cliente" required
                                classSelect2="cliente_servicio" modalName='addServicio'>
                                @if ($clientes && count($clientes) > 0)
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">

                                            {{ $cliente->rfc_cliente }}-{{ $cliente->razon_social }}</option>
                                    @endforeach
                                @else
                                    <option value="">Sin clientes</option>
                                @endif
                            </x-select-select2>
                        </div>
                        <div class="form-group  col-md-4">

                            <x-select-validadolive label="Servicio:" placeholder="" wire-model="servicio" required>
                                @if ($servicios_cliente && count($servicios_cliente) > 0)
                                    @foreach ($servicios_cliente as $servicio)
                                        <option value="{{ $servicio->id }}">

                                            {{ $servicio->ctg_servicio->descripcion }}</option>
                                    @endforeach
                                @else
                                    <option value="">Sin Servicios</option>
                                @endif
                            </x-select-validadolive>
                        </div>
                        <div class="form-group  col-md-4">

                            <x-input-validado-date label="Fecha" placeholder="Fecha" wire-model="fecha"
                                type="date" />

                        </div>
                        <div class="form-group  col-md-3">
                            <x-input-validadolive label="Monto " placeholder="Monto "
                                wire-model="monto" type="number" />
                        </div>
                        <div class="form-group  col-md-3">
                            <x-input-validadolive label="Papeleta/Folio" placeholder="Folio del servicio"
                                wire-model="papeleta" type="text" />
                        </div>
                        <div class="form-group  col-md-3">
                            <x-select-validadolive label="Tipo:" placeholder="" wire-model="tipo" required>
                                <option value="1">Entrega</option>
                                <option value="2">Recolecta</option>

                            </x-select-validadolive>
                        </div>
                        <div class="form-group  col-md-2" style="margin-top: 32px">
                            <button type="button" class="btn btn-info" wire:click='addServicios' wire:loading.remove>Agregar</button>
                        </div>
                    </div>
                    @if ($servicios)
                        <table class="table table-striped">
                            <thead class="table-info">
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Tipo de servicio</th>
                                <th>Papeleta</th>
                                <th>Fecha</th>
                                <th>Eliminar</th>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $index => $servicio)
                                    <tr>
                                        <td>{{ $servicio['cliente_name'] }}</td>
                                        <td>

                                            {{ number_format($servicio['monto'], 2, '.', ',') }} MXN
                                        </td>
                                        <td>{{ $servicio['tipo_servicio'] }}</td>
                                        <td>{{ $servicio['papeleta'] }}</td>
                                        <td>{{ $servicio['fecha'] }}</td>
                                        <td>
                                            <button class="btn btn-danger"
                                                wire:click="removeService({{ $index }})">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <span class="progress-description ">
                            <b class="text-secondary"> No hay servicios agregados.</b>
                        </span>
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($servicios)
                        <button type="button" class="btn btn-info" wire:click='finalizarServicios'
                            wire:loading.remove>Aceptar</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
       
        // $('.cliente_compra').on('change', function() {
        //     var cliente = $(this).val();
        //     @this.set('cliente', cliente)
        //     // Aquí puedes hacer lo que necesites con el valor seleccionado, como enviarlo a través de Livewire
        // });
        // $('.cliente_servicio').on('change', function() {
        //     var cliente = $(this).val();
        //     @this.set('cliente', cliente)
        //     // @this.dispatch('update-cliente',cliente);
        // });
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('alert', function([message]) {

                $("#compraEfectivo").modal('hide');
                Swal.fire({
                    icon: message[1],
                    title: message[0],
                    showConfirmButton: false,
                    timer: 3000
                });
            });

            //resetea el select2 al placeholder
            Livewire.on('resetSelect2', function(){
                $('.cliente_compra').val('').trigger('change');
                $('.cliente_servicio').val('').trigger('change');

            });
        });
    </script>
@endpush
