<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <form>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputId">Id</label>
                                    <input type="text" class="form-control" id="inputId" placeholder="Ingresa el Id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="razonsocial">Razon Social</label>
                                    <input type="text" class="form-control" id="razonsocial"
                                        placeholder="Ingrese razon social">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputFechaInicio">Fecha Inicio</label>
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
                                    <th class="col-md-2 text-center">No.</th>
                                    <th class="col-md-4 text-center">Razon Social</th>
                                    <th class="col-md-1 text-center">Fecha de Solicitud</th>
                                    <th class="col-md-1 text-center">Dictamen</th>
                                    <th class="col-md-2 text-center">Fecha Dictamen</th>
                                    <th class="col-md-1 text-center">Validación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listSolicitudes as $item)
                                <tr>
                                    <td class="col-md-2 text-center">{{ $item->expediente_digital_id }}</td>
                                    <td class="col-md-4 text-center">{{ $item->razon_social }}</td>
                                    <td class="col-md-1 text-center">{{ $item->fecha_solicitud }}</td>
                                    <th class="col-md-1 text-center">
                                        @if($item->dictamen == 1)
                                            <i class="fas fa-circle text-success"></i>
                                        @else
                                            <i class="fas fa-circle text-danger"></i>
                                        @endif
                                    </th>
                                    <th class="col-md-2 text-center">{{ $item->fecha_dictamen }}</th>
                                    @if($item->dictamen==1)
                                    <td class="col-md-1 text-center">Validado</td>
                                    @else
                                    <td class="col-md-1 text-center">
                                    <a href="{{route('clientesactivos.altaSolicitudCumplimiento',$item->id)}}" class="btn btn-primary">Editar Expediente</a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal-pdf-creado title="Vista Previa Dictamen" pdfUrl="{{ $pdfUrl }}" wire:ignore.self>
    </x-modal-pdf>
    
</div>