<div>
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
                                    <form wire:submit.prevent="buscar">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputId">Id</label>
                                                    <input wire:model="idsoli" type="text" class="form-control" id="inputId" placeholder="Ingresa la Id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputcotizacionanexo">Cotización</label>
                                                    <input wire:model="cotizacionsoli" type="text" class="form-control" id="inputcotizacionanexo" placeholder="Ingresa Cotización">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputFechaInicio">Fecha Inicio</label>
                                                    <input wire:model="fechaIniciosoli" type="date" class="form-control" id="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputFechafin">Fecha Fin</label>
                                                    <input wire:model="fechaFinsoli" type="date" class="form-control" id="inputFechafin" placeholder="Ingresa Fecha fin">
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
                                            @if($solicitudes)
                                                    @foreach ($solicitudes as $solicitud)
                                                        <tr>
                                                            <td>{{ $solicitud->id }}</td>
                                                            <td>{{ $solicitud->cliente->razon_social ?? 'N/A' }}</td>
                                                            <td>{{ $solicitud->cliente->rfc_cliente ?? 'N/A' }}</td>
                                                            <td>{{ ($solicitud->cliente->user->name ?? '') . ' ' . ($solicitud->cliente->user->paterno ?? '') . ' ' . ($solicitud->cliente->user->materno ?? '') }}</td>
                                                            <td>{{ $solicitud->updated_at }}</td>
                                                            <td>
                                                                <a href="{{ route('anexo.index', $solicitud->id) }}">Comenzar anexo1</a>
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
