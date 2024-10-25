<div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">


                        <div class="card-body d-flex justify-content-between align-items-center">
                            <a class="btn btn-outline-primary" href="">ACTUALIZAR FIRMAS</a>
                            <h1 class="text-center mb-0" style="margin-left: -150px">Validado por:</h1>
                        </div>

                        @php
                            //contador para las firmas
                            $no_firmas = 0;
                        @endphp

                        <table class="table table-hover table-bordered" width="100%" cellspacing="0"
                            style="font-size:100%">
                            <thead class="table-secondary">
                                <th colspan="2">VENTAS</th>
                                <th colspan="2">OPERACIONES</th>
                                <th colspan="2">BOVEDA</th>
                                <th colspan="2">PROCESO</th>
                            </thead>
                            <tbody>
                                @php
                                    $tiene_firma = [];
                                @endphp

                                @foreach ($firmas as $firma)
                                    @foreach ([1, 2, 3, 4] as $IdArea)
                                        @if ($IdArea == $firma->revisor_areas->area->id)
                                            <td colspan="2">
                                                <i class="fa fa-circle" style="color: green;"> </i>
                                                {{ $firma->revisor_areas->empleado->user->fullName() }}
                                            </td>
                                            @php
                                                $tiene_firma[] = $IdArea;
                                                $no_firmas++;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                                @foreach ([1, 2, 3, 4] as $IdArea)
                                    @unless (in_array($IdArea, $tiene_firma))
                                        <td colspan="2">
                                            <i class="fa fa-circle" style="color: orange;"> </i>Aún no validado 
                                        </td>
                                    @endunless
                                @endforeach
                            </tbody>
                            <thead class="table-secondary">
                                <th colspan="2">CONTABILIDAD</th>
                                <th colspan="2">FACTURACIÓN</th>
                                <th colspan="2">COBRANZA</th>
                                <th colspan="2">Vo Bo GERENCIA</th>
                            </thead>
                            <tbody>
                                @php
                                    $tiene_firma = [];
                                @endphp

                                @foreach ($firmas as $firma)
                                    @foreach ([5, 6, 7, 8] as $IdArea)
                                        @if ($IdArea == $firma->revisor_areas->area->id)
                                            <td colspan="2">
                                                <i class="fa fa-circle" style="color: green;"> </i>
                                                {{ $firma->revisor_areas->area->name }}
                                            </td>
                                            @php
                                                $tiene_firma[] = $IdArea;
                                                $no_firmas++;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                                @foreach ([5, 6, 7, 8] as $IdArea)
                                    @unless (in_array($IdArea, $tiene_firma))
                                        <td colspan="2">
                                            <i class="fa fa-circle" style="color: orange;"> </i> Aún no validado
                                        </td>
                                    @endunless
                                @endforeach
                            </tbody>
                        </table>
                        @if ($no_firmas == 8)
                            <div class="mt-3">
                                <center>
                                    <button class="btn btn-info" wire:click="$dispatch('confirm')">FINALIZAR</button>

                                </center>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

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

                                <x-label>Grupo comercial:</x-label>

                                <x-input disabled wire:model="form.grupo" type="text" />

                            </div>
                            <div class="col-md-4 mb-3">
                                <x-label>Tipo de solicitud:</x-label>

                                <x-input disabled wire:model="form.ctg_tipo_solicitud_id" type="text" />


                            </div>
                            <div class="col-md-4 mb-3">
                                <x-label>Tipo de servicio:</x-label>

                                <x-input disabled wire:model="form.ctg_tipo_servicio_id" type="text" />


                            </div>
                            <div class="col-md-12 mb-3">
                                <x-label>Observaciones:</x-label>

                                <textarea class="form-control" readonly wire:model="form.observaciones" rows="2">
                                </textarea>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="ml-3">
                SUCURSALES
            </h2>
            @foreach ($sucursales as $sucursal)
                <div class="col-md-12" x-data="{ open: false }">
                    <div class="alert alert-info" role="alert" @click="open = ! open">

                        <h4 class="">

                            {{ $sucursal->sucursal }}
                            <i x-bind:class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }" class="fa"
                                aria-hidden="true"></i>

                        </h4>
                    </div>

                    <div class="card card-outline card-info" x-show="open">

                        <div class="card-body">

                            <div class="col-md-12 mb-3">
                                <x-label>REMITENTE DEL SERVICIO :</x-label>

                                <x-input disabled
                                    value="{{ $sucursal->direccion . ' ' . $sucursal->cp->cp . ' ' . $sucursal->cp->estado->name }}"
                                    type="text" />
                            </div>
                            @foreach ($memo_servicio as $memo)
                                @if ($memo->sucursal_servicio->sucursal_id == $sucursal->id)
                                    <div class="card card-outline card-info">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-4 mb-3">
                                                    <x-label>DESCRIPCÍON DEL SERVICIO:</x-label>
                                                    <input class="form-control" disabled type="text"
                                                        value="{{ $memo->sucursal_servicio->servicio->ctg_servicio->descripcion }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <x-label>HORARIO DE ENTREGA:</x-label>
                                                    <input class="form-control" disabled type="text"
                                                        value="{{ $memo->hora_entrega->name }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <x-label>DIA DE ENTREGA:</x-label>
                                                    <input class="form-control" disabled type="text"
                                                        value="{{ $memo->dia_entrega->name }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <x-label>HORARIO DE SERVICIO:</x-label>
                                                    <input class="form-control" disabled type="text"
                                                        value="{{ $memo->hora_servicio->name }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <x-label>CONSIGNATARIO:</x-label>
                                                    <input class="form-control" disabled type="text"
                                                        value="{{ $memo->consignatario->name }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach


        </div>


    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "El memorandum finalizara y pasaran los servicios al area de operaciones.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('save-finalizacion');
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
