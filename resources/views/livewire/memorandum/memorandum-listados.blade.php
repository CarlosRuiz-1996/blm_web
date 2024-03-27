<div>
    <div class="col-md-6">
        <h3 for="">Memorándum de servicio</h3>
    </div>
    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->
            <li class="nav-item">
                <a class="nav-link active" id="memo-solicitud-tab" data-toggle="pill" href="#memo-solicitud" role="tab"
                    aria-controls="memo-solicitud" aria-selected="true">SOLICITUDES</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="memo-proceso-tab" data-toggle="pill" href="#memo-proceso" role="tab"
                    aria-controls="memo-proceso" aria-selected="true">EN VALIDACION</a>
            </li>

            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="memo-terminado-tab" data-toggle="pill" href="#memo-terminado" role="tab"
                    aria-controls="memo-terminado" aria-selected="false">ATENDIDAS</a>
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
                                                    <a href="{{ route('memorandum', $solicitud) }}">Llenar
                                                        memorandum</a>
                                                </td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                                                    <input wire:model="idproceso" type="text" class="form-control" id="inputId" placeholder="Ingresa la Id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputcotizacionanexo">Cotización</label>
                                                    <input wire:model="cotizacionproceso" type="text" class="form-control" id="inputcotizacionanexo" placeholder="Ingresa Cotización">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputFechaInicio">Fecha Inicio</label>
                                                    <input wire:model="fechaInicioproceso" type="date" class="form-control" id="inputFechaInicio" placeholder="Ingresa la Fecha Inicio">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputFechafin">Fecha Fin</label>
                                                    <input wire:model="fechaFinproceso" type="date" class="form-control" id="inputFechafin" placeholder="Ingresa la Fecha Fin">
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
                                                    <a class="btn btn-warning text-white" href="{{ route('memorandum.validacion', $solicitud) }}">Continuar
                                                        memorandum</a>
                                                </td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                                                    pdf
                                                    {{-- <a href="{{route('anexo.index', $solicitud->id)}}">Comenzar anexo1</a> --}}
                                                </td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Puedes copiar y pegar el contenido de la pestaña "Servicios" aquí y hacer las modificaciones necesarias -->
            </div>
        </div>
    </div>
</div>
