<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Razón Social:" placeholder="Ingrese la Razón Social"
                                    wire-model="razonSocial" wire-attribute="razonSocial" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="RFC" placeholder="Ingrese el rfc" wire-model="rfc"
                                    wire-attribute="rfc" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Tipo de cliente:" placeholder="Ingrese tipo de cliente"
                                    wire-model="ctg_tipo_cliente_id" wire-attribute="ctg_tipo_cliente_id" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Puesto:" placeholder="Ingrese el Puesto" wire-model="puesto"
                                    wire-attribute="puesto" type="text" />
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
                                                    <!-- Aquí debes colocar el enlace o el método para la vista previa -->
                                                    <a href="">Vista Previa</a>
                                                </td>
                                                <td>
                                                    <!-- Input para marcar si cumple -->
                                                   <input class="form-control" wire:model.live="cumple.{{ $documento->id }}" type="radio" value="1">
                                                </td>
                                                <td>
                                                    <!-- Input para marcar si no cumple -->
                                                   <input class="form-control" wire:model.live="cumple.{{ $documento->id }}" type="radio" value="0">
                                                </td>
                                                <td>
                                                    <!-- Campo de texto para observaciones -->
                                                    <textarea wire:model.live="nota.{{ $documento->id }}" type="text" name="" class="form-control"></textarea>
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
                <a href="https://qeq.com.mx/datos/pages/" target="_blank" class="btn btn-secondary btn-block "> QUIEN ES QUIEN</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://www.gob.mx/curp/" target="_blank" class="btn btn-secondary btn-block ">CURP</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://listanominal.ine.mx/scpln/" target="_blank" class="btn btn-secondary btn-block ">INE</a>
            </div>
            <div class="col-md-3 M-1">
                <a href="https://agsc.siat.sat.gob.mx/PTSC/ValidaRFC/index.jsf" target="_blank" class="btn btn-secondary btn-block ">RFC</a>
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
                            <x-select-validado label="" placeholder="Seleccione"
                                    wire-model="condicionpago" required>
                                    
                                </x-select-validado>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="archivo">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="input-group">
                                    <input type="submit" class="btn btn-secondary btn-block" name="subirDoc" value="Subir" />
                                  </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>