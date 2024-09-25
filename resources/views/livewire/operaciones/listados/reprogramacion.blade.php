<div wire:init='loadServicios'>

    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    @if (count($reprogramacion))

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Dirección</th>
                                    <th>Monto</th>
                                    <th>Papeleta</th>
                                    <th>Envases</th>
                                    <th>Tipo</th>
                                    <th>Motivo</th>
                                    <th>Estatus</th>
                                    <th>Detalles</th>
                                </thead>
                                <tbody>
                                    @foreach ($reprogramacion as $repro)
                                        <tr style="text-size-adjust: 50">
                                            <td>{{ $repro->ruta_servicio->servicio->cliente->razon_social }}</td>
                                            <td>{{ $repro->ruta_servicio->servicio->ctg_servicio->descripcion }}</td>
                                            <td>
                                                Calle
                                                {{ $repro->ruta_servicio->servicio->sucursal->sucursal->direccion .
                                                    ' ' .
                                                    $repro->ruta_servicio->servicio->sucursal->sucursal->cp->cp .
                                                    ' ' .
                                                    $repro->ruta_servicio->servicio->sucursal->sucursal->cp->estado->name .
                                                    ' ' }}


                                            </td>
                                            <td>{{ $repro->ruta_servicio->monto }}</td>
                                            <td>{{ $repro->ruta_servicio->folio }}</td>
                                            <td>{{ $repro->ruta_servicio->envases }}</td>
                                            <td>{{ $repro->ruta_servicio->tipo_servicio == 1 ? 'ENTREGA' : 'RECOLECTA' }}
                                            </td>
                                            <td>{{ $repro->motivo }}</td>
                                            <td>
                                                <span class="badge 
                                                 {{ $repro->status_reprogramacions == 1 ? 'bg-secondary' : '' }}
                                                    {{ $repro->status_reprogramacions == 2 ? 'bg-warning' : '' }}
                                                    {{ $repro->status_reprogramacions == 3 ? 'bg-success' : '' }}
                                                
                                                " style="font-weight: bold;"> 

                                                    {{ $repro->status_reprogramacions == 1 ? 'PENDIENTE' : '' }}
                                                    {{ $repro->status_reprogramacions == 2 ? 'PROCESO/ASIGNADA' : '' }}
                                                    {{ $repro->status_reprogramacions == 3 ? 'FINALIZADA' : '' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($repro->status_reprogramacions == 1)
                                                    <button class="btn btn-xs btn-info mx-1 shadow"
                                                        title="Detalles de la sucursal" data-toggle="modal"
                                                        wire:click='DetalleServicio({{ $repro }})'
                                                        data-target="#addServicioRuta">
                                                        Asignar Ruta
                                                    </button>
                                                @else
                                                    <p>N/A</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($reprogramacion->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $reprogramacion->links() }}
                                </div>
                            @endif
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
            </div>
        </div>
    </div>


    {{-- servicio ruta --}}
    <x-adminlte-modal wire:ignore.self id="addServicioRuta" title="Agregar a ruta" theme="info" icon="fas fa-bolt"
        size='lg' disable-animations>

        @if ($repro_detail)
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="">Servicio</label>
                    <input class="form-control" disabled
                        value="Calle {{ $repro_detail->ruta_servicio->servicio->ctg_servicio->descripcion }}" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validadolive label="Monto:" :readonly="true" placeholder="Monto." wire-model="form.monto"
                        type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validadolive label="Folio:" :readonly="true" placeholder="Folio." wire-model="form.folio"
                        type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validadolive label="Envases:" :readonly="true" placeholder="Cantidad de envases."
                        wire-model="form.envases" type="text" />
                </div>
            </div>
            <x-select-validadolive label="Dia de la ruta:" placeholder="Selecciona un dia"
                wire-model="form.ctg_ruta_dia_id" required>

                @if ($dias)
                    @foreach ($dias as $dia)
                        <option value="{{ $dia->id }}">{{ $dia->name }}
                        </option>
                    @endforeach
                @else
                    <option value="">Esperando...</option>
                @endif

            </x-select-validadolive>
            @if ($form->ctg_ruta_dia_id)
                @if (count($rutas_dia))
                    <x-select-validadolive label="Ruta:" placeholder="Selecciona una ruta" wire-model="form.ruta_id"
                        required>
                        @foreach ($rutas_dia as $ruta)
                            <option value="{{ $ruta->id }}">{{ $ruta->nombre->name }}
                            </option>
                        @endforeach
                    </x-select-validadolive>
                @else
                    <p>No hay rutas disponibles para este dia. <a href="{{ route('ruta.gestion', 1) }}">Agregar
                            Ruta</a>
                    </p>
                @endif
            @endif
        @else
            <div class="col-md-12 text-center">
                <div class="spinner-border" role="status">
                </div>
            </div>
        @endif

        @if ($repro_detail)
            <button type="submit" class="btn btn-info" wire:click="$dispatch('reprogramar_confirm')">Guardar</button>
        @endif
    </x-adminlte-modal>



    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('reprogramar_confirm', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "El servicio sera asignado a la ruta indicada y solo se realizara una vez",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('save-reprogramacion-ruta');
                        }
                    })
                });

                Livewire.on('alert-repro', function(data) {
                    $('#addServicioRuta').modal('hide');

                    Swal.fire({
                        icon: data[1].tipo,
                        title: data[0].msg,
                        showConfirmButton: false,
                        timer: 3000
                    });

                  
                    // Al ocultar el modal
                    $('#addServicioRuta').on('hide.bs.modal', function(e) {
                        $(this).attr('aria-hidden', 'true');
                        $(this).attr('inert', '');
                    });

                    // Al cerrar el modal
                    $('#addServicioRuta').on('hidden.bs.modal', function(e) {
                        // Devolver el foco a un elemento adecuado, por ejemplo, un botón que abre el modal
                        $('#button-that-opens-modal').focus();
                    });

                });
            });
        </script>
    @endpush
</div>
