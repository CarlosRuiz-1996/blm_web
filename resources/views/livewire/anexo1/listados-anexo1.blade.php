<div wire:init='loadData'>
    <div class="col-md-6">
        <h3 for="">Anexo 1</h3>
    </div>
    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-anexo1coti-tab" data-toggle="pill"
                    href="#custom-tabs-one-anexo1coti" role="tab" aria-controls="custom-tabs-one-anexo1coti"
                    aria-selected="true">SOLICITUDES</a>
            </li>



            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-otra-pestaña-2-tab" data-toggle="pill"
                    href="#custom-tabs-one-otra-pestaña-2" role="tab" aria-controls="custom-tabs-one-otra-pestaña-2"
                    aria-selected="false">ATENDIDAS</a>
            </li>
        </ul>

        <div class="tab-content" id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="custom-tabs-one-anexo1coti" role="tabpanel"
                aria-labelledby="custom-tabs-one-anexo1coti-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputcotizacionanexo">Cotización</label>
                                            <input wire:model.live="cotizacionsoli" type="text" class="form-control"
                                                id="inputcotizacionanexo" placeholder="Ingresa Cotización">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputFechaInicio">Fecha Inicio</label>
                                            <input wire:model.live="fechaIniciosoli" type="date" class="form-control"
                                                id="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputFechafin">Fecha Fin</label>
                                            <input wire:model.live="fechaFinsoli" type="date" class="form-control"
                                                id="inputFechafin" placeholder="Ingresa Fecha fin">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
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
                                            @if ($solicitudes)
                                            @foreach ($solicitudes as $solicitud)
                                            <tr>
                                                <td>{{ $solicitud->id }}</td>
                                                <td>{{ $solicitud->cliente->razon_social ?? 'N/A' }}</td>
                                                <td>{{ $solicitud->cliente->rfc_cliente ?? 'N/A' }}</td>
                                                <td>{{ ($solicitud->cliente->user->name ?? '') .
                                                    ' ' .
                                                    ($solicitud->cliente->user->paterno ?? '') .
                                                    ' ' .
                                                    ($solicitud->cliente->user->materno ?? '') }}
                                                </td>
                                                <td>{{ $solicitud->updated_at }}</td>
                                                <td>
                                                    <a href="{{ route('anexo.index', $solicitud->id) }}">Comenzar
                                                        anexo1</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="6">No hay solicitudes disponibles</td>
                                            </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Contenido de la pestaña "Otra Pestaña 2" -->
            <div class="tab-pane fade" id="custom-tabs-one-otra-pestaña-2" role="tabpanel"
                aria-labelledby="custom-tabs-one-otra-pestaña-2-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputId">Anexo 1</label>
                                            <input wire:model.live="anexo_id" type="text" class="form-control"
                                                id="inputId" placeholder="Ingresa la Id">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputcotizacionanexo">Cotización</label>
                                            <input wire:model.live="anexo_coti" type="text" class="form-control"
                                                id="inputcotizacionanexo" placeholder="Ingresa Cotización">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputFechaInicio">Fecha Inicio</label>
                                            <input wire:model.live="fechaIniciosoli2" type="date" class="form-control"
                                                id="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputFechafin">Fecha Fin</label>
                                            <input wire:model.live="fechaFinsoli2" type="date" class="form-control"
                                                id="inputFechafin" placeholder="Ingresa Fecha fin">
                                        </div>
                                    </div>

                                </div>
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

                                                    <a href="{{ route('anexo.pdf', $solicitud->id) }}"
                                                        class="btn text-danger" target="_blank">
                                                        <i class="fas fa-file-pdf "></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($terminadas->hasPages())
                                    <div class="col-md-12 text-center">
                                        {{ $terminadas->links() }}
                                    </div>
                                </div>
                                @endif
                                @else
                                @if ($readyToLoad)
                                <h3 class="col-md-12 text-center">No hay datos disponibles</h3>
                                @else
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
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
            </div>
        </div>
    </div>
</div>