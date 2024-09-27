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
                                    <th>Area</th>
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
                                                {{$repro->area->name}}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                                 {{ $repro->status_reprogramacions == 1 ? 'bg-secondary' : '' }}
                                                    {{ $repro->status_reprogramacions == 2 ? 'bg-warning' : '' }}
                                                    {{ $repro->status_reprogramacions == 3 ? 'bg-success' : '' }}
                                                
                                                "
                                                    style="font-weight: bold;">

                                                    {{ $repro->status_reprogramacions == 1 ? 'PENDIENTE' : '' }}
                                                    {{ $repro->status_reprogramacions == 2 ? 'PROCESO/ASIGNADA' : '' }}
                                                    {{ $repro->status_reprogramacions == 3 ? 'FINALIZADA' : '' }}
                                                </span>
                                            </td>
                                            
                                            <td class="text-center">
                                                @if ($repro->status_reprogramacions == 1)
                                                    <button class="btn btn-sm btn-info mx-1 shadow"
                                                        title="Detalles de la sucursal" data-toggle="modal"
                                                        wire:click='DetalleServicio({{ $repro }})'
                                                        data-target="#addServicioRuta">
                                                        Asignar Ruta
                                                    </button>
                                                @endif
                                                @if ($repro->status_reprogramacions != 1&&!$repro->evidencia)
                                                    N/A
                                                @endif
                                                @if ($repro->evidencia)
                                                    <button class="btn btn-info mt-5" data-toggle="modal"
                                                        data-target="#evidenciaModal"
                                                        wire:click='evidenciaRepro({{ $repro }})'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="currentColor" class="bi bi-image"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M13.002 1H2.998C2.447 1 2 1.447 2 2.002v11.996C2 14.553 2.447 15 2.998 15h11.004c.551 0 .998-.447.998-.998V2.002A1.002 1.002 0 0 0 13.002 1zM3 2h10a1 1 0 0 1 1 1v8.586l-3.293-3.293a1 1 0 0 0-1.414 0L7 10.586 5.707 9.293a1 1 0 0 0-1.414 0L3 10.586V3a1 1 0 0 1 1-1z" />
                                                            <path
                                                                d="M10.707 9.293a1 1 0 0 1 1.414 0L15 12.172V13a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-.828l3.293-3.293a1 1 0 0 1 1.414 0L7 10.586l3.707-3.707zM10 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                        </svg>
                                                    </button>
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

    <!-- Modal evidencia-->
    <div class="modal fade" id="evidenciaModal" wire:ignore.self tabindex="-1" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <!-- Tamaño del modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Evidencia</h5>
                </div>
                <div class="modal-body text-center">
                    @if ($readyToLoadModal)
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $evidencia_foto) }}" alt="Evidencia" class="img-fluid">
                        </div>
                    @else
                        <div class="col-md-12 text-center">
                            <div class="spinner-border" role="status"></div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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


                $('#evidenciaModal').on('hidden.bs.modal', function(e) {
                    @this.call('clean');
                });
            });
        </script>
    @endpush
</div>
