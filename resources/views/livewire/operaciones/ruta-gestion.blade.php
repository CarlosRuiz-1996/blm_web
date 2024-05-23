<div>

    {{-- cabecera --}}
    <div class="d-sm-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h1 class="me-4">
                <a href="/operaciones" title="ATRAS" class="me-2">
                    <i class="fa fa-arrow-left"></i>
                </a>

                {{ $op == 1 ? 'Nueva Ruta' : 'Gestión de Ruta' }}
            </h1>


        </div>
        @if ($op != 1)
            @php
                $maxValue = 10000000; // 10 millones
                $progressPercentage = min(($total_ruta / $maxValue) * 100, 100); // Asegurarse de no exceder 100%

                $progressClass = '';

                if ($total_ruta >= 10000000) {
                    $progressClass = 'bg-danger'; // Rojo para >= 10 millones
                } elseif ($total_ruta >= 7000000) {
                    $progressClass = 'bg-warning'; // Amarillo para >= 7 millones
                } else {
                    $progressClass = 'bg-success'; // Verde para menos de 7 millones
                }
            @endphp
            <div class="w-50">
                <div style="width: 100%;">
                    Total: {{ number_format($total_ruta, 2) }}
                </div>
                <div class="progress" style="width: 120%;">
                    <div class="progress-bar {{ $progressClass }} progress-bar-striped progress-bar-animated"
                        role="progressbar" style="width: {{ $progressPercentage }}%;" aria-label="Basic example"
                        aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        @endif

        <div class="w-80">
            <div style="width: 100%;">
                @if ($op == 1)
                    <livewire:operaciones.rutas.ruta-ctg-ruta class="me-4" />
                @endif
            </div>
        </div>
    </div>

    {{-- cuerpo --}}
    <div class="container-fluid mb-5">
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
                                            wire:click="$dispatch('confirm',[1])">Guardar</button>
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

            <livewire:operaciones.rutas.agregar-vehiculo :ruta="$form->ruta" />
            {{-- elementos de operaciones --}}
            <livewire:operaciones.rutas.agregar-operador :ruta="$form->ruta" />
            {{-- elementos de seguridad --}}
            <livewire:operaciones.rutas.agregar-personal :ruta="$form->ruta" />
            {{-- servicios --}}
            <livewire:operaciones.rutas.agregar-servicio :ruta="$form->ruta" />

            @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                <div class="col-md-12 ">
                    <button
                        wire:click="$dispatch('confirm',[2,{{ $boveda_pase }},{{ $total_ruta }}, {{ $firma }}])"
                        class="btn btn-info btn-block">Enviar a boveda</button>

                </div>
            @endif
        @endif
    </div>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', ([op, boveda_pase, total_ruta, firma]) => {

                    if (op == 1 || boveda_pase == 1) {
                        //!total_ruta || 
                        if (total_ruta > 10000000 && !firma) {
                            valida10m();
                        } else {
                            Swal.fire({
                                title: '¿Estas seguro?',
                                text: op == 1 ? "La ruta sera guardada en la base de datos" :
                                    "La ruta pasara a boveda.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Si, adelante!',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.dispatch(op == 1 ? 'save-ruta' : 'update-ruta', {
                                        accion: 1
                                    });
                                }
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ruta Incompleta.',
                            text: 'Debe de completar la ruta para poder pasar a boveda.',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                })

                Livewire.on('success', function([message]) {
                    var ruta = message[2];

                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        text: message[1],
                        showConfirmButton: false,
                        timer: 4000
                    });
                    if (ruta) {
                        window.location.href =
                            '{{ route('ruta.gestion', [':op', ':ruta']) }}'
                            .replace(':ruta', ruta)
                            .replace(':op', 2);
                    }

                });


                Livewire.on('error', function(message) {
                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                });


            });

            function valida10m() {
                Swal.fire({
                    title: '¡Supera los 10 Millones!',
                    text: "Para poder llevar más de 10 millones debe de pedir autorización " +
                        "a boveda y operaciones, ¿Quiere proceder?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, adelante!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('insert-firmas');
                    }
                })
            }
        </script>
    @endpush
</div>
