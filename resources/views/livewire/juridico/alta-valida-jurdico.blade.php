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
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <h3 class="text-center mb-2">Documentos Beneficiario</h3>
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
                                        @foreach($documentosexpedienteBene as $documentobene)
                                        <tr>
                                            <td>{{ $documentobene->name }}</td>
                                            <td>{{ $documentobene->document_name }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#modalpdf"
                                                    wire:click="openModal('documentos/{{$rfc}}/beneficiario/{{$documentobene->document_name}}')"
                                                    style="text-decoration: none;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <!-- Input para marcar si cumple -->
                                                <input class="form-control"
                                                    wire:model.live="cumplebene.{{ $documentobene->id }}" type="radio"
                                                    value="1">
                                            </td>
                                            <td>
                                                <!-- Input para marcar si no cumple -->
                                                <input class="form-control"
                                                    wire:model.live="cumplebene.{{ $documentobene->id }}" type="radio"
                                                    value="0">
                                            </td>
                                            <td>
                                                <!-- Campo de texto para observaciones -->
                                                <textarea wire:model.live="notabene.{{ $documentobene->id }}" type="text"
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
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                @if($aceptadoOrNegado==2)
                <button type="button" class="btn btn-primary btn-lg btn-block" wire:click="aceptadaValida" data-toggle="modal" data-target="#exampleModalCenter">
                    Generar dictamen
                </button>
                @else
                <button type="button" class="btn btn-primary btn-lg btn-block" wire:click="negadaValida" data-toggle="modal" data-target="#exampleModalCenter2" @if($aceptadoOrNegado == 0) disabled @endif>
                    Generar dictamen
                </button>
                @endif
            </div>
        </div>
    </div>    
</div>