<!-- resources/views/livewire/empleado-form.blade.php -->

<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Empleado</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="position-relative">
                                    <!-- Imagen cargada -->
                                    @if ($image)
                                    <div class="text-center d-flex justify-content-center">
                                        <img src="{{ asset($image->temporaryUrl()) }}" alt="Imagen cargada" width="200"
                                            height="200">
                                    </div>
                                    @else
                                    <!-- Imagen por defecto -->
                                    <div class="text-center d-flex justify-content-center">
                                        <img src="{{ asset('img/sinfoto.png') }}" alt="Imagen por defecto" width="200"
                                            height="200">
                                    </div>
                                    @endif
                                    <!-- Icono del input -->
                                    <div class="position-absolute" style="top: 0; right: 35%;">
                                        <label class="image" for="image-id"><i
                                                class="fa fa-lg fa-fw fa-plus"></i></label>
                                        <input type="file" class="image" id="image-id" accept="image/*" hidden
                                            wire:model.live="image">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Apellido Paterno:" placeholder="Ingrese el Apellido paterno"
                                    wire-model="apepaterno" wire-attribute="apepaterno" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Apellido Materno:" placeholder="Ingrese el Apellido Materno"
                                    wire-model="apematerno" wire-attribute="apematerno" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="clave:" placeholder="Ingrese la clave"
                                    wire-model="cve_empleado" wire-attribute="cve_empleado" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado-telefono label="Telefono:" placeholder="Ingrese telefono"
                                    wire-model="telefono" wire-attribute="telefono" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Correo Electrónico:" placeholder="Ingrese Correo Electronico"
                                    wire-model="correoElectronico" wire-attribute="correoElectronico" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Fecha Nacimiento:</label>
                                <input placeholder="Ingrese fecha de nacimiento" wire:model="fechaNacimiento" type="date" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-select-validado label="Sexo:" placeholder="Seleccione" wire-model="sexo" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </x-select-validado>
                            </div>
                            <div class="col-md-4 mb-3">
                                    <label for="">Fecha Ingreso:</label>
                                    <input placeholder="Ingrese fecha que ingresa" wire:model="fechaIngreso" type="date" class="form-control" />
                            </div>


                            <div class="col-md-4 mb-3">
                                <div class="">
                                    <label for="roles">Roles<label class="colorrojo">*</label></label>
                                    <select class="form-control" id="roles_user" name="roles_user[]" multiple required
                                        wire:model="roles_user">


                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-select-validado label="area:" placeholder="Seleccione" wire-model="area" required>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </x-select-validado>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="    ¿Revisor de area?:" wire-model="revisor"
                                    wire-attribute="revisor" type="checkbox" />

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
                                            <div class="col-md-3 mb-3">
                                                <x-input-validado label="Codigo Postal:"
                                                    placeholder="Ingrese codigo postal" wire-model="cp"
                                                    wire-attribute="cp" type="text" />
                                            </div>
                                            <div class="col-md-3 mb-3 mt-2">
                                                <div class="form-group ">
                                                    <label></label>
                                                    <button wire:click='validarCp'
                                                        class="btn btn-secondary btn-block ">Validar
                                                        cp</button>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <x-input-validado label="Estado:" :readonly="true"
                                                    placeholder="esperando..." wire-model="estados"
                                                    wire-attribute="estados" type="text" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <x-input-validado label="Alcaldia/Municipio:" :readonly="true"
                                                        placeholder="esperando..." wire-model="municipios"
                                                        wire-attribute="municipios" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-select-validado label="Colonia:" placeholder="Seleccione"
                                                    wire-model="ctg_cp_id" required>
                                                    @if (count($colonias))
                                                    @foreach ($colonias as $cp)
                                                    <option value="{{ $cp->id }}">{{ $cp->colonia }}
                                                    </option>
                                                    @endforeach
                                                    @else
                                                    <option value="">Esperando...</option>
                                                    @endif
                                                </x-select-validado>

                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-input-validado label="Calle y Número:"
                                                    placeholder="Ingrese la Calle y Número" wire-model="calleNumero"
                                                    wire-attribute="calleNumero" type="text" />
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
                                        <h3 class="text-center">Tallas de Ropa</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Talla de Camisa:"
                                                    placeholder="Ingrese talla de camisa" wire-model="tallaCamisa"
                                                    type="number" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Talla de Pantalón:"
                                                    placeholder="Ingrese talla de pantalón" wire-model="tallaPantalon"
                                                    type="number" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Talla de Zapatos:"
                                                    placeholder="Ingrese talla de zapatos" wire-model="tallaZapatos"
                                                    type="number" />
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
                                        <h3 class="text-center">Datos de contacto de emrgencia 1</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Nombre de Contacto de Emergencia:"
                                                    placeholder="Ingrese nombre" wire-model="nombreEmergencia1"
                                                    type="text" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado-telefono label="Teléfono de Emergencia:"
                                                    placeholder="Ingrese teléfono" wire-model="telefonoEmergencia1"
                                                    type="text" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Parentesco:" placeholder="Ingrese parentesco"
                                                    wire-model="parentescoEmergencia1" type="text" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <x-input-validado label="Dirección del Contacto de Emergencia:"
                                                    placeholder="Ingrese dirección" wire-model="direccionEmergencia1"
                                                    type="text" />
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
                                        <h3 class="text-center">Datos de contacto de emrgencia 2</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Nombre de Contacto de Emergencia 2:"
                                                    placeholder="Ingrese nombre" wire-model="nombreEmergencia2"
                                                    type="text" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado-telefono label="Teléfono de Emergencia 2:"
                                                    placeholder="Ingrese teléfono" wire-model="telefonoEmergencia2"
                                                    type="text" />
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-input-validado label="Parentesco 2:" placeholder="Ingrese parentesco"
                                                    wire-model="parentescoEmergencia2" type="text" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <x-input-validado label="Dirección del Contacto de Emergencia 2:"
                                                    placeholder="Ingrese dirección" wire-model="direccionEmergencia2"
                                                    type="text" />
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
                                    <h3 class="text-center">Información Medica</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-input-validado label="Alergias:" placeholder="Ingrese alergias"
                                                wire-model="alergias" type="text" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select-validado label="Tipo de Sangre:" placeholder="Seleccione"
                                                wire-model="tipoSangre">
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </x-select-validado>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input-validado label="UMF Correspondiente:" placeholder="Ingrese UMF"
                                                wire-model="umf" type="text" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input-validado label="Hospital General de Zona Correspondiente:"
                                                placeholder="Ingrese hospital" wire-model="hospital" type="text" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-info btn-block" wire:click="$dispatch('confirm')">Guardar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    document.addEventListener('livewire:initialized', () => {

                    @this.on('confirm', () => {

                        Swal.fire({
                            title: '¿Estas seguro?',
                            text: "El usuario se Creara.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, adelante!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                @this.dispatch('save-empleado');
                            }
                        })
                    })

                    Livewire.on('success', function([message]) {
                        console.log(message);

                        Swal.fire({
                            icon: 'success',
                            title: message[0],
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {

                                window.location.href = '/rh';

                            }
                        });
                    });


                    Livewire.on('error', function([message]) {
                        Swal.fire({
                            icon: 'error',
                            title: message[0],
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
                });
</script>
@endpush
</div>