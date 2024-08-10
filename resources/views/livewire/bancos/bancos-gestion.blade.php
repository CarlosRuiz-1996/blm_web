<div wire:init='loadClientes'>

    <div class="container-fluid">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Monto total en blm</span>


                @if ($readyToLoad)
                    <span class="info-box-number">

                        {{ number_format($resguardototal, 2, '.', ',') }} MXN
                    </span>
                @else
                    <div class="spinner-border" role="status"></div>
                @endif
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description ">
                    <b class="text-secondary"> Este monto es la suma total del resguardo de todos los clientes.</b>
                </span>
            </div>
            <livewire:bancos.servicios-bancos>
                {{-- <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span> --}}

        </div>

        <ul class="nav nav-tabs" wire:ignore.self id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->


            <li class="nav-item">
                <a class="nav-link active" id="bancos-tab" data-toggle="pill" href="#bancos" role="tab"
                    aria-controls="bancos-all" aria-selected="true">Bancos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="compras-tab" data-toggle="pill" href="#compras" role="tab"
                    aria-controls="compras" aria-selected="false">Compra de efectivo</a>
            </li>


            <li class="nav-item">
                <a class="nav-link" id="servicios-tab" data-toggle="pill" href="#servicios" role="tab"
                    aria-controls="servicios" aria-selected="true">Servicios mandados a rutas</a>
            </li>

        </ul>

        <div class="tab-content" wire:ignore.self id="custom-tabs-one-tabContent">
            <div class="tab-pane fade show active " id="bancos" role="tabpanel" aria-labelledby="bancos-tab">

                <div class="card col-md-12">
                    <div class="d-flex  mb-3 mt-3">
                        <input type="text" class="form-control w-full"
                            placeholder="Buscar cliente por razon social o RFC" wire:model.live='form.searchCliente'>
                    </div>

                    @if (count($clientes))

                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>Razon Social</th>
                                    <th>RFC</th>
                                    <th>Contacto</th>
                                    <th>Resguardo</th>
                                    <th style="width: 120px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->razon_social ?? 'N/A' }}</td>
                                        <td>{{ $cliente->rfc_cliente ?? 'N/A' }}</td>
                                        <td>{{ ($cliente->user->name ?? '') . ' ' . ($cliente->user->paterno ?? '') . ' ' . ($cliente->user->materno ?? '') }}
                                        </td>
                                        <td>
                                            ${{ $cliente->resguardo > 0 ? number_format($cliente->resguardo, 2, '.', ',') : 0 }}
                                        </td>
                                        <td>
                                            <button class="btn btn-info d-inline-block" title="detalles de movimientos"
                                                data-toggle="modal" wire:click="showDetail({{ $cliente->id }})"
                                                data-target="#modalDetail">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-success d-inline-block" title="Agregar"
                                                data-toggle="modal" wire:click="showMonto({{ $cliente->id }})"
                                                data-target="#modalAdd">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            {{-- <div class="d-inline-block" wire:ignore.self>
                                                <livewire:clientes.servicios-clientes :cliente="$cliente"
                                                    :banco="true" />

                                            </div> --}}

                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>


                        @if ($clientes->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $clientes->links() }}
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
            <div class="tab-pane fade" id="compras" role="tabpanel" aria-labelledby="compras-tab">

                <div class="card col-md-12 ">

                    @if (count($compras))

                        <table class="table table-bordered table-striped table-hover mt-3">
                            <thead class="table-info">
                                <tr>
                                    <th>Total</th>
                                    <th>Banco</th>
                                    <th>Fecha solicitada</th>
                                    <th>Estatus</th>
                                    <th style="width: 180px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compras as $compra)
                                    <tr>
                                        <td>$ {{ number_format($compra->total, 2, '.', ',') }}
                                        <td>{{ $compra->consignatario->name }}</td>
                                        <td>{{ $compra->fecha_compra }}
                                        </td>
                                        <td>
                                            {{ $compra->status_bancos_servicios == 1 ? 'Pendiente' : 'Finalizada' }}
                                        </td>
                                        <td>
                                            <button class="btn btn-info" data-toggle="modal"
                                                wire:click="showCompraDetail({{ $compra }})"
                                                data-target="#modalDetailCompra">Detalles</button>
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
            <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">

                <div class="card col-md-12">

                    @if (count($servicios))

                        <table class="table table-bordered table-striped table-hover mt-3">
                            <thead class="table-info">
                                <tr>
                                    <th>Razon Social</th>
                                    <th>Servicio</th>
                                    <th>Monto</th>
                                    <th>Papeleta</th>
                                    <th>Fecha de entrega</th>
                                    <th>Tipo servicio</th>
                                    <th>Estatus</th>
                                    {{-- <th style="width: 180px">Opciones</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $servicio)
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
                                            {{ $servicio->tipo_servicio == 1 ? 'Entrega' : 'Recolección' }}
                                        </td>
                                        <td>
                                            {{ $servicio->status_bancos_servicios == 1 ? 'Pendiente' : 'Finalizado' }}
                                        </td>

                                    </tr>
                                @endforeach


                            </tbody>
                        </table>


                        @if ($servicios->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $servicios->links() }}
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

    {{-- modal de agregar --}}
    {{-- elegir sucursal --}}
    <div class="modal fade" wire:ignore.self id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Ingresar monto</h5>
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="">Monto Actual</label>
                        <input disabled type="text" class="form-control"
                            value="{{ isset($form->cliente) ? $form->cliente->resguardo : '' }}">


                        <x-input-validadolive label="Monto a Ingresar" placeholder="Monto a Ingresar"
                            wire-model="form.ingresa_monto" type="number" />


                        <x-input-validadolive label="Nuevo Monto" placeholder="0" wire-model="form.nuevo_monto"
                            type="number" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='add' class="btn btn-info">Aceptar</button>
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" wire:ignore.self id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles de movimientos</h5>
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-info">
                            <tr>
                                <th>Razón social</th>
                                <th>RFC</th>
                                <th>Monto anterior</th>
                                <th>Monto entrante</th>
                                <th>Monto nuevo</th>
                                <th>Empleado que modifico</th>
                                <th>Área que modifico</th>
                                <th>Fecha</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($readyToLoadModal)

                                @if ($cliente_detail && count($cliente_detail->montos))
                                    @foreach ($cliente_detail->montos as $monto)
                                        <tr>
                                            {{-- <td>{{ $cliente->id }}</td> --}}
                                            <td>{{ $monto->cliente->razon_social ?? 'N/A' }}</td>
                                            <td>{{ $monto->cliente->rfc_cliente ?? 'N/A' }}</td>
                                            <td>${{ number_format($monto->monto_old, 2, '.', ',') }}</td>

                                            <td>${{ number_format($monto->monto_in, 2, '.', ',') }}</td>
                                            <td>${{ number_format($monto->monto_new, 2, '.', ',') }}</td>
                                            <td>{{ $monto->empleado->user->name ?? 'N/A' }}</td>
                                            <td>{{ $monto->area->name ?? 'N/A' }}</td>
                                            <td>{{ $monto->created_at ?? 'N/A' }}</td>
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
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" wire:ignore.self id="modalDetailCompra" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles de la compra</h5>
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-info">
                            <tr>
                                <th>Razón social</th>
                                <th>Monto</th>
                                {{-- <th>Fecha de la compra</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($readyToLoadModal)

                                @if ($compra_detalle && count($compra_detalle->detalles))
                                    @foreach ($compra_detalle->detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->cliente->razon_social }}</td>
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
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                Livewire.on('alert', function([message]) {

                    $("#modalAdd").modal('hide');
                    Swal.fire({
                        icon: message[1],
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });

            $('#modalAdd').on('hidden.bs.modal', function() {
                @this.dispatch('clean');

            });
            $('#modalDetail').on('hidden.bs.modal', function() {
                @this.dispatch('clean');
            });
        </script>
    @endpush

</div>
