<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Cliente</h3>
                    </div>
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
                                    wire-model="tipocliente" wire-attribute="tipocliente" type="text" />
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
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Domicilio fiscal</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Pais:" placeholder="Ingrese pais"
                                    wire-model="pais" wire-attribute="pais" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Estado:" placeholder="esperando..." wire-model="estados"
                                    wire-attribute="estados" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <x-input-validado :readonly="true" label="Alcaldia/Municipio:" placeholder="esperando..."
                                        wire-model="municipios" wire-attribute="municipios" type="text" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Codigo Postal:" placeholder="Ingrese codigo postal"
                                    wire-model="cp" wire-attribute="cp" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Colonia:" placeholder="Ingrese la Colonia"
                                    wire-model="colonia" wire-attribute="colonia" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Calle y Número:" placeholder="Ingrese la Calle y Número"
                                    wire-model="calleNumero" wire-attribute="calleNumero" type="text" />
                            </div>

                            <!-- Información de contacto -->
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Telefono:" placeholder="Ingrese telefono" wire-model="telefono"
                                    wire-attribute="telefono" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado :readonly="true" label="Correo Electrónico:" placeholder="Ingrese Correo Electronico"
                                    wire-model="correoElectronico" wire-attribute="correoElectronico" type="text" />
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
                    <div class="card-header">
                        <h3 class="text-center">Documentación</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-4">
                            <x-select-validado label="Documento:" placeholder="Seleccione tipo de documento" wire-model="documentoid"
                            required>
                            @foreach ($documentos as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                            @endforeach
                            </x-select-validado>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="custom-file mt-3">
                                    <input type="file" class="custom-file-input" wire:model="documentoSelec" id="docuemntosselec" accept="application/pdf">
                                    <label class="custom-file-label" for="docuemntosselec">Seleccione un archivo</label>
                                    @error('documentoSelec') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <button wire:click="agregarArchivo" class="btn btn-primary mt-3">Subir Archivo</button>
                            </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="col-md-5">Documento</th>
                                        <th class="col-md-4">Archivo Adjunto</th>
                                        <th class="col-md-2 text-center">Estatus</th>
                                        <th class="col-md-1">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentos as $doc)
                                        <tr>
                                            <td class="col-md-5">{{ $doc->name }}</td>
                                            <td class="col-md-4">
                                                @php $documentoEncontrado = false; @endphp
                                                @foreach ($documentosexpediente as $documento)
                                                    @if ($documento->ctg_documentos_id == $doc->id)
                                                        {{ $documento->document_name }}
                                                        @php $documentoEncontrado = true; @endphp
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="col-md-2 text-center f-2">
                                                @if ($documentoEncontrado)
                                                    <i class="fas fa-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="col-md-1">
                                                <button wire:click="eliminarArchivo()" class="btn btn-danger">Eliminar</button>
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
        <!--validacion docuemntos Beneficiarios-->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Documentos del beneficiario (OPCIONAL)</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-2 mt-5">
                                    <label for="checkbene">Habilitar Beneficiario</label>
                                    <input type="checkbox" name="checkbene" id="checkbene" wire:model.live='checkbeneficiario'>
                                </div>                                
                                <div class="col-md-4" {{ $habilitados ? 'hidden' : '' }}>
                            <x-select-validado label="Documento:" placeholder="Seleccione tipo de documento" wire-model="documentoidbene"
                            required >
                            @foreach ($documentos_beneficiarios as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                            @endforeach
                            </x-select-validado>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="custom-file mt-3" {{ $habilitados ? 'hidden' : '' }} >
                                    <input type="file" class="custom-file-input" wire:model="documentoSelecbene" id="docuemntosselecbene" accept="application/pdf">
                                    <label class="custom-file-label" for="docuemntosselec">Seleccione un archivo</label>
                                    @error('documentoSelecbene') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-2 mt-3" {{ $habilitados ? 'hidden' : '' }}>
                                <button wire:click="agregarArchivoBeneficiario" class="btn btn-primary mt-3">Subir Archivo</button>
                            </div>
                            </div>
                            <table class="table" {{ $habilitados ? 'hidden' : '' }}>
                                <thead>
                                    <tr>
                                        <th class="col-md-5">Documento</th>
                                        <th class="col-md-4">Archivo Adjunto</th>
                                        <th class="col-md-2 text-center">Estatus</th>
                                        <th class="col-md-1">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentos_beneficiarios as $doca)
                                        <tr>
                                            <td class="col-md-3">
                                                {{ $doca->name }}
                                            </td>
                                            <td class="col-md-4">
                                                @php $documentoEncontradobe = false; @endphp
                                                @foreach ($documentosexpedienteBene as $documentobene)
                                                    @if ($documentobene->ctg_documentos_benf_id == $doca->id)
                                                    <button data-toggle="modal" data-target="#modalpdf" wire:click="openModal('documentos/{{$rfc}}/beneficiario/{{$documentobene->document_name}}')" > {{ $documentobene->document_name}}</button>
                                                       
                                                    </a>
                                                        @php $documentoEncontradobe = true; @endphp
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="col-md-2 text-center f-2">
                                                @if ($documentoEncontradobe)
                                                    <i class="fas fa-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="col-md-1">
                                                <button wire:click="eliminarArchivo()" class="btn btn-danger">Eliminar</button>
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
    <script>
        document.getElementById('docuemntosselec').addEventListener('change', function() {
            var fileName = this.files[0].name;
            var label = document.querySelector('.custom-file-label');
            label.innerText = fileName;
        });
    </script>

    <x-modal-pdf title="PDF Modal" pdfUrl="{{ $pdfUrl }}" wire:ignore.self>
    </x-modal-pdf>
  
    
</div>
