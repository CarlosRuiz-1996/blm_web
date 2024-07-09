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
        </div>
        <div class="card">
            @if ($clientes)

                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th>Razon Social</th>
                            <th>RFC</th>
                            <th>Contacto</th>
                            <th>Resguardo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                {{-- <td>{{ $cliente->id }}</td> --}}
                                <td>{{ $cliente->razon_social ?? 'N/A' }}</td>
                                <td>{{ $cliente->rfc_cliente ?? 'N/A' }}</td>
                                <td>{{ ($cliente->user->name ?? '') . ' ' . ($cliente->user->paterno ?? '') . ' ' . ($cliente->user->materno ?? '') }}
                                </td>
                                <td>
                                    ${{ $cliente->resguardo > 0 ? number_format($cliente->resguardo, 2, '.', ',') : 0 }}
                                </td>
                                <td>
                                    <a class="btn btn-info" title="detalles de movimientos"
                                        href="{{ route('anexo.index', $cliente->id) }}">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                    <button class="btn btn-success" title="Agregar" data-toggle="modal"
                                        wire:click="showMonto({{ $cliente->id }})" data-target="#modalAdd">
                                        <i class="fas fa-plus"></i>
                                    </button>
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
                    <h1 class="col-md-12 text-center">No hay datos disponibles</h1>
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
                        <input disabled type="text" class="form-control" value="{{ $form->cliente ? $form->cliente->resguardo : '' }}" >

                        
                        <x-input-validadolive label="Monto a Ingresar" placeholder="Monto a Ingresar"
                        wire-model="form.nuevo_monto" type="number" />

                       
                        <x-input-validadolive label="Nuevo Monto" placeholder="0"
                        wire-model="form.nuevo_monto" type="number" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
