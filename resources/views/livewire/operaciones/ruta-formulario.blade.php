<div>
    <div class="container-fluid">
        <div class="row">
                <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <x-select-validado label="Nombre de la ruta:" placeholder="Seleccione"
                                    wire-model="form.ctg_tipo_solicitud_id" required>
                                    <option value="0" selected>Seleccione:</option>
                                    @foreach ($rutas as $ctg)
                                        <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                    @endforeach
                                </x-select-validado>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Hora de Inicio:" placeholder="" wire-model="form.grupo"
                                    type="datetime-local" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-input-validado label="Hora de finalización:" placeholder="" wire-model="form.grupo"
                                    type="datetime-local" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>





        </div>


    </div>
</div>
