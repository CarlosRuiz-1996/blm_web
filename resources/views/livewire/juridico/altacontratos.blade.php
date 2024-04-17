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
                                            <th>Nombre</th>
                                            <th>Apellido Paterno</th>
                                            <th>Apellido Materno</th>
                                            <!-- Puedes agregar más columnas según tus necesidades -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $cliente)
                                            <tr>
                                                <td>{{ $cliente->name }}</td>
                                                <td>{{ $cliente->paterno }}</td>
                                                <td>{{ $cliente->materno }}</td>
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