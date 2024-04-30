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
                                        data-target="#exampleModal" wire:click="$set('editar', 'true')">
                                        Agregar Servicios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive" {{ $foraneos ? 'hidden' : '' }}>
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
                                        <th>ACCIÓN</th>
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
                                        <td>
                                            <button wire:click="eliminarDeListaNormal({{ $item['id'] }})"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button wire:click="editarDeListaNormal({{ $item['id'] }})"
                                                class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#exampleModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" {{ $foraneos ? '' : 'hidden' }}>
                            <table class="table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>PDA</th>
                                        <th>NOMBRE DEL SERVICIO</th>
                                        <th>INICIO</th>
                                        <th>DESTINO</th>
                                        <th>COSTOTAL/KM</th>
                                        <th>COSTOTAL/MILES</th>
                                        <th>G/OPERACIÓN</th>
                                        <th>IVA</th>
                                        <th>TOTAL</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataforaneo as $item2)
                                    <tr>
                                        <td>{{ $item2['id'] }}</td>
                                        <td>Servicio Foraneo</td>
                                        <td>{{ $item2['inicioruta'] }}</td>
                                        <td>{{ $item2['destinoruta'] }}</td>
                                        <td>{{ $item2['totalkmprecio'] }}</td>
                                        <td>{{ $item2['costomiles'] }}</td>
                                        <td>{{ $item2['goperacion'] }}</td>
                                        <td>{{ $item2['totaliva'] }}</td>
                                        <td>{{ $item2['sumatotal'] }}</td>
                                        <td>
                                            <button wire:click="eliminarDeListaForanea({{ $item2['id'] }})"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button wire:click="editarDeListaForanea({{ $item2['id'] }})"
                                                class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#exampleModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
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
                                <x-input-validado label="Vigencia:" placeholder="Ingrese Vigencia" wire-model="vigencia"
                                    wire-attribute="vigencia" type="number" />
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
                    <h5 class="modal-title" id="exampleModalLabel">{{$editar ? 'AGREGAR':'EDITAR'}} SERVICIO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <h5 class="modal-title" id="tema" {{ $foraneos ? 'hidden' : '' }}>Seleccione servicio</h5>
                        <form>
                            <div class="row">
                                <div class="col-md-3 mb-3" {{ $foraneos ? 'hidden' : '' }}>

                                    <x-select-validadolive label="Servicio:" placeholder="Seleccione"
                                        wire-model="servicioId" required>
                                        @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->id }}">{{ $servicio->folio }} / {{
                                            $servicio->descripcion }}</option>
                                        @endforeach
                                        </x-select-validado>
                                </div>
                                <div class="col-md-1 mb-3 mt-2" {{ $foraneos ? 'hidden' : '' }}>
                                    <button type="button" data-target="#exampleModalToggle2" data-toggle="modal"
                                        class="btn btn-primary btn-block mt-4"><i class="fas fa-plus"></i></button>
                                </div>

                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <x-input-validado label="Nombre de servicio:" :readonly="true"
                                        placeholder="Ingrese el nombre del servicio" wire-model="nombreServicio"
                                        wire-attribute="nombreServicio" type="text" />
                                </div>

                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <x-input-validado label="Tipo de servicio:" :readonly="true"
                                        placeholder="Ingrese el nombre del servicio" wire-model="tipoServicio"
                                        wire-attribute="tipoServicio" type="text" />
                                </div>

                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <x-input-validado label="Unidad medida:" :readonly="true"
                                        placeholder="Ingrese la unidad de medida" wire-model="unidadMedida"
                                        wire-attribute="unidadMedida" type="text" />
                                </div>

                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <x-select-validadolive label="Precio unitario:" placeholder="Seleccione"
                                        wire-model="precioUnitario" required>
                                        @foreach ($precio_servicio as $precio)
                                        <option value="{{ $precio->precio }}">{{ $precio->precio }}</option>
                                        @endforeach
                                        </x-select-validado>
                                </div>
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl mt-2">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model.live='editarPreciocheck' id="editarPreciocheck"
                                                name="editarPreciocheck">
                                            <label class="custom-control-label" for="editarPreciocheck">Editar
                                                Precio:</label>
                                        </div>
                                        <input type="number" class="form-control" wire:model.live="editarPrecio" 
                                        @if(!$editarPreciohabilitado) readonly @endif />

                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl mt-2">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model.live='cantidadcheck' id="cantidadcheck" name="cantidadcheck">
                                            <label class="custom-control-label" for="cantidadcheck">cantidad:</label>
                                        </div>
                                        <input type="number" class="form-control" wire:model.live='cantidad'
                                         @if (!$cantidadhabilitado) readonly @endif />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <div class="form-group mt-5">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-switch-xl">
                                                <input type="checkbox" class="custom-control-input" wire:model='isAdmin'
                                                    id="isAdminSwitch" name="isAdmin">
                                                <label class="custom-control-label" for="isAdminSwitch">Especial</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
                                    <x-input-validado label="Total:" :readonly="true" placeholder="Total"
                                        wire-model="total" wire-attribute="total" type="number" />
                                </div>
                                <!--servicios foraneos-->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-switch-xl">
                                                <input type="checkbox" class="custom-control-input"
                                                    wire:model.live='checkforaneo' id="checkforaneo"
                                                    name="checkforaneo">
                                                <label class="custom-control-label" for="checkforaneo">Activar Servicios
                                                    Foraneos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-12 mb-3">
                                    <hr>
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-12 mb-3">
                                    <h3 class="text-center">Servicios Foraneos</h3>
                                </div>

                                <!--conceptos foraneos-->
                                <div class="col-md-5 mb-3" {{ $foraneos ? '' : 'hidden' }}>
                                    <x-input-validado label="Servicios:" :readonly="false"
                                        placeholder="Ingrese el servicio" wire-model="consepforaneo"
                                        wire-attribute="consepforaneo" type="text" />
                                </div>
                                <div class="col-md-3 mb-3" {{ $foraneos ? '' : 'hidden' }}>
                                    <x-input-validado label="Cantidad:" :readonly="false"
                                        placeholder="Ingrese la cantidad" wire-model="cantidadfora"
                                        wire-attribute="cantidadfora" type="number" step="any" />
                                </div>
                                <div class="col-md-2 mb-3" {{ $foraneos ? '' : 'hidden' }}>
                                    <x-input-validado label="Costo:" :readonly="false" placeholder="Ingrese el costo"
                                        wire-model="precioconsepforaneo" wire-attribute="precioconsepforaneo"
                                        type="number" step="any" />
                                </div>
                                <div class="col-md-2 mb-3 mt-2" {{ $foraneos ? '' : 'hidden' }}>
                                    <button type="button" wire:click="agregarALista"
                                        class="btn btn-primary btn-block mt-4"><i class="fas fa-plus"></i></button>
                                </div>
                                <!-- Lista de elementos en formato de tabla -->
                                @if (count($listaForaneos) > 0)
                                <div class="col-md-12 mb-3 mt-2" {{ $foraneos ? '' : 'hidden' }}>
                                    <table class="table">
                                        <thead class="table-info">
                                            <tr>
                                                <th class="col-md-8">Conceptos</th>
                                                <th class="col-md-2">Cantidad</th>
                                                <th class="col-md-2">Costo</th>
                                                <th class="col-md-2">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listaForaneos as $index => $item)
                                            <tr>
                                                <td>
                                                    @if ($editIndex === $index)
                                                    <input type="text"
                                                        wire:model="listaForaneos.{{ $index }}.consepforaneo"
                                                        class="form-control">
                                                    @else
                                                    {{ $item['consepforaneo'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($editIndex === $index)
                                                    <input type="number"
                                                        wire:model="listaForaneos.{{ $index }}.cantidadfora"
                                                        class="form-control">
                                                    @else
                                                    {{ $item['cantidadfora'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($editIndex === $index)
                                                    <input type="text"
                                                        wire:model="listaForaneos.{{ $index }}.precioconsepforaneo"
                                                        class="form-control">
                                                    @else
                                                    {{ $item['precioconsepforaneo'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($editIndex !== $index)
                                                    <button wire:click="eliminarDeLista({{ $index }})"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <button wire:click="editarDeListaForaneaConcepto({{ $index }})"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @else
                                                    <button wire:click="guardarEdicion({{ $index }})"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-12 mb-3">
                                    <x-input-validado label="Costo total servicios:" :readonly="true"
                                        placeholder="Calculando costo total de servicios"
                                        wire-model="costototalservicios" wire-attribute="costototalservicios"
                                        type="number" step="any" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-12 mb-3">
                                    <x-input-validado label="Cantidad Servicio:" :readonly="false"
                                        placeholder="Ingrese cantidad que llevara el servicio"
                                        wire-model="cantidadlleva" wire-attribute="cantidadlleva" type="number"
                                        step="any" />
                                </div>

                                <!--fin concetos foraneos-->
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-6 mb-3">
                                    <x-input-validado label="Inicio:" :readonly="false" placeholder="Ingrese Inicio"
                                        wire-model="inicioruta" wire-attribute="inicioruta" type="text" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-6 mb-3">
                                    <x-input-validado label="Destino:" :readonly="false" placeholder="Ingrese Destino"
                                        wire-model="destinoruta" wire-attribute="destinoruta" type="text" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validadolive label="Kilometros:" :readonly="false"
                                        placeholder="Ingrese la cantidad de Kilometros" wire-model="km"
                                        wire-attribute="km" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validadolive label="Costo por Kilometro:" :readonly="false"
                                        placeholder="Ingrese costo por Kilometro" wire-model="costokm"
                                        wire-attribute="costokm" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validado label="Costo total Kilometros:" :readonly="true"
                                        placeholder="Ingrese costo por Kilometro" wire-model="totalkmprecio"
                                        wire-attribute="totalkmprecio" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validadolive label="Miles:" :readonly="false"
                                        placeholder="Ingrese la cantidad de Miles" wire-model="miles"
                                        wire-attribute="miles" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validadolive label="Costo por Miles:" :readonly="false"
                                        placeholder="Ingrese costo por Miles" wire-model="milesprecio"
                                        wire-attribute="milesprecio" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validado label="Costo total Miles:" :readonly="true"
                                        placeholder="Ingrese costo por Miles" wire-model="costomiles"
                                        wire-attribute="costomiles" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validadolive label="G/Operacion:" :readonly="false"
                                        placeholder="Ingrese la cantidad de G/Operacion" wire-model="goperacion"
                                        wire-attribute="goperacion" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-2 mb-3">
                                    <x-input-validadolive label="IVA:" :readonly="false"
                                        placeholder="Ingrese la cantidad de Iva" wire-model="iva" wire-attribute="iva"
                                        type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-2 mb-3">
                                    <x-input-validado label="Total IVA:" :readonly="true"
                                        placeholder="Ingrese la cantidad de Iva" wire-model="totaliva"
                                        wire-attribute="totaliva" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-4 mb-3">
                                    <x-input-validado label="Subtotal:" :readonly="true"
                                        placeholder="calculando subtotal" wire-model="subtotalforaneo"
                                        wire-attribute="subtotalforaneo" type="number" />
                                </div>
                                <div {{ $foraneos ? '' : 'hidden' }} class="col-md-12 mb-3">
                                    <x-input-validado label="Total:" :readonly="true" placeholder="Calculando total"
                                        wire-model="sumatotal" wire-attribute="sumatotal" type="number" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @if($editar)
                    <button type="button" class="btn btn-primary" {{ $bloqser ? 'disabled' : '' }}
                        wire:click='llenartabla'>Agregar</button>
                    @else
                    <button class="btn btn-primary"
                        wire:click="editarservicioNormal('{{ $valoreditar }}')">Editar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--fin modal 1-->
    <!--modal 2-->
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">CREAR NUEVO SERVICIO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Folio:" :readonly="false"
                                placeholder="Ingrese el folio ejemplo blm-005" wire-model="folioctg"
                                wire-attribute="folioctg" type="text" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Tipo:" :readonly="false" placeholder="Ingrese el tipo"
                                wire-model="tipoctg" wire-attribute="tipoctg" type="text" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Descripción:" :readonly="false"
                                placeholder="Ingrese la descripción" wire-model="descripcionctg"
                                wire-attribute="descripcionctg" type="text" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Unidad:" :readonly="false" placeholder="Ingrese la unidad"
                                wire-model="unidadctg" wire-attribute="unidadctg" type="text" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-target="#exampleModal" data-toggle="modal">Cerrar</button>
                    <button class="btn btn-primary" wire:click="crearServicioctg">Crear Servicio</button>
                </div>
            </div>
        </div>
    </div>
    <!--fin modal 2-->

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
                Livewire.on('successservicio', function(message) {
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        // Cerrar el modal después de que se muestra el mensaje de éxito
                        $('#exampleModalToggle2').modal('hide');
                    });
                });
                

            });

              // Ocultar el primer modal cuando se muestra el segundo modal
                $('#exampleModalToggle2').on('show.bs.modal', function () {
                    $('#exampleModal').modal('hide');
                });

                // Mostrar el primer modal cuando se cierra el segundo modal
                $('#exampleModalToggle2').on('hidden.bs.modal', function () {
                    $('#exampleModal').modal('show');
                });

                // Ocultar el segundo modal cuando se muestra el primer modal
                $('#exampleModal').on('show.bs.modal', function () {
                    $('#exampleModalToggle2').modal('hide');
                });

        
            
    </script>


    @endpush
</div>