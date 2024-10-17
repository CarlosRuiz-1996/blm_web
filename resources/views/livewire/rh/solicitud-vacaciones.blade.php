<!-- resources/views/livewire/empleado-form.blade.php -->

<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary float-right mb-2" wire:click="openModal()">
                    Nuevo <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" wire:model.live="empleado_id_filtro" class="form-control"
                                    placeholder="Empleado ID">
                            </div>
                            <div class="col-md-2">
                                <input type="date" wire:model.live="fecha_inicio_filtro" class="form-control"
                                    placeholder="Fecha de inicio">
                            </div>
                            <div class="col-md-2">
                                <input type="date" wire:model.live="fecha_fin_filtro" class="form-control"
                                    placeholder="Fecha de fin">
                            </div>
                            <div class="col-md-3">
                                <select wire:model.live="status_vacaciones_filtro" class="form-control">
                                    <option value="">Estado de vacaciones</option>
                                    <option value="1">Aprobada</option>
                                    <option value="2">Rechazada</option>
                                    <option value="3">Pendiente</option>
                                    <option value="4">Terminadas</option>
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
                                    <th>Empleado</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Motivo</th>
                                    <th>Estado Vacaciones</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Iteración sobre los resultados paginados -->
                                @foreach($solicitudes as $solicitud)
                                <tr>
                                    <td>{{ $solicitud->id }}</td>
                                    <td>{{ $solicitud->empleado->user->name }} {{ $solicitud->empleado->user->paterno }} {{ $solicitud->empleado->user->materno }}</td>
                                    <td>{{ $solicitud->fecha_inicio }}</td>
                                    <td>{{ $solicitud->fecha_fin }}</td>
                                    <td>{{$solicitud->motivo->motivo}}</td>
                                    <td>
                                        {{ $solicitud->status_vacaciones == 1 ? 'Aprobada' : ($solicitud->status_vacaciones == 2 ? 'Rechazada' : ($solicitud->status_vacaciones == 3 ? 'Pendiente' : ($solicitud->status_vacaciones == 4 ? 'Finalizadas' : 'Estado desconocido'))) }}
                                    </td>
                                    <td>
                                        @if($solicitud->status_vacaciones == 1 ||$solicitud->status_vacaciones == 2 || $solicitud->status_vacaciones == 3)
                                            <button wire:click='openModal({{ $solicitud->id }})' class="btn btn-warning">Editar</button>
                                            <button wire:click="eliminar({{ $solicitud->id }})" class="btn btn-danger">Eliminar</button>
                                            @if($solicitud->status_vacaciones == 2 || $solicitud->status_vacaciones == 3)
                                            <button wire:click="aceptarsoli({{ $solicitud->id }})" class="btn btn-success">Aprobar</button>
                                            @endif
                                        @endif
                                    </td>
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

    <div
    x-data="{ show: @entangle('isOpen') }"
    x-init="
        $watch('show', value => {
            if (value) {
                $('#myModal').modal('show');
            } else {
                $('#myModal').modal('hide');
            }
        });
        $('#myModal').on('hidden.bs.modal', () => {
            if (show) {
                show = false;
            }
        });
    "
    id="myModal"
    class="modal fade @if($isOpen) show @endif"
    tabindex="-1"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    wire:ignore.self
    style="@if($isOpen) display: block; @endif"
>
    <div class="modal-dialog modal-dialog-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white text-center">
                <h5 class="modal-title" id="myModalLabel">Subir Documento</h5>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="empleado_id">Empleado</label>
                        <select wire:model="empleado_id" class="form-control" id="empleado_id" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach ($empleados as $empleado)  {{-- Asegúrate de pasar esta lista desde el controlador --}}
                                <option value="{{ $empleado->id }}">{{ $empleado->user->name }} {{ $empleado->user->paterno }} {{ $empleado->user->materno }}</option>
                            @endforeach
                        </select>
                        @error('empleado_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
            
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" wire:model="fecha_inicio" class="form-control" id="fecha_inicio" required>
                        @error('fecha_inicio') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
            
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" wire:model="fecha_fin" class="form-control" id="fecha_fin" required>
                        @error('fecha_fin') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
            
                    <div class="form-group">
                        <label for="ctg_motivo_vacaciones_id">Motivo de Vacaciones</label>
                        <select wire:model="ctg_motivo_vacaciones_id" class="form-control" id="ctg_motivo_vacaciones_id" required>
                            <option value="">Seleccione un motivo</option>
                            @foreach ($motivos as $motivo)  {{-- Asegúrate de pasar esta lista desde el controlador --}}
                                <option value="{{ $motivo->id }}">{{ $motivo->motivo }}</option>
                            @endforeach
                        </select>
                        @error('ctg_motivo_vacaciones_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
            
                    <button type="submit" class="btn btn-primary btn-block">Enviar Solicitud</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>