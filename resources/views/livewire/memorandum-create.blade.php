<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <x-label>Razón Social:</x-label>

                                <x-input disabled wire:model="form.rz" type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>RFC:</x-label>
                                <x-input disabled wire:model="form.rfc" type="text" />

                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>Ejecutivo:</x-label>
                                <x-input disabled wire:model="form.ejecutivo" type="text" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <x-label>Fecha solicitud:</x-label>
                                <x-input disabled wire:model="form.solicitud" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-input-validado label="Grupo comercial:" placeholder="Grupo comercial:"
                                    wire-model="form.grupo" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-select-validado label="Tipo de solicitud:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_solicitud_id" required>
                                    @foreach ($ctg_tipo_solicitud as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>

                            </div>
                            <div class="col-md-4 mb-3">

                                <x-select-validado label="Tipo de servicio:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_servicio_id" required>
                                    @foreach ($ctg_tipo_servicio as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>