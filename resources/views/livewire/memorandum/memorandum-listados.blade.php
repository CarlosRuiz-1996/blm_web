<div wire:init='loadData'>
    <div class="col-md-6">
        <h3 for="">Memorándum de servicio</h3>
    </div>
    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist" wire:ignore.self>
            <!-- Pestaña "Servicios" -->
            <li class="nav-item">
                <a class="nav-link active" id="memo-solicitud-tab" data-toggle="pill" href="#memo-solicitud"
                    wire:ignore.self role="tab" aria-controls="memo-solicitud" aria-selected="true">SOLICITUDES</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="memo-proceso-tab" data-toggle="pill" href="#memo-proceso" wire:ignore.self
                    role="tab" aria-controls="memo-proceso" aria-selected="true">EN VALIDACION</a>
            </li>

            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="memo-terminado-tab" data-toggle="pill" href="#memo-terminado" wire:ignore.self
                    role="tab" aria-controls="memo-terminado" aria-selected="false">ATENDIDAS</a>
            </li>
        </ul>

        <div class="tab-content" id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="memo-solicitud" role="tabpanel"
                aria-labelledby="memo-solicitud-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <form>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputId">Id</label>
                                                <input type="text" class="form-control" id="inputId"
                                                    placeholder="Ingresa la Id">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputcotizacionanexo">Cotización</label>
                                                <input type="text" class="form-control" id="inputcotizacionanexo"
                                                    placeholder="Ingresa Cotización">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechaInicio">Fecha
                                                    Inicio</label>
                                                <input type="date" class="form-control" id="inputFechaInicio"
                                                    placeholder="Ingresa el Fecha Inicio">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechafin">Fecha Fin</label>
                                                <input type="date" class="form-control" id="inputFechafin"
                                                    placeholder="Ingresa Fecha fin">
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if (count($solicitudes))
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Razon Social</th>
                                                    <th>RFC</th>
                                                    <th>Contacto</th>
                                                    <th>Fecha de Solicitud</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($solicitudes as $solicitud)
                                                    <tr>
                                                        <td>{{ $solicitud->id }}</td>
                                                        <td>{{ $solicitud->cliente->razon_social }}</td>
                                                        <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                                                        <td>{{ $solicitud->cliente->user->name .
                                                            ' ' .
                                                            $solicitud->cliente->user->paterno .
                                                            ' ' .
                                                            $solicitud->cliente->user->materno }}
                                                        </td>
                                                        <td>{{ $solicitud->updated_at }}</td>
                                                        <td>
                                                            <button class="btn btn-primary"
                                                                wire:click="$dispatch('confirm', [1, {{ $solicitud->id }}])">Comenzar</button>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    @if ($readyToLoad)
                                        <div class="alert alert-secondary" role="alert">
                                            No hay datos disponibles </div>
                                    @else
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de la pestaña "Otra Pestaña 2" -->
            <div class="tab-pane fade" id="memo-proceso" role="tabpanel" aria-labelledby="memo-proceso-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <form wire:submit.prevent="buscar">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputId">Id</label>
                                                <input wire:model="idproceso" type="text" class="form-control"
                                                    id="inputId" placeholder="Ingresa la Id">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputcotizacionanexo">Cotización</label>
                                                <input wire:model="cotizacionproceso" type="text"
                                                    class="form-control" id="inputcotizacionanexo"
                                                    placeholder="Ingresa Cotización">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechaInicio">Fecha Inicio</label>
                                                <input wire:model="fechaInicioproceso" type="date"
                                                    class="form-control" id="inputFechaInicio"
                                                    placeholder="Ingresa la Fecha Inicio">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechafin">Fecha Fin</label>
                                                <input wire:model="fechaFinproceso" type="date"
                                                    class="form-control" id="inputFechafin"
                                                    placeholder="Ingresa la Fecha Fin">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if (count($proceso))
                                    <div class="table-responsive">

                                        <table class="table">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Razon Social</th>
                                                    <th>RFC</th>
                                                    <th>Contacto</th>
                                                    <th>Fecha de Solicitud</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($proceso as $solicitud)
                                                    <tr>
                                                        <td>{{ $solicitud->id }}</td>
                                                        <td>{{ $solicitud->cliente->razon_social }}</td>
                                                        <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                                                        <td>{{ $solicitud->cliente->user->name .
                                                            ' ' .
                                                            $solicitud->cliente->user->paterno .
                                                            ' ' .
                                                            $solicitud->cliente->user->materno }}
                                                        </td>
                                                        <td>{{ $solicitud->created_at }}</td>
                                                        <td>
                                                            <a class="btn btn-primary text-white"
                                                                wire:click="$dispatch('confirm', [2, {{ $solicitud->id }}])">
                                                                Detalles</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    @if ($readyToLoad)
                                        <div class="alert alert-secondary" role="alert">
                                            No hay datos disponibles </div>
                                    @else
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
            </div>

            <!-- Contenido de la pestaña "Otra Pestaña 2" -->
            <div class="tab-pane fade" id="memo-terminado" role="tabpanel" aria-labelledby="memo-terminado-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <form>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputId">ID</label>
                                                <input type="text" class="form-control" id="inputId"
                                                    placeholder="Ingresa la Id">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputcotizacionanexo">Cotización</label>
                                                <input type="text" class="form-control" id="inputcotizacionanexo"
                                                    placeholder="Ingresa Cotización">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechaInicio">Fecha
                                                    Inicio</label>
                                                <input type="date" class="form-control" id="inputFechaInicio"
                                                    placeholder="Ingresa el Fecha Inicio">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputFechafin">Fecha Fin</label>
                                                <input type="date" class="form-control" id="inputFechafin"
                                                    placeholder="Ingresa Fecha fin">
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if (count($terminadas))
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Razon Social</th>
                                                    <th>RFC</th>
                                                    <th>Contacto</th>
                                                    <th>Fecha de Solicitud</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($terminadas as $solicitud)
                                                    <tr>
                                                        <td>{{ $solicitud->id }}</td>
                                                        <td>{{ $solicitud->cliente->razon_social }}</td>
                                                        <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                                                        <td>{{ $solicitud->cliente->user->name .
                                                            ' ' .
                                                            $solicitud->cliente->user->paterno .
                                                            ' ' .
                                                            $solicitud->cliente->user->materno }}
                                                        </td>
                                                        <td>{{ $solicitud->updated_at }}</td>
                                                        <td>
                                                            <i wire:click="generarPDF({{ $solicitud->id }})"
                                                                class="fa fa-file-pdf"
                                                                style="color: red; cursor: pointer;"></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($terminadas->hasPages())
                                        <div class="d-flex justify-content-center">
                                            {{ $terminadas->links() }}
                                        </div>
                                    @endif
                                    </div>
                                @else
                                    @if ($readyToLoad)
                                        <div class="alert alert-secondary" role="alert">
                                            No hay datos disponibles </div>
                                    @else
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('confirm', ([op, id]) => {
                    Swal.fire({
                        title: op == 1 ? '¿Seguro de inicar el memorandum?' : '¿Esta seguro?',
                        text: op == 2 ? 'Desde esta area se podra finalizar el memorandum' : '',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (op == 1) {
                                window.location.href = '{{ route('memorandum', [':factibilidad']) }}'
                                    .replace(':factibilidad', id)
                            } else if (op == 2) {
                                window.location.href =
                                    '{{ route('memorandum.validacion', [':memorandum']) }}'
                                    .replace(':memorandum', id)
                            }
                        }
                    })
                })
            });
        </script>
    @endpush
</div>
