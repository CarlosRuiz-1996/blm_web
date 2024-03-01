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
                                <x-input-validado label="Razón Social:" placeholder="Ingrese la Razón Social"
                                    wire-model="razonSocial" wire-attribute="razonSocial" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="RFC" placeholder="Ingrese el rfc" wire-model="rfc"
                                    wire-attribute="rfc" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Tipo de cliente:" placeholder="Ingrese tipo de cliente"
                                    wire-model="ctg_tipo_cliente_id" wire-attribute="ctg_tipo_cliente_id" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Puesto:" placeholder="Ingrese el Puesto" wire-model="puesto"
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
                                <x-input-validado label="Pais:" placeholder="Ingrese pais"
                                    wire-model="pais" wire-attribute="pais" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Estado:" placeholder="esperando..." wire-model="estados"
                                    wire-attribute="estados" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <x-input-validado label="Alcaldia/Municipio:" placeholder="esperando..."
                                        wire-model="municipios" wire-attribute="municipios" type="text" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Codigo Postal:" placeholder="Ingrese codigo postal"
                                    wire-model="cp" wire-attribute="cp" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Colonia:" placeholder="Ingrese la Colonia"
                                    wire-model="colonia" wire-attribute="colonia" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Calle y Número:" placeholder="Ingrese la Calle y Número"
                                    wire-model="calleNumero" wire-attribute="calleNumero" type="text" />
                            </div>

                            <!-- Información de contacto -->
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Telefono:" placeholder="Ingrese telefono" wire-model="telefono"
                                    wire-attribute="telefono" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Correo Electrónico:" placeholder="Ingrese Correo Electronico"
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
                            <h4>Documentos Requeridos</h4>
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
                                        <td class="col-md-3">
                                            {{ $doc->name }}
                                        </td>
                                        <td class="col-md-5">
                                            @if ($tuVariable)
                                                <a href="{{ route('ruta.descarga', $tuVariable) }}" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Descargar PDF
                                                </a>
                                            @else
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" wire:model="pdfArchivo">
                                                        <label class="custom-file-label" for="pdfArchivo">Seleccionar Archivo PDF</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <button wire:click="agregarArchivo" class="btn btn-primary">Subir Archivo</button>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="col-md-2 text-center f-2">
                                            @if($tuVariable)
                                                <i class="fas fa-circle text-success"></i>
                                            @else
                                                <i class="fas fa-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="col-md-2">
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
                            <h4>Documentos Requeridos</h4>
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
                                    @foreach ($documentos_beneficiarios as $doc)
                                        <tr>
                                            <td class="col-md-3">
                                                {{ $doc->name }}
                                            </td>
                                            <td class="col-md-5">
                                                @if (isset($tuVariable[$doc->id]))
                                                    dsd
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" wire:model="archivos.{{ $doc->id }}">
                                                                <label class="custom-file-label" for="archivos.{{ $doc->id }}">Seleccionar Archivo PDF</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button wire:click="agregarArchivo({{ $doc->id }})" class="btn btn-primary">Subir Archivo</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="col-md-2 text-center f-2">
                                                @if(isset($archivos[$doc->id]))
                                                    <i class="fas fa-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="col-md-2">
                                                <button wire:click="eliminarArchivo({{ $doc->id }})" class="btn btn-danger">Eliminar</button>
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
        <div class="row mb-5">
            <div class="col-md-12">
                <button wire:click='validaInfo' class="btn btn-info btn-block ">Generar Solicitud</button>
            </div>
        </div>
    </div>
</div>
