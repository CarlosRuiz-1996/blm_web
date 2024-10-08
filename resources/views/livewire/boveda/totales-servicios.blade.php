<div wire:init='loadTotalesSServicios'>
    <div class="container-fluid">
        <div class="row mb-2 mt-2 align-items-end">
            <div class="col-3 col-sm-12 col-md-3">
                <label for="fechainicio">Fecha Inicio</label>
                <input class="form-control" type="date" wire:model.live='fechaInicio'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <label for="fechafin">Fecha Fin</label>
                <input class="form-control" type="date" wire:model.live='fechaFin'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <label for="razonsocial">Cliente</label>
                <input class="form-control" type="text" wire:model.live='razonsocial'>
            </div>
            <div class="col-3 col-sm-12 col-md-3">
                <button type="button" name="" id="" wire:click='exportarExcel' class="btn btn-success btn-block mt-2">Exportar</button>
            </div>
        </div>
        
        <div class="accordion mt-2" id="accordionClientes">
            @foreach ($clientes as $cliente)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $cliente->id }}">
                        <button class="btn btn-block btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $cliente->id }}" aria-expanded="false" aria-controls="collapse{{ $cliente->id }}">
                            {{ $cliente->razon_social }}
                        </button>
                    </h2>
                    <div id="collapse{{ $cliente->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $cliente->id }}" data-parent="#accordionClientes">
                        <div class="accordion-body">
                            <h5>Servicios del Cliente</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Ruta</th>
                                            <th>Sucursal</th>
                                            <th>Papeleta</th>
                                            <th>Tipo Servicio</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cliente->servicios as $servicio)
                                            @foreach ($servicio->ruta_servicios as $rutaServicio)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $rutaServicio->ruta->nombre->name }}</td>
                                                    <td>{{ $rutaServicio->servicio->sucursal->sucursal->sucursal }}</td>
                                                    <td>{{$rutaServicio->folio}}</td>
                                                    <td>{{ $rutaServicio->tipo_servicio == 1 ? 'Entrega' : ($rutaServicio->tipo_servicio == 2 ? 'Recolecta' : 'Desconocido') }}</td>
                                                    <td>{{$rutaServicio->monto}}</td>
                                                    <td>{{ $rutaServicio->created_at->format('Y-m-d') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
