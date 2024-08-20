<div>
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 for="">BANCOS</h3>
    </div>

    <div class="" wire:init='loadBancos'>
        <ul class="nav nav-tabs" wire:ignore.self id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="serv_banc-tab" data-toggle="pill" href="#serv_banc" role="tab"
                    aria-controls="serv_banc" aria-selected="true">SERVICIOS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="compra_efectivo-tab" data-toggle="pill" href="#compra_efectivo" role="tab"
                    aria-controls="compra_efectivo" aria-selected="false">COMPRA DE EFECTIVO</a>
            </li>
        </ul>

        <div class="tab-content" wire:ignore.self id="custom-tabs-one-tabContent">
            {{-- servicios --}}
            <div class="tab-pane fade show active " id="serv_banc" role="tabpanel" aria-labelledby="serv_banc-tab">
                <div class="row">

                    <div class="card col-md-12">

                        @if (count($servicio_bancos))

                            <table class="table table-bordered table-striped table-hover mt-3">
                                <thead class="table-info">
                                    <tr>
                                        <th>Razon Social</th>
                                        <th>Servicio</th>
                                        <th style="width: 130px">Monto</th>
                                        <th>Papeleta</th>
                                        <th>Fecha de entrega</th>
                                        <th>Tipo servicio</th>
                                        <th>Estatus</th>
                                        <th style="width: 130px">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($servicio_bancos as $servicio)
                                        <tr>
                                            {{-- <td>{{ $cliente->id }}</td> --}}
                                            <td>{{ $servicio->servicio->cliente->razon_social ?? 'N/A' }}</td>
                                            <td>{{ $servicio->servicio->ctg_servicio->descripcion ?? 'N/A' }}</td>
                                            <td>$ {{ number_format($servicio->monto, 2, '.', ',') }}
                                            </td>
                                            <td>
                                                {{ $servicio->papeleta }}
                                            </td>
                                            <td>
                                                {{ $servicio->fecha_entrega }}
                                            </td>
                                            <td>
                                                {{ $servicio->tipo_servicio == 1 ? 'ENTREGA' : 'RECOLECCIÓN' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $servicio->status_bancos_servicios == 1 ? 'secondary' : 'success' }}">
                                                    {{ $servicio->status_bancos_servicios == 1 ? 'PENDIENTE' : 'FINALIZADO' }}

                                                </span>
                                            </td>
                                            <td>
                                                @if ($servicio->status_bancos_servicios == 1)
                                                    <button class="btn btn-info"
                                                        wire:click='findRutaServicio({{ $servicio }})'
                                                        data-target="#modalAddServicio" data-toggle="modal">
                                                        Agregar servicio
                                                    </button>
                                                @else
                                                    <button class="btn btn-info"
                                                        wire:click='findRutaServicio({{ $servicio }})'
                                                        data-target="#modalDetailServicio" data-toggle="modal">
                                                        Detalles
                                                    </button>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>


                            @if ($servicio_bancos->hasPages())
                                <div class="col-md-12 text-center">
                                    {{ $servicio_bancos->links() }}
                                </div>
                            @endif
                        @else
                            @if ($readyToLoad)
                                <h3 class="col-md-12 text-center">No hay datos disponibles</h3>
                            @else
                                <!-- Muestra un spinner mientras los datos se cargan -->
                                <div class="col-md-12 text-center">
                                    <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                        role="status">
                                        {{-- <span class="visually-hidden">Loading...</span> --}}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- compra --}}
            <div class="tab-pane fade" id="compra_efectivo" role="tabpanel" aria-labelledby="compra_efectivo-tab">


                <div class="card col-md-12 ">

                    @if (count($compras))

                        <table class="table table-bordered table-striped table-hover mt-3">
                            <thead class="table-info">
                                <tr>
                                    <th>Total</th>
                                    {{-- <th>Banco</th> --}}
                                    <th>Fecha solicitada</th>
                                    <th>Estatus</th>
                                    <th style="width: 180px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compras as $compra)
                                    <tr>
                                        <td>$ {{ number_format($compra->total, 2, '.', ',') }}
                                        {{-- <td>{{ $compra->consignatario->name }}</td> --}}
                                        <td>{{ $compra->fecha_compra }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $servicio->status_compra_efectivos == 1 ? 'secondary' : 'success' }}">

                                                {{ $compra->status_compra_efectivos == 1 ? 'PENDIENTE' : 'FINALIZADO' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($compra->status_compra_efectivos == 1)
                                                <button class="btn btn-info" data-toggle="modal"
                                                    wire:click="asignarCompraEfectivo({{ $compra }})"
                                                    data-target="#modalAddRutaCompra">Asignar a ruta</button>
                                            @else
                                                <button class="btn btn-info" data-toggle="modal"
                                                    wire:click="asignarCompraEfectivo({{ $compra }})"
                                                    data-target="#modalDetailRutaCompra">Detalles</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>


                        @if ($compras->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $compras->links() }}
                            </div>
                        @endif
                    @else
                        @if ($readyToLoad)
                            <h3 class="col-md-12 text-center">No hay datos disponibles</h3>
                        @else
                            <!-- Muestra un spinner mientras los datos se cargan -->
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                    {{-- <span class="visually-hidden">Loading...</span> --}}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- compras --}}
    <div class="modal fade" wire:ignore.self id="modalAddRutaCompra" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Ruta</h5>
                    <button wire:click='clean' type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @if ($compra)
                        <label for="">Total de la compra</label>
                        <input class="form-control" disabled
                            value="$ {{ number_format($compra->total, 2, '.', ',') }}" />

                        {{-- <label for="">Consignatario/Banco</label>
                        <input class="form-control" disabled value="{{ $compra->consignatario->name }}" /> --}}

                        <label for="">Fecha solicitud</label>
                        <input class="form-control" disabled value="{{ $compra->fecha_compra }}" />
                    @endif

                    <x-select-validadolive label="Dia de la ruta:" placeholder="Selecciona un dia"
                        wire-model="ctg_ruta_dia_id" required>

                        @if ($dias)
                            @foreach ($dias as $dia)
                                <option value="{{ $dia->id }}">{{ $dia->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Esperando...</option>
                        @endif

                    </x-select-validadolive>
                    @if ($ctg_ruta_dia_id)
                        @if (count($rutas_dia))
                            <x-select-validadolive label="Ruta:" placeholder="Selecciona una ruta"
                                wire-model="ruta_id" required>
                                @foreach ($rutas_dia as $ruta)
                                    <option value="{{ $ruta->id }}">{{ $ruta->nombre->name }}-
                                        {{ $ruta->status_ruta == 1 ? 'EN PLANEACIÓN' : 'EN RUTA' }}
                                    </option>
                                @endforeach
                            </x-select-validadolive>
                        @else
                            <p>No hay rutas disponibles para este dia.
                                <a href="{{ route('ruta.gestion', 1) }}">Agregar
                                    Ruta</a>
                            </p>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" wire:click="$dispatch('confirm',1)"
                        wire:loading.remove>Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="modalDetailRutaCompra" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Ruta</h5>
                    <button wire:click='clean' type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @if ($compra)
                        <label for="">Total de la compra</label>
                        <input class="form-control" disabled
                            value="$ {{ number_format($compra->total, 2, '.', ',') }}" />

                        {{-- <label for="">Consignatario/Banco</label> --}}
                        {{-- <input class="form-control" disabled value="{{ $compra->consignatario->name }}" /> --}}

                        <label for="">Fecha solicitud</label>
                        <input class="form-control" disabled value="{{ $compra->fecha_compra }}" />

                        @if ($compra->ruta_compra)
                            <label for="">Ruta asignada</label>
                            <input class="form-control" disabled
                                value="{{ $compra->ruta_compra->ruta->nombre->name }}" />
                        @endif
                    @endif


                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- servicios --}}
    <div class="modal fade" wire:ignore.self id="modalAddServicio" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar servicio a una ruta</h5>
                    <button wire:click='clean' type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @if ($banco_servicio)
                            <div class="col-6">
                                <label for="">Cliente</label>
                                <input class="form-control" disabled
                                    value="{{ $banco_servicio->servicio->cliente->razon_social }}" />
                            </div>
                            <div class="col-6">

                                <label for="">Servicio</label>
                                <input class="form-control" disabled
                                    value="{{ $banco_servicio->servicio->ctg_servicio->descripcion }}" />
                            </div>
                            <div class="col-3">

                                <label for="">Monto</label>
                                <input class="form-control" disabled
                                    value="$ {{ number_format($banco_servicio->monto, 2, '.', ',') }}" />
                            </div>
                            <div class="col-3">

                                <label for="">Papeleta</label>
                                <input class="form-control" disabled value="{{ $banco_servicio->papeleta }}" />
                            </div>
                            <div class="col-3">

                                <label for="">Fecha solicitada</label>
                                <input class="form-control" disabled value="{{ $banco_servicio->fecha_entrega }}" />
                            </div>
                            <div class="col-3">

                                <label for="">Tipo de servicio</label>
                                <input class="form-control" disabled
                                    value="{{ $banco_servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolecta' }}" />
                            </div>
                            <div class="col-3">

                                <label for="">Ruta actual</label>
                                <input class="form-control" disabled
                                    value="{{ $banco_servicio->servicio->ruta_servicio
                                        ? $banco_servicio->servicio->ruta_servicio->ruta->dia->name .
                                            ' - ' .
                                            $banco_servicio->servicio->ruta_servicio->ruta->nombre->name
                                        : 'Sin ruta asignada' }}" />
                            </div>
                            <div class="col-4">

                                <x-select-validadolive label="Dia de la ruta:"
                                    placeholder="Selecciona el dia para la ruta" wire-model="ctg_ruta_dia_id"
                                    required>

                                    @if ($dias)
                                        @foreach ($dias as $dia)
                                            <option value="{{ $dia->id }}">{{ $dia->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Esperando...</option>
                                    @endif

                                </x-select-validadolive>
                            </div>
                            <div class="col-3">

                                @if ($ctg_ruta_dia_id)
                                    @if (count($rutas_dia))
                                        <x-select-validadolive label="Nueva ruta ruta:"
                                            placeholder="Selecciona una ruta" wire-model="ruta_id" required>
                                            @foreach ($rutas_dia as $ruta)
                                                <option value="{{ $ruta->id }}">{{ $ruta->nombre->name }}-
                                                    {{ $ruta->status_ruta == 1 ? 'EN PLANEACIÓN' : 'EN RUTA' }}
                                                </option>
                                            @endforeach
                                        </x-select-validadolive>
                                    @else
                                        <p>No hay rutas disponibles para este dia.
                                            <a href="{{ route('ruta.gestion', 1) }}">Agregar
                                                Ruta</a>
                                        </p>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="col-md-12 text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                    {{-- <span class="visually-hidden">Loading...</span> --}}
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" wire:click="$dispatch('confirm',2)">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" wire:ignore.self id="modalDetailServicio" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
                    <button wire:click='clean' type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @if ($banco_servicio)
                        <label for="">Cliente</label>
                        <input class="form-control" disabled
                            value="{{ $banco_servicio->servicio->cliente->razon_social }}" />


                        <label for="">Servicio</label>
                        <input class="form-control" disabled
                            value="{{ $banco_servicio->servicio->ctg_servicio->descripcion }}" />


                        <label for="">Monto</label>
                        <input class="form-control" disabled
                            value="$ {{ number_format($banco_servicio->monto, 2, '.', ',') }}" />

                        <label for="">Papeleta</label>
                        <input class="form-control" disabled value="{{ $banco_servicio->papeleta }}" />


                        <label for="">Fecha solicitada</label>
                        <input class="form-control" disabled value="{{ $banco_servicio->fecha_entrega }}" />


                        <label for="">Tipo de servicio</label>
                        <input class="form-control" disabled
                            value="{{ $banco_servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolecta' }}" />

                        <label for="">Ruta actual</label>
                        <input class="form-control" disabled
                            value="{{ $banco_servicio->servicio->ruta_servicio
                                ? $banco_servicio->servicio->ruta_servicio->ruta->dia->name .
                                    ' - ' .
                                    $banco_servicio->servicio->ruta_servicio->ruta->nombre->name
                                : 'Sin ruta asignada' }}" />
                    @else
                        <div class="col-md-12 text-center">
                            <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                role="status">
                                {{-- <span class="visually-hidden">Loading...</span> --}}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>

                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (op) => {
                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: op == 1 ? "La compra de servicio sera asignada a la ruta" :
                            "El servicio sera reasignado de ruta.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(op == 1 ? 'banco-compra-rutas' : 'banco-servicio-rutas');
                        }
                    });
                });
                Livewire.on('alert', function([data]) {

                    $("#modalAddRutaCompra").modal('hide');
                    $("#modalAddServicio").modal('hide');

                    Swal.fire({
                        icon: data[1],
                        title: data[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });
        </script>
    @endpush
</div>
