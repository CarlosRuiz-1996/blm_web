<div>
    <div class="row g-3">
        <div class="col-md-10" style="display: flex; justify-content: space-between;">
            <h3 style="margin: 0;">Validación de Memorándum de servicio</h3>
        </div>
        <div class="col-md-2" style="display: flex; justify-content: space-between;">
            @if($name)
            <h6 class="text-primary" style="align-self: flex-end;">- Área: {{ $name }}</h6>
            @endif
        </div>
    </div>

    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->
            <li class="nav-item">
                <a class="nav-link active" id="valida-solicitud-tab" data-toggle="pill" href="#valida-solicitud"
                    role="tab" aria-controls="valida-solicitud" aria-selected="true">SOLICITUDES</a>
            </li>



            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="valida-terminado-tab" data-toggle="pill" href="#valida-terminado" role="tab"
                    aria-controls="valida-terminado" aria-selected="false">ATENDIDAS</a>
            </li>
        </ul>

        <div class="tab-content" id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="valida-solicitud" role="tabpanel"
                aria-labelledby="valida-solicitud-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No Memorándum</th>
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
                                                <td>{{ $solicitud->created_at }}</td>
                                                <td>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('memorandum.validar', ['memorandum' => $solicitud, 'area' => $area]) }}">Validar</a>
                                                </td>
                                            </tr>
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
            <div class="tab-pane fade" id="valida-terminado" role="tabpanel" aria-labelledby="valida-terminado-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No Validación</th>
                                                <th>Razon Social</th>
                                                <th>RFC</th>
                                                <th>Contacto</th>
                                                <th>Fecha de validación</th>
                                                <th>Validación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($terminadas as $solicitud)
                                            <tr>
                                                <td>{{ $solicitud->id }}</td>
                                                <td>{{ $solicitud->memorandum->cliente->razon_social }}</td>
                                                <td>{{ $solicitud->memorandum->cliente->rfc_cliente }}</td>
                                                <td>{{ $solicitud->memorandum->cliente->user->name .
                                                    ' ' .
                                                    $solicitud->memorandum->cliente->user->paterno .
                                                    ' ' .
                                                    $solicitud->memorandum->cliente->user->materno }}
                                                </td>
                                                <td>{{ $solicitud->created_at }}</td>
                                                <td>
                                                    <i class="fa fa-circle"
                                                        style="color: {{ $solicitud->status_validacion_memoranda == 1 ? 'green' : 'orange' }};">
                                                    </i>

                                                </td>
                                            </tr>
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
