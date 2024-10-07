<div wire:init='loadTotalesSServicios'>
    <div class="container">
        <div class="row mb-2 mt-2 align-items-end"> <!-- Alinea al final -->
            <div class="col-4">
                <label for="fechainicio">Fecha Inicio</label>
                <input class="form-control" type="date" wire:model.live='fechaInicio'>
            </div>
            <div class="col-4">
                <label for="fechafin">Fecha Fin</label>
                <input class="form-control" type="date" wire:model.live='fechaFin'>
            </div>
            <div class="col-4">
                <label for="razonsocial">Cliente</label>
                <input class="form-control" type="text" wire:model.live='razonsocial'>
            </div>
        </div>
        
        <div class="accordion" id="accordionClientes">
            @foreach ($clientes as $cliente)
                <div class="accordion-item"> <!-- Añadir margen entre los elementos -->
                    <h2 class="accordion-header" id="heading{{ $cliente->id }}">
                        <button class="btn btn-block btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $cliente->id }}" aria-expanded="false" aria-controls="collapse{{ $cliente->id }}">
                            {{ $cliente->razon_social }}
                        </button>
                    </h2>
                    <div id="collapse{{ $cliente->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $cliente->id }}" data-parent="#accordionClientes">
                        <div class="accordion-body">
                            <h5>Servicios del Cliente</h5>
                            <div class="table-responsive"> <!-- Tabla responsiva para mejor visualización en móviles -->
                                <table class="table table-striped table-hover table-bordered"> <!-- Tabla con bordes y hover -->
                                    <thead class="table-dark"> <!-- Encabezado con fondo oscuro -->
                                        <tr>
                                            <th>#</th>
                                            <th>Servicio</th>
                                            <th>Ruta</th>
                                            <th>Sucursal</th>
                                            <th>Tipo Servicio</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cliente->servicios as $servicio)
                                            @foreach ($servicio->ruta_servicios as $rutaServicio)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                                    <td>{{ $rutaServicio->ruta->nombre->name }}</td>
                                                    <td>{{ $rutaServicio->servicio->sucursal->sucursal->sucursal }}</td>
                                                    <td>{{ $rutaServicio->tipo_servicio == 1 ? 'Entrega' : ($rutaServicio->tipo_servicio == 2 ? 'Recolecta' : 'Desconocido') }}</td>
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
