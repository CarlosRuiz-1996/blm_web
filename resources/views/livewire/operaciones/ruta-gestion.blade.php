<div>
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        @if (!$form->ruta)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <x-select-validadolive label="Dia de la ruta:" placeholder="Seleccione"
                                        wire-model="form.ctg_ruta_dia_id" required>
                                        @foreach ($dias as $dia)
                                            <option value="{{ $dia->id }}">{{ $dia->name }}</option>
                                        @endforeach
                                    </x-select-validadolive>
                                </div>
                                @if ($dia_select)

                                    <div class="col-md-4 mb-3">
                                        <x-select-validado label="Nombre de la ruta:" placeholder="Seleccione"
                                            wire-model="form.ctg_rutas_id" required>
                                            {{ $form->ctg_rutas_id }}
                                            <option value="0" selected>Seleccione:</option>
                                            @foreach ($rutas as $ctg)
                                                <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                            @endforeach
                                        </x-select-validado>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Hora de Inicio:" placeholder=""
                                            wire-model="form.hora_inicio" type="time" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-validado label="Hora de finalización (opcional):" placeholder=""
                                            wire-model="form.hora_fin" type="time" />
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <button type="submit" class="btn btn-info btn-block"
                                            wire:click="$dispatch('confirm',1)">Guardar</button>
                                    </div>

                                @endif

                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Dia de la ruta:</label>
                                    <input type="text" readonly class="form-control"
                                        wire:model='form.ctg_ruta_dia_id'>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Nombre de la ruta:</label>
                                    <input type="text" readonly class="form-control" wire:model='form.ctg_rutas_id'>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Hora de Inicio:</label>
                                    <input wire:model="form.hora_inicio" type="time" readonly class="form-control" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Hora de Finalización:</label>
                                    <input wire:model="form.hora_fin" type="time" readonly class="form-control" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- complementos de la ruta --}}
        @if ($form->ruta)
            {{-- vehiculos --}}
            
            <livewire:operaciones.rutas.agregar-vehiculo :ruta="$form->ruta"/>
            
            {{-- elementos de seguridad --}}
            <div class="row">
                <h1 class="ml-3">Equipo de seguridad</h1>
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-body">
                            <div class="row">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- servicios --}}
            <livewire:operaciones.rutas.agregar-servicio />


            <div class="col-md-12 ">
                <button wire:click='save' class="btn btn-info btn-block">Guardar</button>

            </div>
        @endif
    </div>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', (op) => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: op == 1 ? "La ruta sera guardada en la base de datos" : "",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch(op == 1 ? 'save-ruta' : 'update-ruta');
                        }
                    })
                })

                Livewire.on('success', function([message]) {
                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        text: message[1],
                        showConfirmButton: false,
                        timer: 4000
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
