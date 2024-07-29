<div>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
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
                                <th>Fecha</th>
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
                                        <td>{{ $repro->ruta_servicio->updated_at }}</td>
                                        <td>

                                            <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                title="Detalles de la sucursal" data-toggle="modal"
                                                wire:click='DetalleServicio({{ $repro }})'
                                                data-target="#addServicioRuta">
                                                Asignar Ruta
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- servicio ruta --}}
    <div class="modal fade" id="addServicioRuta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar a ruta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($repro_detail)
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Servicio</label>
                                <input class="form-control" disabled
                                    value="Calle {{ $repro_detail->ruta_servicio->servicio->ctg_servicio->descripcion }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validadolive label="Monto:" :readonly="true" placeholder="Monto."
                                    wire-model="form.monto" type="text" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validadolive label="Folio:" :readonly="true" placeholder="Folio."
                                    wire-model="form.folio" type="text" />
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
                                <x-select-validadolive label="Ruta:" placeholder="Selecciona una ruta"
                                    wire-model="form.ruta_id" required>
                                    @foreach ($rutas_dia as $ruta)
                                        <option value="{{ $ruta->id }}">{{ $ruta->nombre->name }}
                                        </option>
                                    @endforeach
                                </x-select-validadolive>
                            @else
                                <p>No hay rutas disponibles para este dia. <a
                                        href="{{ route('ruta.gestion', 1) }}">Agregar
                                        Ruta</a>
                                </p>
                            @endif
                        @endif
                    @else
                        <div class="col-md-12 text-center">
                            <div class="spinner-border" role="status">
                                {{-- <span class="visually-hidden">Loading...</span> --}}
                            </div>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @if ($repro_detail)
                        <button type="submit" class="btn btn-info"
                            wire:click="$dispatch('reprogramar_confirm')">Guardar</button>
                    @endif
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
            });
        </script>
    @endpush
</div>
