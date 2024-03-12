<div>
    <div class="row">
        <div class="col-md-6">
            <h3 for="">Memorándum de servicios</h3>
        </div>
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputId">Id</label>
                                    <input type="text" class="form-control" id="inputId"
                                        placeholder="Ingresa la Id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputFechaInicio">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="inputFechaInicio"
                                        placeholder="Ingresa el Fecha Inicio">
                                </div>
                            </div>

                            <div class="col-md-3">
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

                                @if ($solicitudes)
                                    @foreach ($solicitudes as $solicitud)
                                        <td>{{ $solicitud->id }}</td>
                                        <td>{{ $solicitud->cliente->razon_social }}</td>
                                        <td>{{ $solicitud->cliente->rfc }}</td>
                                        <td>{{ $solicitud->cliente->user->name .
                                            ' ' .
                                            $solicitud->cliente->user->paterno .
                                            ' ' .
                                            $solicitud->cliente->user->materno }}
                                        </td>
                                        <td>{{ $solicitud->updated_at }}</td>
                                        <td>
                                            <a  href="{{ route('ventas.memorandum', $solicitud) }}">Llenar memorandum</a>
                                        </td>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
