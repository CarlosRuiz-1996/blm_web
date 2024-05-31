<div>

    {{-- sucursal nueva --}}
    <x-adminlte-modal wire:ignore.self id="modalNueva" title="Nueva sucursal" theme="info" icon="fas fa-bolt"
        size='xl' disable-animations>
        {{-- sucursales --}}

        <div class="col-md-12">
            <div class="card card-outline card-info">

                <div class="card-body">
                    {{-- sucursal general --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <x-input-validado label="Sucursal:" placeholder="Ingrese el nombre de la sucursal"
                                wire-model="form.sucursal" required type="text" />
                        </div>
                        <div class="col-md-4 mb-3">

                            <x-input-validado label="Correo:" placeholder="Ingrese la correo de la sucursal"
                                wire-model="form.correo" type="email" />
                        </div>

                        <!-- Información de contacto -->
                        <div class="col-md-4 mb-3">
                            <x-input-validado-telefono label="Teléfono:"
                                placeholder="Ingrese la teléfono de la sucursal" wire-model="form.phone" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Contacto:" placeholder="Ingrese contacto de la sucursal"
                                wire-model="form.contacto" required type="email" />
                        </div>


                        <div class="col-md-6 mb-3">
                            <x-input-validado label="Cargo:" placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.cargo" type="text" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-validado-date label="Fecha de evaluación:"
                                placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.fecha_evaluacion" type="date" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-validado-date label="Fecha de inicio de servicio:"
                                placeholder="Ingrese cargo del contacto de la sucursal"
                                wire-model="form.fecha_inicio_servicio" type="date" />
                        </div>
                    </div>
                    {{-- direccion --}}
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-input-validado label="Codigo postal:" placeholder="Codigo postal" wire-model="form.cp"
                                type="number" />
                        </div>
                        <div class="col-md-2 mb-3" style="margin-top: 3%">
                            <div class="form-group">
                                <button wire:click='validarCp' class="btn btn-secondary btn-block">Validar
                                    cp</button>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label>Alcaldia/Municipio:</label>
                                <input type="text" class="form-control" disabled wire:model='form.municipio'
                                    placeholder="esperando..." required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label>Estado:</label>
                                <input type="text" class="form-control" disabled wire:model='form.estado'
                                    placeholder="esperando..." required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-select-validado label="Colonia:" placeholder="Selecciona" wire-model="form.ctg_cp_id"
                                required>

                                @if ($form->colonias)
                                    @foreach ($form->colonias as $cp)
                                        @if ($form->ctg_cp_id == $cp->id)
                                            <option value="{{ $cp->id }}" selected>{{ $cp->colonia }}
                                            </option>
                                        @break

                                    @else
                                        <option value="{{ $cp->id }}">{{ $cp->colonia }}</option>
                                    @endif
                                @endforeach
                            @else
                                <option value="">Esperando...</option>
                            @endif

                        </x-select-validado>
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-input-validado label="Calle y Número:" placeholder="Calle y Número"
                            wire-model="form.direccion" type="text" />

                    </div>
                    <div class="col-md-4 mb-3">
                        <x-input-validado label="Referencias:" placeholder="Alguna referencia"
                            wire-model="form.referencias" type="text" />

                    </div>


                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info btn-block"
                            wire:click="$dispatch('confirm', 1)">Guardar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-adminlte-modal>

<div class="modal fade" wire:ignore.self id="modalElegir" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Elegir sucursal</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    {{-- Success is as dangerous as failure. --}}
                    <x-adminlte-button label="Agregar sucursal" data-toggle="modal" data-target="#modalNueva"
                         class="bg-primary" />
                    <x-select-validado label="Sucursales:" placeholder="" wire-model="form.sucursal_id" required>
                        @if ($sucursales && count($sucursales) > 0)
                            @foreach ($sucursales as $sucursal)
                                {{-- <option value="{{ $sucursal->id }}"> --}}
                                <option value="{{ $sucursal->id }}"
                                    {{ $selectedSucursalId == $sucursal->id ? 'selected' : '' }}>

                                    {{ $sucursal->sucursal }}</option>
                            @endforeach
                        @else
                            <option value="">Sin sucursales</option>
                        @endif
                    </x-select-validado>
                </div>
                @if ($sucursales && count($sucursales) > 0)
                    @if ($selectedSucursalId == '')
                        <x-adminlte-button class="btn-flat ml-2" type="submit" wire:click='sucursal_servicio'
                            label="Asignar" theme="info" icon="fas fa-lg fa-save" />
                    @else
                        <x-adminlte-button class="btn-flat ml-2" type="submit"
                            wire:click='actualizarSucursalServicio' label="Cambiar" theme="info"
                            icon="fas fa-lg fa-save" />
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" wire:click='limpiarDatos' class="btn btn-secondary"
                    data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('open-sucursal', () => {

                $('#modalElegir').modal('show');
            })
        });
    </script>
@endpush
</div>
