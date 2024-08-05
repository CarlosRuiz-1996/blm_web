<div wire:init='loadServicios'>


    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Servicios</h1>
        @if ($this->form->ruta->ctg_rutas_estado_id == 1)
            <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
                data-target="#servicios" wire:click='getServicios()'>
                Agregar Servicio
                <i class="fa fa-plus" aria-hidden="true"></i>

            </button>
        @endif
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex col-md-12 mb-3">



                            <input type="text" class="form-control w-full" placeholder="Buscar servicio"
                                wire:model.live='form.searchServicio'>

                        </div>

                        <div class="col-md-12">

                            @if ($ruta_servicios && count($ruta_servicios))
                                <table class="table table-hover table-striped">
                                    <thead class="table-info">
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Monto</th>
                                        <th>Folio</th>
                                        <th>Envases</th>
                                        <th>Tipo servicio</th>
                                        @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                                            <th>Acciones</th>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach ($ruta_servicios as $servicio)
                                            <tr>

                                                <td>{{ $servicio->id }}</td>
                                                <td>{{ $servicio->servicio->cliente->razon_social }}</td>
                                                <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>

                                                <td>{{ $servicio->monto }}</td>
                                                <td>{{ $servicio->folio }}</td>
                                                <td>{{ $servicio->envases }}</td>
                                                <td>{{ $servicio->tipo_servicio == 1 ? 'ENTREGA' : 'RECOLECCIÓN' }}</td>
                                                @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                                                    <td>
                                                        <button class="btn text-danger" title="Eliminar"
                                                            wire:click="$dispatch('confirm-delete-servicio',{{ $servicio }})">
                                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                                        </button>


                                                        <button class="btn text-success" title="Editar"
                                                            wire:click="servicioEdit({{ $servicio }})"
                                                            data-toggle="modal" data-target="#servicios_edit">
                                                            <i class="fa fa-lg fa-fw fa-pen" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if ($readyToLoad)
                                    <div class="alert alert-secondary" role="alert">
                                        No hay datos disponibles </div>
                                @else
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            {{-- <span class="visually-hidden">Loading...</span> --}}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    {{-- Modal servicios --}}
    <x-adminlte-modal wire:ignore.self id="servicios" title="Agregar servicios a la ruta" theme="info"
        icon="fas fa-car" size='xl' disable-animations>


        <div class="d-flex mb-3">



            <input type="text" class="form-control" placeholder="Buscar por RFC o Razon social"
                wire:model.live='form.searchClienteModal'>


            <select class="form-control ml-2" wire:model.live='form.searchClienteSelect'>
                <option value="">Selecciona un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->razon_social . '-' . $cliente->rfc_cliente }}
                    </option>
                @endforeach
            </select>
            <button title="Limpiar Filtros" wire:click='limpiarFiltro' class="btn btn-sm btn-primary ml-2"><i
                    class="fa fa-eraser" aria-hidden="true"></i></button>
        </div>

        <div>
            @if ($servicios && count($servicios))
                <div class="table-responsive">
                    {{-- <table id="dataTable" class="table table-hover table-striped">
                        <thead class="table-info">
                            <tr>
                                <th></th>
                                <th>Servicio</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Tipo Servicio</th>
                                <th>Monto</th>
                                <th>Papeleta</th>
                                <th>Contenedor</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($servicios as $servicio)
                                <tr x-data="{
                                    checkServicio: false,
                                    checkbox1: false,
                                    checkbox2: false,
                                    monto: '',
                                    folio: '',
                                    contenedor: '',
                                    monto2: '',
                                    folio2: '',
                                    contenedor2: '',
                                    tipo: '0',
                                    updateCheckboxes() {
                                        if (!this.checkServicio) {
                                            this.checkbox1 = false;
                                            this.checkbox2 = false;
                                            this.monto = '';
                                            this.folio = '';
                                            this.contenedor = '';
                                            this.monto2 = '';
                                            this.folio2 = '';
                                            this.contenedor2 = '';
                                        }
                                    },
                                    disableFields() {
                                        return !this.checkServicio;
                                    }
                                }">
                                    <td>
                                        <input type="checkbox" wire:model='selectServicios.{{ $servicio->id }}'
                                            x-model="checkServicio" wire:click="resetError('{{ $servicio->id }}')"
                                            @change="updateCheckboxes" />




                                    </td>
                                    <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                    <td>{{ $servicio->cliente->razon_social }}</td>
                                    <td>{{ $servicio->sucursal->sucursal->direccion .
                                        ' ' .
                                        $servicio->sucursal->sucursal->cp->cp .
                                        '' .
                                        $servicio->sucursal->sucursal->cp->estado->name }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="form-check mt-2">

                                                <input class="form-check-input" type="checkbox"
                                                    x-bind:value="checkServicio ? tipo : '1'"
                                                    x-bind:disabled="!checkServicio" x-model="checkbox1"
                                                    wire:model='selectServiciosRecolecta.{{ $servicio->id }}'
                                                    @change="if (!checkbox1) {  monto = ''; folio = ''; contenedor='' ;  }"
                                                    wire:click="resetError('{{ $servicio->id }}')" />
                                                <label class="form-check-label"
                                                    for="selectServiciosRecolecta.{{ $servicio->id }}">Recolección</label>
                                            </div>
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                    x-bind:value="checkServicio ? tipo : '2'"
                                                    x-bind:disabled="!checkServicio" x-model="checkbox2"
                                                    @change="if (!checkbox2) { monto2 = ''; folio2 = ''; contenedor2='' ; }"
                                                    wire:model='selectServiciosEntrega.{{ $servicio->id }}'
                                                    wire:click="resetError('{{ $servicio->id }}')" />
                                                <label class="form-check-label"
                                                    for="selectServiciosEntrega.{{ $servicio->id }}">Entrega</label>
                                            </div>
                                        </div>
                                        @error('selectValidacion.' . $servicio->id)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado
                                                x-bind:value="checkServicio ? monto : '' || checkbox1 ? monto : ''"
                                                style="margin-top: -20%" x-bind:disabled="!checkServicio || !checkbox1"
                                                x-model="monto" placeholder="Monto"
                                                wire-model='montoArrayRecolecta.{{ $servicio->id }}' type="number" />
                                            <x-input-validado
                                                x-bind:value="checkServicio ? monto2 : '' || checkbox2 ? monto2 : ''"
                                                style="margin-top: -25%" x-bind:disabled="!checkServicio || !checkbox2"
                                                x-model="monto2" placeholder="Monto"
                                                wire-model='montoArray.{{ $servicio->id }}' type="number" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado
                                                x-bind:value="checkServicio ? folio : '' || checkbox1 ? folio : ''"
                                                style="margin-top: -19%" x-bind:disabled="!checkServicio || !checkbox1"
                                                x-model="folio" placeholder="Papeleta"
                                                wire-model='folioArrayRecolecta.{{ $servicio->id }}'
                                                type="text" />
                                            <x-input-validado
                                                x-bind:value="checkServicio ? folio2 : '' || checkbox2 ? folio2 : ''"
                                                style="margin-top: -22%"
                                                x-bind:disabled="!checkServicio || !checkbox2" x-model="folio2"
                                                placeholder="Papeleta" wire-model='folioArray.{{ $servicio->id }}'
                                                type="text" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado
                                                x-bind:value="checkServicio ? contenedor : '' || checkbox1 ? contenedor : ''"
                                                style="margin-top: -17%"
                                                x-bind:disabled="!checkServicio || !checkbox1" x-model="contenedor"
                                                placeholder="Envases"
                                                wire-model='envaseArrayRecolecta.{{ $servicio->id }}'
                                                type="number" />
                                            <x-input-validado
                                                x-bind:value="checkServicio ? contenedor2 : '' || checkbox2 ? contenedor2 : ''"
                                                style="margin-top: -21%"
                                                x-bind:disabled="!checkServicio || !checkbox2" x-model="contenedor2"
                                                placeholder="Envases" wire-model='envaseArray.{{ $servicio->id }}'
                                                type="number" />
                                        </div>
                                    </td>


                                </tr>
                            @endforeach

                        </tbody>
                    </table> --}}
                    <table id="dataTable" class="table table-hover table-striped">
                        <thead class="table-info">
                            <tr>
                                <th></th>
                                <th>Servicio</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Tipo Servicio</th>
                                <th>Monto</th>
                                <th>Papeleta</th>
                                <th>Contenedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios as $servicio)
                                <tr x-data="{
                                    checkServicio: @entangle('selectServicios.{{ $servicio->id }}'),
                                    checkbox1: @entangle('selectServiciosRecolecta.{{ $servicio->id }}'),
                                    checkbox2: @entangle('selectServiciosEntrega.{{ $servicio->id }}'),
                                    monto: @entangle('montoArrayRecolecta.{{ $servicio->id }}'),
                                    monto2: @entangle('montoArray.{{ $servicio->id }}'),
                                    folio: @entangle('folioArrayRecolecta.{{ $servicio->id }}'),
                                    folio2: @entangle('folioArray.{{ $servicio->id }}'),
                                    contenedor: @entangle('envaseArrayRecolecta.{{ $servicio->id }}'),
                                    contenedor2: @entangle('envaseArray.{{ $servicio->id }}'),
                                    updateCheckboxes() {
                                        if (!this.checkServicio) {
                                            this.checkbox1 = false;
                                            this.checkbox2 = false;
                                            this.monto = '';
                                            this.monto2 = '';
                                            this.folio = '';
                                            this.folio2 = '';
                                            this.contenedor = '';
                                            this.contenedor2 = '';
                                        }
                                    }
                                }" @change="updateCheckboxes()">
                                    <td>
                                        <input type="checkbox" x-model="checkServicio" />
                                    </td>
                                    <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                    <td>{{ $servicio->cliente->razon_social }}</td>
                                    <td>{{ $servicio->sucursal->sucursal->direccion . ' ' . $servicio->sucursal->sucursal->cp->cp . '' . $servicio->sucursal->sucursal->cp->estado->name }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" x-model="checkbox1" />
                                                <label class="form-check-label">Recolección</label>
                                            </div>
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" x-model="checkbox2" />
                                                <label class="form-check-label">Entrega</label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado x-model="monto" placeholder="Monto" :disabled="!checkServicio || !checkbox1" type="number" />
                                            <x-input-validado x-model="monto2" placeholder="Monto" :disabled="!checkServicio || !checkbox2" type="number" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado x-model="folio" placeholder="Papeleta" :disabled="!checkServicio || !checkbox1" type="text" />
                                            <x-input-validado x-model="folio2" placeholder="Papeleta" :disabled="!checkServicio || !checkbox2" type="text" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <x-input-validado x-model="contenedor" placeholder="Envases" :disabled="!checkServicio || !checkbox1" type="number" />
                                            <x-input-validado x-model="contenedor2" placeholder="Envases" :disabled="!checkServicio || !checkbox2" type="number" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($servicios->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $servicios->links() }}
                    </div>
                @endif
                <div class="text-center col-md-12 mb-3">
                    <button class="btn btn-info btn-xl " wire:click='$dispatch("confirm-servicio")'>Guardar</button>
                </div>
            @else
                @if ($readyToLoad)
                    <div class="alert alert-secondary" role="alert">
                        No hay datos disponibles </div>
                @else
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            {{-- <span class="visually-hidden">Loading...</span> --}}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </x-adminlte-modal>
    <x-adminlte-modal wire:ignore.self id="servicios_edit" title="Editar datos del servicio" theme="info"
        icon="fas fa-car" size='xl' disable-animations>



        <div>
            <x-input-validado label="Descripción:" :readonly="true" placeholder="Descripción"
                wire-model='form.servicio_desc' type="text" />

            <x-input-validado label="Monto:" placeholder="Monto" wire-model='form.monto' type="number" />
            <x-input-validado label="Papeleta:" placeholder="Papeleta" wire-model='form.folio' type="text" />
            <x-input-validado label="Envases:" placeholder="Envases" wire-model='form.envases' type="number" />

            <div class="text-center col-md-12 mb-3">
                <button class="btn btn-info btn-xl " wire:click='$dispatch("confirm-edit-servicio")'>Guardar</button>
            </div>

        </div>
    </x-adminlte-modal>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm-servicio', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Los servicios se agregaran a la ruta",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('add-servicio-ruta');
                        }
                    })
                })
                @this.on('confirm-delete-servicio', (servicio) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Se borrara de la base de datos, esto puede traer problemas con el sistema",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('delete-servicio', {
                                servicio: servicio
                            });
                        }
                    })
                })
                @this.on('confirm-edit-servicio', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "Los cambios echos se apliacaran al servicio seleccionado",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('update-servicio');
                        }
                    })
                })
                Livewire.on('success-servicio', function(message) {
                    $('#servicios').modal('hide');
                    $('#servicios_edit').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    });
                    location.reload();

                });


                Livewire.on('error-servicio', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                    // .then(() => {
                    //     location.reload();
                    // });
                });
            });

            // detecto cuando cierra modal y limpio array
            $(document).ready(function() {
                $('#servicios').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
                $('#servicios_edit').on('hidden.bs.modal', function(event) {
                    @this.dispatch('clean-servicios');
                });
            });
        </script>
    @endpush

</div>
