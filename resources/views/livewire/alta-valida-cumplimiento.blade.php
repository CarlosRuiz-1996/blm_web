<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Razón Social:"
                                    placeholder="Ingrese la Razón Social" wire-model="razonSocial"
                                    wire-attribute="razonSocial" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="RFC" placeholder="Ingrese el rfc"
                                    wire-model="rfc" wire-attribute="rfc" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Tipo de cliente:"
                                    placeholder="Ingrese tipo de cliente" wire-model="ctg_tipo_cliente_id"
                                    wire-attribute="ctg_tipo_cliente_id" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Puesto:" placeholder="Ingrese el Puesto"
                                    wire-model="puesto" wire-attribute="puesto" type="text" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--tabla documentacion-->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>DOCUMENTO</th>
                                            <th>NOMBRE DOCUMENTO</th>
                                            <th>VISTA PREVIA</th>
                                            <th>CUMPLE</th>
                                            <th>NO CUMPLE</th>
                                            <th>OBSERVACIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentosexpediente as $documento)
                                        <tr>
                                            <td>{{ $documento->name }}</td>
                                            <td>{{ $documento->document_name }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#modalpdf"
                                                    wire:click="openModal('documentos/{{$rfc}}/{{$documento->document_name}}')"
                                                    style="text-decoration: none;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <!-- Input para marcar si cumple -->
                                                <input class="form-control"
                                                    wire:model.live="cumple.{{ $documento->id }}" type="radio"
                                                    value="1">
                                            </td>
                                            <td>
                                                <!-- Input para marcar si no cumple -->
                                                <input class="form-control"
                                                    wire:model.live="cumple.{{ $documento->id }}" type="radio"
                                                    value="0">
                                            </td>
                                            <td>
                                                <!-- Campo de texto para observaciones -->
                                                <textarea wire:model.live="nota.{{ $documento->id }}" type="text"
                                                    name="" class="form-control"></textarea>
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
            <div class="col-md-3 M-1">
                <a href="https://qeq.com.mx/datos/pages/" target="_blank" class="btn btn-secondary btn-block "> QUIEN ES
                    QUIEN</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://www.gob.mx/curp/" target="_blank" class="btn btn-secondary btn-block ">CURP</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://listanominal.ine.mx/scpln/" target="_blank"
                    class="btn btn-secondary btn-block ">INE</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://agsc.siat.sat.gob.mx/PTSC/ValidaRFC/index.jsf" target="_blank"
                    class="btn btn-secondary btn-block ">RFC</a>
            </div>
        </div>
        <!--validacion docuemntos listas negras-->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Evidencia Documental</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <x-select-validado label="" placeholder="Seleccione tipo de documento"
                                    wire-model="evidencias" required>
                                    <option value="1">Listas Negras</option>
                                </x-select-validado>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="custom-file mt-2">
                                    <input type="file" class="custom-file-input" wire:model="documentoevidencia"
                                        accept="application/pdf">
                                    <label class="custom-file-label" for="documentoevidencia">Seleccione un
                                        archivo</label>
                                    @error('documentoevidencia') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="input-group">
                                    <button wire:click="agregarEvidencia" class="btn btn-primary mt-3">Subir
                                        Archivo</button>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>NOMBRE DOCUMENTO</th>
                                        <th>VISTA PREVIA</th>
                                        <th>CUMPLE</th>
                                        <th>NO CUMPLE</th>
                                        <th>OBSERVACIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentosEvidencias as $documentosevi)
                                    <tr>
                                        <td>{{ $documentosevi->document_name }}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#modalpdf"
                                                wire:click="openModal('documentos/{{$rfc}}/evidencias/{{$documentosevi->document_name}}')"
                                                style="text-decoration: none;">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <!-- Input para marcar si cumple -->
                                            <input class="form-control"
                                                wire:model.live="cumpleevidencias.{{ $documentosevi->id }}" type="radio"
                                                value="1">
                                        </td>
                                        <td>
                                            <!-- Input para marcar si no cumple -->
                                            <input class="form-control"
                                                wire:model.live="cumpleevidencias.{{ $documentosevi->id }}" type="radio"
                                                value="0">
                                        </td>
                                        <td>
                                            <!-- Campo de texto para observaciones -->
                                            <textarea wire:model.live="notaevidencias.{{ $documentosevi->id }}"
                                                type="text" name="" class="form-control"></textarea>
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
        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                @if($aceptadoOrNegado==2)
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModalCenter">
                    Generar dictamen
                </button>
                @else
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModalCenter2" @if($aceptadoOrNegado == 0) disabled @endif>
                    Generar dictamen
                </button>
                @endif
            </div>
        </div>
    </div>
    <x-modal-pdf title="PDF Modal" pdfUrl="{{ $pdfUrl }}" wire:ignore.self>
    </x-modal-pdf>


    <!-- Modal aceptado -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" wire:ignore.self
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLongTitle">Dictamen Aceptado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('img/logospdf.png') }}" alt="Nombre alternativo"
                        class="img-fluid mx-auto d-block mb-3" style="max-width: 110px;">
                    <h3 class="text-primary">Servicios Integrados PRO-BLM de México S.A. de C.V.</h3>
                    <h6 class="text-justify mt-2">
                        Después de haber revisado los datos en la Carta de Ley Antilavado, validado los documentos para
                        la integración del expediente único y realizar la verificación del cliente y sus relacionados en el
                        Web Service de Q&Q, confirmando que no existen antecedentes negativos en listas negras. <br>
                        Se otorga el VoBo para continuar con el proceso de contratación del cliente: AUTOTRANSPORTES
                        METROPOLITANOS DEL ORIENTE PERIFÉRICO, S.A. DE C.V.
                    </h6>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Si</th>
                                    <th>No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ctg_aceptado as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <input class="form-control" type="radio" wire:model.live="aceptadoValid.{{ $item->id }}" value="1">
                                    </td>
                                    <td>
                                        <input class="form-control" type="radio" wire:model.live="aceptadoValid.{{ $item->id }}" value="0">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <h6 class="text-justify mt-4">
                        Sin mas por el momento, atento a cualquier duda o aclaracion. <br>
                        Responsable de Cumplimiento. <br>
                        Servicios Integrados PRO-BLM de Mexico, S.A. de C.V. <br>
                    </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="aceptadaValida">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal negado-->
    <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" wire:ignore.self
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLongTitle">Dictamen Rechazo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('img/logospdf.png') }}" alt="Nombre alternativo"
                    class="img-fluid mx-auto d-block mb-3" style="max-width: 110px;">
                <h3 class="text-primary">Servicios Integrados PRO-BLM de México S.A. de C.V.</h3>
                <h6 class="text-justify mt-4">
                    Después de haber revisado los datos en la Carta de Ley Antilavado, validado los documentos para la integración del expediente único y realizar 
                    la verificación del cliente y sus relacionados en el Web Service de Q&Q, No se otorga el VoBo para continuar con el proceso de contratación del cliente:
                     (AUTOTRANSPORTES METROPOLITANOS DEL ORIENTE PERIFERICO, S.A. DE C.V.), debido al siguiente motivo:
                </h6>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                                <th>Motivo de Rechazo</th>
                                <th>Seleccione</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ctg_rechazo as $itemrechazado)
                            <tr>
                                <td>{{ $itemrechazado->name }}</td>
                                <td>
                                    <input class="form-control" type="radio" wire:model.live="aceptado" wire:click="setAceptado({{ $itemrechazado->id }})" value="{{ $itemrechazado->id }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h6 class="text-justify mt-4">
                    Sin mas por el momento, atento a cualquier duda o aclaracion. <br>
                    Responsable de Cumplimiento.<br>
                    Servicios Integrados PRO-BLM de Mexico, S.A. de C.V.<br>
                </h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="negadaValida" >Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</div>