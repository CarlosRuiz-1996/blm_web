<div>
    <div class="row">
        <div class="col-md-10">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="info-empleado-tab" data-toggle="tab" href="#info-empleado" role="tab" aria-controls="info-empleado" aria-selected="true">Información del Empleado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="emergencias-tab" data-toggle="tab" href="#emergencias" role="tab" aria-controls="emergencias" aria-selected="false">Emergencias</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="salud-tab" data-toggle="tab" href="#salud" role="tab" aria-controls="salud" aria-selected="false">Salud</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <!-- Información del Empleado -->
                        <div class="tab-pane fade show active" id="info-empleado" role="tabpanel" aria-labelledby="info-empleado-tab">
                            <h5><b>Nombre Empleado:</b> {{ $empleado->user->name . ' ' . $empleado->user->paterno . ' ' . $empleado->user->materno }}.</h5>
                            <h5><b>Clave:</b> {{ optional($empleado)->cve_empleado ?? 'Sin información' }}.</h5>
                            <h5><b>Sexo:</b> {{ optional($empleado)->sexo ?? 'Sin información' }}.</h5>
                            <h5><b>Correo:</b> {{ optional($empleado->user)->email ?? 'Sin información' }}.</h5>
                            <h5><b>Edad:</b> {{ optional($empleado)->fecha_nacimiento ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age . ' Años' : 'Sin información' }}</h5>
                        </div>
                        <!-- Emergencias -->
                        <div class="tab-pane fade" id="emergencias" role="tabpanel" aria-labelledby="emergencias-tab">
                            <h5><b>Nombre Emergencia 1:</b> {{ optional($empleado)->nombre_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Teléfono Emergencia 1:</b> {{ optional($empleado)->telefono_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Parentesco Emergencia 1:</b> {{ optional($empleado)->parentesco_emergencia1 ?? 'Sin información' }}.</h5>
                            <h5><b>Dirección Emergencia 1:</b> {{ optional($empleado)->direccion_emergencia1 ?? 'Sin información' }}.</h5>
                        
                            <h5><b>Nombre Emergencia 2:</b> {{ optional($empleado)->nombre_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Teléfono Emergencia 2:</b> {{ optional($empleado)->telefono_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Parentesco Emergencia 2:</b> {{ optional($empleado)->parentesco_emergencia2 ?? 'Sin información' }}.</h5>
                            <h5><b>Dirección Emergencia 2:</b> {{ optional($empleado)->direccion_emergencia2 ?? 'Sin información' }}.</h5>
                        </div>
                        <!-- Salud -->
                        <div class="tab-pane fade" id="salud" role="tabpanel" aria-labelledby="salud-tab">
                            <h5><b>Alergias:</b> {{ optional($empleado)->alergias ?? 'Sin información' }}.</h5>
                            <h5><b>Tipo de Sangre:</b> {{ optional($empleado)->tipo_sangre ?? 'Sin información' }}.</h5>
                            <h5><b>UMF:</b> {{ optional($empleado)->umf ?? 'Sin información' }}.</h5>
                            <h5><b>Hospital:</b> {{ optional($empleado)->hospital ?? 'Sin información' }}.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="text-center d-flex justify-content-center">
                        @if(file_exists(storage_path('app/public/fotosEmpleados/' . $empleado->id . '.png')))
                        <img src="{{ asset('storage/fotosEmpleados/' . $empleado->id . '.png') }}"
                            alt="Imagen del empleado" width="200" height="117">
                        @else
                        <img src="{{ asset('img/sinfoto.png') }}" alt="Imagen por defecto" width="200" height="117">
                        @endif
                    </div>
                    @if ($empleado->status_empleado == 1)
                    <button wire:click='desactivarempleado({{$empleado->id}})' class="btn btn-danger btn-block mt-1">Desactivar</button>
                    @else
                    <button wire:click='activarempleado({{$empleado->id}})' class="btn btn-success btn-block mt-1">Activar</button>
                    @endif
                    <button wire:click="openModal({{ $empleado->id }})" class="btn btn-success btn-block mt-1">Editar</button>
                </div>
            </div>
        </div>

    <div class="col-md-12">
        <div class="card card-outline card-info">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#documentos">Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#vacaciones">Vacaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#herramientas">Herramientas de Trabajo</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="documentos" class="tab-pane fade show active">
                    @livewire('rh.empleados-expediente-digital', ['empleadoId' => $empleado->id])
                </div>
                <div id="vacaciones" class="tab-pane fade">
                    @livewire('rh.empleados-vacaciones-perfil', ['empleadoId' => $empleado->id])
                </div>
                <div id="herramientas" class="tab-pane fade">
                    @livewire('rh.empleadosherramientas', ['empleadoId' => $empleado->id])
                </div>
            </div>
        </div>
    </div>
</div>
<div
x-data="{ show: @entangle('isOpenempleado') }"
x-init="
    $watch('show', value => {
        if (value) {
            $('#myModaldosempleados').modal('show');
        } else {
            $('#myModaldosempleados').modal('hide');
        }
    });
    $('#myModaldosempleados').on('hidden.bs.modal', () => {
        if (show) {
            show = false;
        }
    });
"
id="myModaldosempleados"
class="modal fade @if($isOpenempleado) show @endif"
tabindex="-1"
aria-labelledby="myModaldosLabel"
aria-hidden="true"
wire:ignore.self
style="@if($isOpenempleado) display: block; @endif"
>
<div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%; width: 1200px;">
    <div class="modal-content">
        <div class="modal-header bg-info text-white text-center">
            <h5 class="modal-title" id="myModaldosLabel">Editar Empleado</h5>
        </div>
        <div class="modal-body">
            @if($isOpenempleado)
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Datos Personales</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3 text-center">
                                        <!-- Mostrar la vista previa de la imagen cuando se haya cargado un archivo temporal -->
                                        @if(file_exists(storage_path('app/public/fotosEmpleados/' . $empleado->id . '.png')))
                                            <img src="{{ asset('storage/fotosEmpleados/' . $empleado->id . '.png') }}"
                                                 alt="Imagen del empleado" width="200" height="117" class="img-fluid img-thumbnail">
                                        @else
                                            @if ($foto)
                                                <div class="mt-3">
                                                    <p>Vista previa:</p>
                                                    <img src="{{ $foto->temporaryUrl() }}" alt="Vista previa de la imagen" 
                                                         class="img-fluid img-thumbnail" style="max-height: 200px;">
                                                </div>
                                            @else
                                                <img src="{{ asset('img/sinfoto.png') }}" alt="Imagen por defecto" width="200" height="117" 
                                                     class="img-fluid img-thumbnail">
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="foto">Subir Foto</label>
                                        <input type="file" id="foto" wire:model.live="foto" accept="image/*" class="form-control">
                                        @error('foto') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Nombre del contacto:"
                                            placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                            type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Apellido Paterno:" placeholder="Ingrese el Apellido paterno"
                                            wire-model="apepaterno" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Apellido Materno:" placeholder="Ingrese el Apellido Materno"
                                            wire-model="apematerno" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Clave:" placeholder="Ingrese la clave"
                                            wire-model="cve_empleado" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-telefono label="Teléfono:" placeholder="Ingrese telefono"
                                            wire-model="telefono" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Correo Electrónico:" placeholder="Ingrese Correo Electrónico"
                                            wire-model="correoElectronico" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Información Adicional</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="fechaIngreso">Fecha De Nacimiento</label>
                                        <input label="Fecha Nacimiento:"
                                            placeholder="Ingrese fecha de nacimiento" wire:model="fechaNacimiento"
                                            type="date" class="form-control" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-select-validado label="Sexo:" placeholder="Seleccione" wire-model="sexo" required>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </x-select-validado>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="fechaIngreso">Fecha Ingreso</label>
                                        <input label="Fecha Ingreso:" placeholder="Ingrese fecha que ingresa"
                                            wire:model="fechaIngreso" type="date" class="form-control" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Código Postal:" placeholder="Ingrese el código postal" 
                                            wire-model="cp" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3 mt-2">
                                        <div class="form-group ">
                                            <label></label>
                                            <button wire:click='validarCp'
                                                class="btn btn-secondary btn-block">Validar
                                                cp</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Estado:" :readonly="true"
                                            placeholder="esperando..." wire-model="estados"
                                            wire-attribute="estados" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Alcaldía/Municipio:" :readonly="true"
                                            placeholder="esperando..." wire-model="municipios"
                                            wire-attribute="municipios" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-select-validado label="Colonia:" placeholder="Seleccione"
                                            wire-model="ctg_cp_id" required>
                                            @if (!empty($colonias))
                                                @foreach ($colonias as $cp)
                                                    <option value="{{ $cp->id }}">{{ $cp->colonia }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Esperando...</option>
                                            @endif
                                        </x-select-validado>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Calle y Número:" placeholder="Ingrese calle y número"
                                            wire-model="calleNumero" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Contactos de Emergencia</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Nombre Emergencia 1:" placeholder="Ingrese nombre" 
                                            wire-model="nombreEmergencia1" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-telefono label="Teléfono Emergencia 1:" placeholder="Ingrese teléfono" 
                                            wire-model="telefonoEmergencia1" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Parentesco Emergencia 1:" placeholder="Ingrese parentesco"
                                            wire-model="parentescoEmergencia1" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Dirección Emergencia 1:" placeholder="Ingrese dirección"
                                            wire-model="direccionEmergencia1" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Nombre Emergencia 2:" placeholder="Ingrese nombre"
                                            wire-model="nombreEmergencia2" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado-telefono label="Teléfono Emergencia 2:" placeholder="Ingrese teléfono" 
                                            wire-model="telefonoEmergencia2" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Parentesco Emergencia 2:" placeholder="Ingrese parentesco"
                                            wire-model="parentescoEmergencia2" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Dirección Emergencia 2:" placeholder="Ingrese dirección"
                                            wire-model="direccionEmergencia2" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Información Médica</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Alergias:" placeholder="Ingrese alergias"
                                            wire-model="alergias" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-select-validado label="Tipo de Sangre:" placeholder="Seleccione tipo" wire-model="tipoSangre">
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </x-select-validado>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="UMF:" placeholder="Ingrese UMF"
                                            wire-model="umf" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Hospital:" placeholder="Ingrese hospital"
                                            wire-model="hospital" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Información Ropa</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Talla Camisa:" placeholder="Ingrese alergias"
                                            wire-model="tallaCamisa" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Talla Pantalon:" placeholder="Ingrese alergias"
                                            wire-model="tallaPantalon" type="text" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Talla zapatos:" placeholder="Ingrese alergias"
                                            wire-model="tallaZapatos" type="text" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-block" wire:click='guardarInformacion'>Guardar Información</button>
                    </div>
                </div>        
            </div>
            
@endif
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="closeModal()">Cerrar</button>
        </div>
    </div>
</div>
</div>
</div>