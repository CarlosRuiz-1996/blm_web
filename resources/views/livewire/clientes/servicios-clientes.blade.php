<div>
    <div class="row">
        <div class="col-md-6">
            <h3 for="">Servicios</h3>
        </div>
        <div class="col-md-6">
            <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Agregar Servicios</button>
            </div>
        </div>

        {{-- tabla mostrada <div wire:init='loadProducts'> --}}
        <div class="col-md-12" wire:init='loadServicios'>
            <div class="table-responsive">
                @if (count($servicios_cliente))

                    <table class="table">
                        <thead class="table-primary">
                            <tr>
                                <th>Servicio</th>
                                <th>Monto del servicio</th>
                                <th>Sucursal</th>
                                <th>Estatus</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios_cliente as $servicio)
                                <tr>
                                    <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                    <td>${{ $servicio->ruta_servicio ? number_format($servicio->ruta_servicio->monto, 2, ',', '.') : 0 }}
                                    </td>
                                    <td>
                                        {{ $servicio->sucursal->sucursal->sucursal }}
                                    </td>
                                    <td>
                                        @if ($servicio->status_servicio != 0)
                                            <i class="fa fa-circle" style="color: green"></i>
                                        @else
                                            <i class="fa fa-circle" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($servicio->status_servicio != 0)
                                            <button class="btn btn-danger"
                                                wire:click='updateServicio({{ $servicio->id }},1)'>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-primary"
                                                wire:click='updateServicio({{ $servicio->id }},2)'>
                                                Reactivar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($servicios_cliente->hasPages())
                        <div class="px-6 py-3 text-gray-500">
                            {{ $servicios_cliente->links() }}
                        </div>
                    @endif
                @else
                    @if ($readyToLoad)
                        <div class="alert alert-secondary" role="alert">
                            No hay datos disponibles!
                        </div>
                    @else
                        <!-- Muestra un spinner mientras los datos se cargan -->
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>


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
                        <h5 class="modal-title" id="tema" {{ $foraneos ? 'hidden' : '' }}>Seleccione servicio</h5>
                        <form>
                            <div class="row">
                                <div class="col-md-3 mb-3" {{ $foraneos ? 'hidden' : '' }}>

                                    <x-select-validadolive label="Servicio:" placeholder="Seleccione"
                                        wire-model="servicioId" required>
                                        @foreach ($servicios as $servicio)
                                            <option value="{{ $servicio->id }}">{{ $servicio->folio }} /
                                                {{ $servicio->descripcion }}</option>
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
                                            @if (!$editarPreciohabilitado) readonly @endif />

                                    </div>
                                </div>
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>

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
                                <div class="col-md-4 mb-3" {{ $foraneos ? 'hidden' : '' }}>
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
                                                <label class="custom-control-label" for="checkforaneo">Activar
                                                    Servicios
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
                                                                <button
                                                                    wire:click="eliminarDeLista({{ $index }})"
                                                                    class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                <button
                                                                    wire:click="editarDeListaForaneaConcepto({{ $index }})"
                                                                    class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            @else
                                                                <button
                                                                    wire:click="guardarEdicion({{ $index }})"
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
                                    <x-input-validado label="Destino:" :readonly="false"
                                        placeholder="Ingrese Destino" wire-model="destinoruta"
                                        wire-attribute="destinoruta" type="text" />
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
                                        placeholder="Ingrese la cantidad de Iva" wire-model="iva"
                                        wire-attribute="iva" type="number" />
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
                    <button type="button" class="btn btn-primary" {{ $bloqser ? 'disabled' : '' }}
                        wire:click='llenartabla'>Siguiente</button>

                </div>
            </div>
        </div>
    </div>


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
                    <button class="btn btn-primary" data-target="#exampleModalToggle2"
                        data-toggle="modal" wire:click='cancelar()'>Cerrar</button>
                    <button class="btn btn-primary" wire:click="crearServicioctg">Crear Servicio</button>
                </div>
            </div>
        </div>
    </div>

    <livewire:clientes.modals.anexo-servicios :cliente="$form->cliente" />
    {{-- :client="$from->cliente" --}}

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('success-servicio', function([message]) {


                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000,
                        didClose: () => {
                            // Cerrar el modal después de que se muestra el mensaje de éxito
                            $('#exampleModalToggle2').modal('hide');
                        }
                    });
                });

                @this.on('sucursal-servico-clienteActivo', () => {
                    $('#exampleModal').modal('hide');
                    @this.dispatch('open-sucursal-servico-clienteActivo');

                })


                Livewire.on('success-terminado', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'El servicio se agrego con exito.',
                        timer: 4000,
                    });
                });

                Livewire.on('error-terminado', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error, intenta más tarde.',
                        showConfirmButton: true,
                        timer: 4000,
                    });
                });
            });
        </script>
    @endpush
</div>
