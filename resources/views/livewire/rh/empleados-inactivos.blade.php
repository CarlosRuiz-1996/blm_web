<div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mr-2">
                                <label for="claveEmpleado" class="sr-only">Clave</label>
                                <input type="text" class="form-control" id="claveEmpleado" wire:model='claveEmpleado' placeholder="Clave">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mr-2">
                                <label for="NombreEmpleado" class="sr-only">Nombre</label>
                                <input type="text" class="form-control" id="NombreEmpleado" wire:model='NombreEmpleado' placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary btn-block" wire:click='buscar'>Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">

                <table class="table">
                    <thead class="table-info">
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->cve_empleado }}</td>
                                <td>{{ $empleado->user->name }} {{ $empleado->user->paterno }} {{ $empleado->user->materno }}</td>
                                <td>
                                    <a href="{{ route('rh.perfil', ['id' => $empleado->id]) }}" class="btn btn-info">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $empleados->links() }}
            </div>
        </div>
    </div>

</div>