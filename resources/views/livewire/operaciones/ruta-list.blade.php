<div>
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 for="">RUTAS</h3>

    </div>
    <div class="">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <!-- Pestaña "Servicios" -->
            <li class="nav-item">
                <a class="nav-link active" id="rutas-all-tab" data-toggle="pill" href="#rutas-all" role="tab"
                    aria-controls="rutas-all" aria-selected="true">TODAS LAS RUTAS</a>
            </li>



            <!-- Pestaña "Otra Pestaña 2" -->
            <li class="nav-item">
                <a class="nav-link" id="rutas-dia-tab" data-toggle="pill" href="#rutas-dia" role="tab"
                    aria-controls="rutas-dia" aria-selected="false">RUTAS DEL DIA</a>
            </li>
        </ul>

        <div class="tab-content" id="custom-tabs-one-tabContent">
            <!-- Contenido de la pestaña "anexo1coti" -->
            <div class="tab-pane fade show active" id="rutas-all" role="tabpanel" aria-labelledby="rutas-all-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Dia</th>
                                                <th>Riesgo</th>
                                                <th>Estado</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Finalización</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rutas as $ruta)
                                                <tr>
                                                    <td>{{ $ruta->id }}</td>
                                                    <td>{{ $ruta->nombre->name }}</td>
                                                    <td>{{ $ruta->dia->name }}</td>
                                                    <td>{{ $ruta->riesgo->name }}</td>
                                                    <td>{{ $ruta->estado->name }}</td>
                                                    <td>{{ $ruta->hora_inicio }}</td>
                                                    <td>{{ $ruta->hora_fin }}</td>
                                                    <td>
                                                        <a href="{{ route('ruta.gestion', [2, $ruta]) }}">Detalles</a>
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
            <div class="tab-pane fade" id="rutas-dia" role="tabpanel" aria-labelledby="rutas-dia-tab">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-outline card-info">

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
                                        {{-- <tbody>
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
                                            </td>
                                        @endforeach --}}
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
