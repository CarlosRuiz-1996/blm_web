<div wire:init='loadClientes'>

    <div class="container-fluid">
        <div class="info-box">
            {{-- montoo total --}}
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Monto total en blm</span>


                @if ($readyToLoad)
                    <span class="info-box-number">

                        {{ number_format($resguardototal->monto, 2, '.', ',') }} MXN
                    </span>
                @else
                    <div class="spinner-border" role="status"></div>
                @endif
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description ">
                    <b class="text-secondary"> Este es el monto disponible de blm.</b>
                </span>
            </div>
            {{-- resguardo --}}
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Resguardo</span>

                @if ($readyToLoad)
                    <span class="info-box-number">

                        {{ number_format($resguardototalCliente, 2, '.', ',') }} MXN
                    </span>
                @else
                    <div class="spinner-border" role="status"></div>
                @endif
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="progress-description ">
                    <b class="text-secondary"> Este es el monto en resguardo de todos los clientes</b>
                </span>
            </div>
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

        </div>

        <ul class="nav nav-tabs" wire:ignore.self id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->

            <li class="nav-item">
                <a class="nav-link {{ $activeNav[0] }}" wire:click='ActiveNav(0)' id="bancos-tab" data-toggle="pill"
                    href="#bancos" role="tab" aria-controls="bancos-all" aria-selected="true">Saldo de clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeNav[1] }}" wire:click='ActiveNav(1)' id="compras-tab" data-toggle="pill"
                    href="#compras" role="tab" aria-controls="compras" aria-selected="false">Compra de efectivo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeNav[2] }}" wire:click='ActiveNav(2)' id="servicios-tab" data-toggle="pill"
                    href="#servicios" role="tab" aria-controls="servicios" aria-selected="true">Dotaciones mandados
                    a
                    rutas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeNav[3] }}" wire:click='ActiveNav(3)' id="acreditaciones-tab"
                    data-toggle="pill" href="#acreditaciones" role="tab" aria-controls="acreditaciones"
                    aria-selected="true">
                    Acreditaciones
                </a>
            </li>
        </ul>

        <div class="tab-content" wire:ignore.self id="custom-tabs-one-tabContent">
            <div class="tab-pane fade {{ $activeNav[0] }} {{ $showNav[0] }} " id="bancos" role="tabpanel"
                aria-labelledby="bancos-tab">

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
            <div class="tab-pane fade {{ $activeNav[1] }} {{ $showNav[1] }} " id="compras" role="tabpanel"
                aria-labelledby="compras-tab">

                <div class="card col-md-12 ">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Efectivo</label>
                                <input type="text" class="form-control w-full"
                                    placeholder="Buscar total de la compra"
                                    wire:model.live='form.monto_compra_search'>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Banco/Consignatario</label>

                                <input type="text" class="form-control w-full" placeholder="Buscar banco"
                                    wire:model.live='form.banco_compra_search'>
                            </div>
                        </div> --}}

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Fecha de inicio</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechaini_compra_search'>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Fecha de fin</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechafin_compra_search'>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Estatus</label>
                                <select class="custom-select" wire:model.live='form.status_compra_search'>
                                    <option value="" selected>Seleccione</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">Finalizado</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    @if (count($compras))

                        <table class="table table-bordered table-striped table-hover mt-3">
                            <thead class="table-info">
                                <tr>
                                    <th>Total</th>
                                    <th>Fecha solicitada</th>
                                    <th>Estatus</th>
                                    <th style="width: 180px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compras as $compra)
                                    <tr>
                                        <td>$ {{ number_format($compra->total, 2, '.', ',') }}
                                        <td>{{ $compra->fecha_compra }}
                                        </td>
                                        <td>
                                            {{ $compra->status_compra_efectivos == 1 ? 'Pendiente' : 'Finalizada' }}
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
                            <div class="col-md-12 text-center mt-3">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                    {{-- <span class="visually-hidden">Loading...</span> --}}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="tab-pane fade {{ $activeNav[2] }} {{ $showNav[2] }} " id="servicios" role="tabpanel"
                aria-labelledby="servicios-tab">

                <div class="card col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Cliente</label>
                                <input type="text" class="form-control w-full" placeholder="Buscar cliente"
                                    wire:model.live='form.cliente_bancoServ_serach'>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Papeleta</label>

                                <input type="text" class="form-control w-full" placeholder="Buscar papeleta"
                                    wire:model.live='form.papeleta_bancoServ_serach'>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de inicio</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechaini_bancoServ_serach'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de fin</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechafin_bancoServ_serach'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Tipo de servicio</label>
                                <select class="custom-select" wire:model.live='form.tipoServ_bancoServ_serach'>
                                    <option value="" selected>Seleccione</option>
                                    <option value="1">Entrega</option>
                                    <option value="2">Recolecta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Estatus</label>
                                <select class="custom-select" wire:model.live='form.status_bancoServ_serach'>
                                    <option value="" selected>Seleccione</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">Finalizado</option>
                                </select>
                            </div>

                        </div>
                    </div>
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
            <div class="tab-pane fade {{ $activeNav[3] }} {{ $showNav[3] }} " id="acreditaciones" role="tabpanel"
                aria-labelledby="acreditaciones-tab">

                <div class="card col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Monto</label>
                                <input type="text" class="form-control w-full" placeholder="Buscar por monto"
                                    wire:model.live='form.monto_acreditacion_search'>
                            </div>
                        </div>
            
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Papeleta</label>
            
                                <input type="text" class="form-control w-full" placeholder="Buscar por papeleta de ruta"
                                    wire:model.live='form.papeleta_acreditacion_search'>
                            </div>
                        </div>
            
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de inicio</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechai_acreditacion_search'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de fin</label>
                                <input type="date" class="form-control w-full"
                                    wire:model.live='form.fechaf_acreditacion_search'>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Folio/Ticket</label>
            
                                <input type="text" class="form-control w-full" placeholder="Buscar por ticket"
                                    wire:model.live='form.folio_acreditacion_search'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Estatus</label>
                                <select class="custom-select" wire:model.live='form.status_acreditacion_search'>
                                    <option value="" selected>Seleccione</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">Finalizado</option>
                                </select>
                            </div>
            
                        </div>
                    </div> 
                    @if (count($acreditaciones))

                        <table class="table table-bordered table-striped table-hover mt-3">
                            <thead class="table-info">
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Papeleta</th>
                                    <th>Fecha de entrada</th>
                                    <th>Folio/ticket</th>
                                    <th>Estatus</th>
                                    <th style="width: 180px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($acreditaciones as $acreditacion)
                                    <tr>
                                        <td>$ {{ number_format($acreditacion->envase->cantidad, 2, '.', ',') }}</td>
                                        <td>
                                            {{ $acreditacion->envase->folio }}
                                        </td>
                                        <td>
                                            {{ $acreditacion->created_at }}
                                        </td>
                                        <td>
                                            {{ $acreditacion->folio ?? 'Sin folio' }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $acreditacion->status_acreditacion == 2 ? 'bg-success' : 'bg-warning' }} mb-2">
                                                {{ $acreditacion->status_acreditacion == 2 ? 'Finalizado' : 'Pendiente' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($acreditacion->status_acreditacion==1)
                                            <button class="btn btn-success d-inline-block" title="Ticket"
                                                data-toggle="modal" wire:click="addTickect({{ $acreditacion }})"
                                                data-target="#modalAcreditacion">
                                                Ticket
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>


                        @if ($acreditaciones->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $acreditaciones->links() }}
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
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- modal de agregar --}}
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
                            value="{{ isset($form->cliente) ? '$ ' . number_format($form->cliente->resguardo, 2, '.', ',') : '' }}">


                        <label for="">Monto a Ingresar $
                            {{ $form->ingresa_monto ? number_format($form->ingresa_monto, 2, '.', ',') : '0' }}
                        </label>
                        <input type="number" class="form-control @error('form.ingresa_monto') is-invalid @enderror"
                            placeholder="Monto a Ingresar" wire:model.live="form.ingresa_monto">
                        @error('form.ingresa_monto')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror

                        <label for="">Nuevo Monto</label>
                        <input disabled type="text" class="form-control"
                            value="{{ isset($form->nuevo_monto) ? '$ ' . number_format($form->nuevo_monto, 2, '.', ',') : '$ 0' }}">

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
                    @if ($cliente_detail)
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-info">
                                <tr>
                                    <th>Razón social</th>
                                    <th>RFC</th>
                                    <th>Monto anterior</th>
                                    <th>Monto entrante</th>
                                    <th>Monto disminuido</th>
                                    <th>Monto nuevo</th>
                                    <th>Empleado que modifico</th>
                                    <th>Área que modifico</th>
                                    <th>Fecha</th>
                                    <th>Estatus</th>

                                </tr>
                            </thead>
                            <tbody>


                                @if ($cliente_detail && count($cliente_detail->montos))
                                    @foreach ($this->montos as $monto)
                                        <tr>
                                            <td>{{ $monto->cliente->razon_social ?? 'N/A' }}</td>
                                            <td>{{ $monto->cliente->rfc_cliente ?? 'N/A' }}</td>
                                            <td>${{ number_format($monto->monto_old, 2, '.', ',') }}</td>

                                            <td>{{ $monto->tipo == 1 ? '$ ' . number_format($monto->monto_in, 2, '.', ',') : 'N/A' }}
                                            </td>
                                            <td>{{ $monto->tipo == 0 ? '$ ' . number_format($monto->monto_in, 2, '.', ',') : 'N/A' }}
                                            </td>
                                            <td>${{ number_format($monto->monto_new, 2, '.', ',') }}</td>
                                            <td>{{ $monto->empleado->user->name ?? 'N/A' }}</td>
                                            <td>{{ $monto->area->name ?? 'N/A' }}</td>
                                            <td>{{ $monto->created_at ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $monto->status_cliente_monto == 2 ? 'bg-success' : 'bg-warning' }} mb-2">
                                                    {{ $monto->status_cliente_monto == 2 ? 'Terminado' : 'Pendiente' }}
                                                </span>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="8"> No hay movimientos</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                        @if ($this->montos->hasPages())
                            <div class="col-md-12 text-center">
                                {{ $this->montos->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <td colspan="8">
                                <div class="spinner-border" role="status"
                                    style="width: 5rem; height: 5rem; border-width: 0.5em;"></div>
                            </td>
                        </div>

                    @endif




                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>





    {{-- servicios compra y add servicio --}}
    {{-- efectivo --}}
    <div class="modal fade" wire:ignore.self id="compraEfectivo" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Compra de efectivo.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='clean'><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="form-group  col-md-5">

                            <label for="">Monto: {{ number_format($monto_e ?? 0, 2, '.', ',') }} MXN</label>
                            <input type="number" wire:model.live='monto_e'
                                class="form-control @error('monto_e') is-invalid @enderror">
                            @error('monto_e')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror


                        </div>
                        <div class="form-group  col-md-5">
                            <x-select-validado label="Cajero:" placeholder="" wire-model="cajero_id" required>
                                @foreach ($consignatarios as $consignatario)
                                    <option value="{{ $consignatario->id }}">

                                        {{ $consignatario->name }}</option>
                                @endforeach

                            </x-select-validado>
                        </div>

                        <div class="form-group  col-md-2" style="margin-top: 32px">
                            <button type="button" class="btn btn-info" wire:click='addCompra'
                                wire:loading.remove>Agregar</button>
                        </div>
                    </div>
                    @if ($compras_efectivo)
                        <table class="table table-striped">
                            <thead class="table-info">
                                <th>Cajero</th>
                                <th>Monto</th>
                                <th>Eliminar</th>
                            </thead>
                            <tbody>

                                @foreach ($compras_efectivo as $index => $compra)
                                    <tr>
                                        <td>{{ $compra['cajero_name'] }}</td>
                                        <td>

                                            $ {{ number_format($compra['monto'], 2, '.', ',') }} MXN
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
                                <label for="">Total de la compra</label>
                                <input type="text" disabled class="form-control"
                                    value="$ {{ number_format($total, 2, '.', ',') }} MXN">

                            </div>

                        </div>
                    @else
                        <span class="progress-description ">
                            <b class="text-secondary"> No hay servicios agregados.</b>
                        </span>
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($compras_efectivo)
                        <button type="button" class="btn btn-info" wire:click='finalizarCompra'
                            wire:loading.remove>Aceptar</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>
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
                                <th>Banco/Consignatario</th>
                                <th>Monto</th>
                                {{-- <th>Fecha de la compra</th> --}}
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
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click='clean'><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="form-group  col-md-4" wire:ignore>

                            <x-select-select2 label="Clientes:" placeholder="" wire-model="cliente" required
                                classSelect2="cliente_servicio" modalName='addServicio'>
                                @if ($clientes_activo && count($clientes_activo) > 0)
                                    @foreach ($clientes_activo as $cliente)
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
                        <div class="form-group  col-md-12">
                            <x-input-validadolive label="Direccion:" placeholder="Direccion del servicio"
                                wire-model="direccion" type="text" :readonly='true' />
                        </div>
                        <div class="form-group  col-md-3">


                            <label for="">
                                Monto:
                                ${{ number_format((float) ($monto_e ?? 0), 2, '.', ',') }}

                            </label>
                            <input class="form-control  @error('monto_e') 'is-invalid' @enderror"
                                wire:model.live="monto_e" placeholder="Monto" type="number" />
                            @error('monto_e')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
                            <button type="button" class="btn btn-info" wire:click='addServicios'
                                wire:loading.remove>Agregar</button>
                        </div>


                    </div>
                    @if ($servicios_add)
                        <table class="table table-striped">
                            <thead class="table-info">
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Monto</th>
                                <th>Tipo de servicio</th>
                                <th>Papeleta</th>
                                <th>Fecha</th>
                                <th>Eliminar</th>
                            </thead>
                            <tbody>
                                @foreach ($servicios_add as $index => $servicio)
                                    <tr>
                                        <td>{{ $servicio['cliente_name'] }}</td>
                                        <td>{{ $servicio['direccion'] }}</td>
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
                    @if ($servicios_add)
                        <button type="button" class="btn btn-info" wire:click='finalizarServicios'
                            wire:loading.remove>Aceptar</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click='clean'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    {{-- acreditaciones --}}

    <div class="modal fade" wire:ignore.self id="modalAcreditacion" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Ingresar Folio/Ticket</h5>
                    <button type="button" wire:click='limpiarDatos' class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="">Monto ingresado</label>
                        <input disabled type="text" class="form-control"
                            value="{{ isset($acreditacion_detail->envase->cantidad) ? '$ ' . number_format($acreditacion_detail->envase->cantidad, 2, '.', ',') : '' }}">



                        <x-input-validado label="No. Ticket" placeholder="Ingresa el ticket" wire-model="ticket"
                            type="text" />

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='finalizarAcreditacion' class="btn btn-info">Aceptar</button>
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

                    if (message[1] == 'success') {
                        $("#modalAdd").modal('hide');
                    }
                    $("#compraEfectivo").modal('hide');
                    $("#addServicio").modal('hide');
                    $("#modalAcreditacion").modal('hide');
                    Swal.fire({
                        icon: message[1],
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });



                //resetea el select2 al placeholder
                Livewire.on('resetSelect2', function() {
                    $('.cliente_compra').val('').trigger('change');
                    $('.cliente_servicio').val('').trigger('change');

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
