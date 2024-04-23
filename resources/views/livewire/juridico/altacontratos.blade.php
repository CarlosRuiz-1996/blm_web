<div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Generar Contratos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">                        
                           
                            <div class="col-md-10 mb-3">
                                <x-input-validado :readonly="false" label="Cliente:" placeholder="Ingrese el cliente a buscar"
                                    wire-model="clientenombre" wire-attribute="clientenombre" type="text" />
                            </div>
                            <div class="col-md-2 mb-3 mt-3">
                                <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal" wire:click='BuscarCliente' data-target="#exampleModal" >Buscar</button>
                            </div>
                               
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Cliente</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-select-validado label="Tipo de cliente:" placeholder="Seleccione"
                                    wire-model="ctg_tipo_cliente_id" required>
                                    <!--@foreach ($tipoClientelist as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach-->
                                </x-select-validado>
                            </div>

                            <div class="col-md-4 mb-3" hidden>
                                <x-input-validado :readonly="true" label="Razón Social:" placeholder="Ingrese la Razón Social"
                                    wire-model="id" wire-attribute="id" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="Razón Social:" placeholder="Ingrese la Razón Social"
                                    wire-model="razonSocial" wire-attribute="razonSocial" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="true" label="RFC" placeholder="Ingrese el rfc" wire-model="rfc"
                                    wire-attribute="rfc" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                    <x-input-validado :readonly="true" label="Tipo Cliente" placeholder="Ingrese el tipo de cliente" wire-model="tipocliente"
                                        wire-attribute="tipocliente" type="text" />
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
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Datos Fiscales</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Apoderado/a:" placeholder="Ingrese Apoderado/a"
                                    wire-model="apoderado" wire-attribute="apoderado" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Escritura:" placeholder="Ingrese Escritura"
                                    wire-model="escritura" wire-attribute="escritura" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Licenciado:" placeholder="Ingrese Licenciado"
                                    wire-model="licenciado" wire-attribute="licenciado" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Folio Mercantil:" placeholder="Ingrese Folio Mercantil"
                                    wire-model="foliomercantil" wire-attribute="foliomercantil" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Fecha de Registro:" placeholder="Ingrese fecha de Registro"
                                    wire-model="fecharegistro" wire-attribute="fecharegistro" type="date" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Lugar de registro:" placeholder="Ingrese Lugar de registro"
                                    wire-model="lugarregistro" wire-attribute="lugarregistro" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Poder notarial</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-8 mb-3">
                                <x-input-validado :readonly="false" label="Notario:" placeholder="Ingrese Notario"
                                    wire-model="notario" wire-attribute="notario" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group mt-5">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-xl">
                                            <input type="checkbox" class="custom-control-input"
                                                wire:model='datosextra' id="datosextraSwitch" name="datosextra">
                                            <label class="custom-control-label"
                                                for="datosextraSwitch">Datos extras</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="No.Licenciado de la notaria:" placeholder="Ingrese No.Licenciado de la notaria"
                                    wire-model="numnotario" wire-attribute="numnotario" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Fecha de poder notarial:" placeholder="Ingrese Fecha de poder notarial"
                                    wire-model="fechapodernotarial" wire-attribute="fechapodernotarial" type="date" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado :readonly="false" label="Ciudad de la notaria:" placeholder="Ingrese Ciudad de la notaria"
                                    wire-model="ciudadnotaria" wire-attribute="ciudadnotaria" type="text" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <h3 class="text-center">Documentos</h3>
                            </div>
                            <div class="col-md-12 mb-3">
                                <hr>
                            </div>
                            <div class="col-md-12 mb-3">
                               <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre Contrato</th>
                                            <th>Documento</th>
                                            <th>Eliminar</th>
                                            <!-- Agrega más <th> según sea necesario para tus columnas -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PODER NOTARIAL</td>
                                            <td><i class="fas fa-file-word" style="color: #2B579A;"></td>
                                            <td></td>
                                            <!-- Agrega más <td> según sea necesario para tus filas de datos -->
                                        </tr>
                                        <!-- Agrega más <tr> y <td> según sea necesario para más filas y datos -->
                                    </tbody>
                                </table>
                               </div>
                            </div>
                            <div class="col-md-12 mb-3">
                               <button class="btn btn-info btn-block" wire:click='generarpdf'>Generar Contrato</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <h5 class="modal-title" id="tema">Seleccione cliente</h5>
                        @empty($data)
                            <p>Sin resultados</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $cliente)
                                            <tr>
                                                <td>{{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</td>
                                                <!-- Puedes agregar más columnas según tus necesidades -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    
                            <!-- Agregar controles de paginación si hay más de una página -->
                                <div class="mt-4">
                                    {{ $data->links() }}
                                </div>
                        @endempty
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
                Swal.fire({
                    icon: 'success',
                    title: message[0],
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {


                        window.location.href = '/ventas';

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