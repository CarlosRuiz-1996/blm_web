<!-- resources/views/livewire/empleado-form.blade.php -->

<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary float-right mb-2"  data-toggle="modal" data-target="#ModalHerramientas">
                    Nuevo <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" wire:model.live="empleado_id" class="form-control"
                                    placeholder="Empleado ID">
                            </div>
                            <div class="col-md-2">
                                <input type="date" wire:model.live="fecha_inicio" class="form-control"
                                    placeholder="Fecha de inicio">
                            </div>
                            <div class="col-md-2">
                                <input type="date" wire:model.live="fecha_fin" class="form-control"
                                    placeholder="Fecha de fin">
                            </div>
                            <div class="col-md-3">
                                <select wire:model.live="status_vacaciones" class="form-control">
                                    <option value="">Estado de vacaciones</option>
                                    <option value="1">Aprobada</option>
                                    <option value="2">Rechazada</option>
                                    <option value="3">Pendiente</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button wire:click="resetFilters" class="btn btn-primary">Limpiar Filtros</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table">
                            <!-- Encabezado de la tabla -->
                            <thead class="table-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado ID</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado Vacaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Iteración sobre los resultados paginados -->
                                @foreach($solicitudes as $solicitud)
                                <tr>
                                    <td>{{ $solicitud->id }}</td>
                                    <td>{{ $solicitud->empleado_id }}</td>
                                    <td>{{ $solicitud->fecha_inicio }}</td>
                                    <td>{{ $solicitud->fecha_fin }}</td>
                                    <td>{{ $solicitud->status_vacaciones }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Controles de paginación automáticos de Livewire -->
                        {{ $solicitudes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>