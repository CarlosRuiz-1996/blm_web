<div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <x-label>Razón Social:</x-label>

                                <x-input disabled wire:model="form.razon_social" type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>RFC:</x-label>
                                <x-input disabled wire:model="form.rfc_cliente" type="text" />

                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>Ejecutivo:</x-label>
                                <x-input disabled wire:model="form.ejecutivo" type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>Fecha solicitud:</x-label>
                                <x-input disabled wire:model="form.fecha_solicitud" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-input-validado label="Grupo comercial:" placeholder="Grupo comercial:"
                                    wire-model="form.grupo" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-select-validado label="Tipo de solicitud:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_solicitud_id" required>
                                    <option value="0" selected>Seleccione</option>
                                    @foreach ($ctg_tipo_solicitud as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-select-validado label="Tipo de servicio:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_servicio_id" required>
                                    <option value="0" selected>Seleccione</option>

                                    @foreach ($ctg_tipo_servicio as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @foreach ($sucursales as $sucursal)
                <div class="col-md-12" x-data="{ open: false }">
                    <div class="alert alert-info" role="alert" @click="open = ! open">

                        <h2 class="">

                            {{ __('SUCURSAL: ') . $sucursal['sucursal']->sucursal }}
                            {{-- <i class="fa fa-chevron-down" aria-hidden="true"></i> --}}
                            <i x-bind:class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }" class="fa"
                                aria-hidden="true"></i>

                        </h2>
                    </div>

                    <div class="card card-outline card-info" x-show="open">
                        <div class="card-body">
                            <div class="col-md-12 mb-3">
                                <x-label>REMITENTE DEL SERVICIO :</x-label>

                                <x-input disabled
                                    value="{{ $sucursal['sucursal']->direccion .
                                        ' ' .
                                        $sucursal['sucursal']->cp->cp .
                                        ' ' .
                                        $sucursal['sucursal']->cp->estado->name }}"
                                    type="text" />
                            </div>
                            @foreach ($sucursal['servicios'] as $servicio)
                                <div class="card card-outline card-info">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-5 mb-3">
                                                <x-label>DESCRIPCÍON DEL SERVICIO:</x-label>
                                                    <input class="form-control" disabled type="text" value="{{ $servicio['servicio']->ctg_servicio->descripcion }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <x-select-validadolive label="HORARIO DE ENTREGA:"
                                                    placeholder="Selecciona"
                                                    wire-model="form.horarioEntrega.{{ $servicio['servicio']->id}}" required>
                                                    
                                                    @foreach ($ctg_horario_entrega as $ctg)
                                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                                    @endforeach

                                                </x-select-validadolive>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-select-validadolive label="DIA DE ENTREGA:" placeholder="Selecciona"
                                                    wire-model="form.diaEntrega.{{ $servicio['servicio']->id }}" required>
                                                    @foreach ($ctg_dia_entrega as $ctg)
                                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                                        </option>
                                                    @endforeach

                                                </x-select-validadolive>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-select-validadolive label="HORARIO DE SERVICIO:"
                                                    placeholder="Selecciona"
                                                    wire-model="form.horarioServicio.{{ $servicio['servicio']->id }}" required>
                                                    @foreach ($ctg_horario_servicio as $ctg)
                                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                                        </option>
                                                    @endforeach

                                                </x-select-validadolive>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-select-validadolive label="DIA DE SERVICIO:" placeholder="Selecciona"
                                                    wire-model="form.diaServicio.{{ $servicio['servicio']->id }}" required>
                                                    @foreach ($ctg_dia_servicio as $ctg)
                                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                                        </option>
                                                    @endforeach

                                                </x-select-validadolive>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <x-select-validadolive label="CONSIGNATARIO:" placeholder="Selecciona"
                                                    wire-model="form.consignatorio.{{ $servicio['servicio']->id }}" required>
                                                    @foreach ($ctg_consignatario as $ctg)
                                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                                        </option>
                                                    @endforeach

                                                </x-select-validadolive>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <x-label>Observaciones:</x-label>

                                <textarea class="form-control" wire:model="form.observaciones" rows="2">
                                </textarea>
                                
                            </div>
                            
                            <div class="col-md-4 mb-3">

                                <button class="btn btn-info" wire:click="$dispatch('confirm')">Guardar</button>

                            </div>
                        </div>
                    </div>
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
                    text: "El memorandum se creara y pasara a vailidacion",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('save-memo');
                    }
                })
            })

            Livewire.on('success', function([message]) {
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
