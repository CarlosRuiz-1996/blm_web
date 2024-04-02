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
                                <x-select-validado label="Tipo de cliente:" placeholder="Seleccione"
                                    wire-model="ctg_tipo_cliente_id" required>
                                    @foreach ($tipoClientelist as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado-telefono label="Telefono:" placeholder="Ingrese telefono"
                                    wire-model="telefono" wire-attribute="telefono" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Correo Electrónico:" placeholder="Ingrese Correo Electronico"
                                    wire-model="correoElectronico" wire-attribute="correoElectronico" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4 class="text-center">Datos del contacto</h4>
                                <hr>
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Nombre del contacto:"
                                    placeholder="Ingrese el Nombre del Contacto" wire-model="nombreContacto"
                                    wire-attribute="nombreContacto" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Apellido Paterno:" placeholder="Ingrese el Apellido paterno"
                                    wire-model="apepaterno" wire-attribute="apepaterno" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Apellido Materno:" placeholder="Ingrese el Apellido Materno"
                                    wire-model="apematerno" wire-attribute="apematerno" type="text" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Puesto:" placeholder="Ingrese el Puesto" wire-model="puesto"
                                    wire-attribute="puesto" type="text" />
                            </div>
                            <!-- Información de contacto -->

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
                            <div class="col-md-3 mb-3">
                                <x-input-validado label="Codigo Postal:" placeholder="Ingrese codigo postal"
                                    wire-model="cp" wire-attribute="cp" type="text" />
                            </div>
                            <div class="col-md-3 mb-3 mt-2">
                                <div class="form-group ">
                                    <label></label>
                                    <button wire:click='validarCp' class="btn btn-secondary btn-block ">Validar
                                        cp</button>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-input-validado label="Estado:" :readonly="true" placeholder="esperando..."
                                    wire-model="estados" wire-attribute="estados" type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <x-input-validado label="Alcaldia/Municipio:" :readonly="true"
                                        placeholder="esperando..." wire-model="municipios" wire-attribute="municipios"
                                        type="text" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select-validado label="Colonia:" placeholder="Seleccione" wire-model="ctg_cp_id"
                                    required>
                                    @if (count($colonias))
                                        @foreach ($colonias as $cp)
                                            <option value="{{ $cp->id }}">{{ $cp->colonia }}</option>
                                        @endforeach
                                    @else
                                        <option value="">Esperando...</option>
                                    @endif
                                </x-select-validado>

                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Calle y Número:" placeholder="Ingrese la Calle y Número"
                                    wire-model="calleNumero" wire-attribute="calleNumero" type="text" />
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
                        <div class="row">
                            <div class="col-md-6">
                                <h3 for="">Servicios</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">
                                        Agregar Servicios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>PDA</th>
                                        <th>NOMBRE DEL SERVICIO</th>
                                        <th>UNIDAD MEDIDA</th>
                                        <th>CANTIDAD</th>
                                        <th>PRECIO UNITARIO</th>
                                        <th>TIPO</th>
                                        <th>SUBTOTAL</th>
                                        <th>ELIMINAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td>{{ $item['nombreservicio'] }}</td>
                                            <td>{{ $item['unidadmedida'] }}</td>
                                            <td>{{ $item['cantidad'] }}</td>
                                            <td>{{ $item['preciounitario'] }}</td>
                                            <td>{{ $item['isAdmin'] }}</td>
                                            <td>{{ $item['total'] }}</td>
                                            <td>{{ $item['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex">
                            <div class="ml-auto h3">
                                Total: {{ $totalreal }}
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
                        <h3 class="text-center">Condiciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-validado label="Vigencia:" placeholder="Ingrese Vigencia"
                                    wire-model="vigencia" wire-attribute="vigencia" type="number" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-select-validado label="Condiciones de pago:" placeholder="Seleccione"
                                    wire-model="condicionpago" required>
                                    @foreach ($condicionpagolist as $condicionpag)
                                        <option value="{{ $condicionpag->id }}">{{ $condicionpag->nombre }}</option>
                                    @endforeach
                                </x-select-validado>
                            </div>
                            <div class="col-md-12">
                                <button wire:click="$dispatch('confirm')" class="btn btn-secondary btn-block ">Guardar
                                    Cotización</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">AGREGAR SERVICIO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <h5 class="modal-title" id="tema">Seleccione servicio</h5>
                        <form>
                            <div class="row">
                                <div class="col-md-4 mb-3">

                                    <x-select-validadolive label="Servicio:" placeholder="Seleccione"
                                        wire-model="servicioId" required>
                                        @foreach ($servicios as $servicio)
                                            <option value="{{ $servicio->id }}">{{ $servicio->folio }}  /  {{ $servicio->descripcion }}</option>
                                        @endforeach
                                        </x-select-validado>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Nombre de servicio:" :readonly="true"
                                        placeholder="Ingrese el nombre del servicio" wire-model="nombreServicio"
                                        wire-attribute="nombreServicio" type="text" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Tipo de servicio:" :readonly="true"
                                        placeholder="Ingrese el nombre del servicio" wire-model="tipoServicio"
                                        wire-attribute="tipoServicio" type="text" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Unidad medida:" :readonly="true"
                                        placeholder="Ingrese la unidad de medida" wire-model="unidadMedida"
                                        wire-attribute="unidadMedida" type="text" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-select-validadolive label="Precio unitario:" placeholder="Seleccione"
                                        wire-model="precioUnitario" required>
                                        @foreach ($precio_servicio as $precio)
                                            <option value="{{ $precio->precio }}">{{ $precio->precio }}</option>
                                        @endforeach
                                        </x-select-validado>
                                </div>
                                <div class="col-md-4 mb-3">

                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl mt-2">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model.live='editarPreciocheck' id="editarPreciocheck"
                                                name="editarPreciocheck">
                                            <label class="custom-control-label" for="editarPreciocheck">Editar
                                                Precio:</label>
                                        </div>
                                        <input type="number" class="form-control" wire:model.live="editarPrecio"
                                            @if (!$editarPreciohabilitado) readonly @endif />

                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">

                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl mt-2">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model.live='cantidadcheck' id="cantidadcheck"
                                                name="cantidadcheck">
                                            <label class="custom-control-label" for="cantidadcheck">cantidad:</label>
                                        </div>
                                        <input type="number" class="form-control" wire:model.live='cantidad'
                                            @if (!$cantidadhabilitado) readonly @endif />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group mt-5">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-switch-xl">
                                                <input type="checkbox" class="custom-control-input"
                                                    wire:model='isAdmin' id="isAdminSwitch" name="isAdmin">
                                                <label class="custom-control-label"
                                                    for="isAdminSwitch">Especial</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-input-validado label="Total:" :readonly="true" placeholder="Total"
                                        wire-model="total" wire-attribute="total" type="number" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click='llenartabla'>Agregar</button>
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
                        text: "La cotizacion se generara y comenzara el proceso de contratación.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('save-cotizacion');
                        }
                    })
                })

                Livewire.on('success-cotizacion', function([message]) {
                    console.log(message);

                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            window.location.href = '/ventas/detalle-cotizacion/' + message[1];

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

                Livewire.on('errorTablaDatos', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
                Livewire.on('errorTabla', function([message]) {
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
