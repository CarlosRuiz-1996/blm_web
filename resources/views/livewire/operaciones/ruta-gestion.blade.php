<div>

    {{-- cabecera --}}
    <div class="d-sm-flex align-items-center justify-content-between">
    
        <table class="table " width="100%" cellspacing="0">
            <tr>
                <td align="left" class="d-flex align-items-center">
                    <h1 class="me-4">

                        <a href="/operaciones" title="ATRAS" class="me-2">
                            <i class="fa fa-arrow-left"></i>
                        </a>

                        {{ $op == 1 ? 'Nueva Ruta' : 'Gestión de Ruta' }}
                    </h1>
                </td>
                <td align="center" style="width: 40%;">
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
                            <div style="width: 100%;">
                                Total: {{ number_format($total_ruta, 2) }}
                            </div>
                            <div class="progress" style="width: 120%;">
                                <div class="progress-bar {{ $progressClass }} progress-bar-striped progress-bar-animated"
                                    role="progressbar" style="width: {{ $progressPercentage }}%;"
                                    aria-label="Basic example" aria-valuenow="{{ $progressPercentage }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>

                    @endif
                </td>
                <td align="right" style="width: 20%;">
                    @if ($op != 1)
                    <a target="_blank" href="{{route('ruta.pdf',[$form->ruta])}}">
                        <i title="Hoja de ruta" style="color: red;" class="fas fa-file-pdf fa-2x"
                            aria-hidden="true"></i>
                    </a>
                    @endif
                </td>

            </tr>

        </table>

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
                            <div class="col-md-4 mb-3">
                                <label>Nombre de la ruta:</label>
                                <input type="text" readonly class="form-control" wire:model='form.ctg_rutas_id'>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Dia de la ruta:</label>
                                <input type="text" readonly class="form-control" wire:model='form.ctg_ruta_dia_id'>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Fecha a realizar Servicios:</label>
                                <input id="ruta_fecha" type="date" @if(!$this->form->botonhablitarruta) readonly @endif wire:model.live='form.dia_id_calendario' class="form-control" />
                            </div>
                        
                            <!-- Clases condicionales para las columnas de Hora de Inicio y Hora de Finalización -->
                            <div class="{{ $this->form->ruta->ctg_rutas_estado_id == 2 ? 'col-md-6' : 'col-md-4' }} mb-3">
                                <label>Hora de Inicio:</label>
                                <input wire:model="form.hora_inicio" type="time" @if(!$this->form->botonhablitarruta) readonly @endif class="form-control @error('form.hora_inicio') is-invalid @enderror" />
                                @error('form.hora_inicio')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="{{ $this->form->ruta->ctg_rutas_estado_id == 2 ? 'col-md-6' : 'col-md-4' }} mb-3">
                                <label>Hora de Finalización:</label>
                                <input wire:model="form.hora_fin" type="time" @if(!$this->form->botonhablitarruta) readonly @endif class="form-control @error('form.hora_fin') is-invalid @enderror" />
                                @error('form.hora_fin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>                           
                        
                            @if ($this->form->ruta->ctg_rutas_estado_id == 1)
                            <div class="col-md-4 mb-3 mt-2">
                                @if(!$this->form->botonhablitarruta)
                                    <button type="button" name="ediatruta" id="editarruta" wire:click='editarrutahora' class="btn btn-primary btn-block mt-4">Editar Ruta</button>
                                @else
                                <div class="row">
                                    <div class="col-6 col-md-6 col-sm-12  mt-4">
                                        <button type="button" name="guardaruta" id="guardaruta" wire:click='guardaredicionhoraruta' class="btn btn-success btn-block">Guardar</button>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-12  mt-4">
                                        <button type="button" name="cancelaruta" id="cancelaruta" wire:click='cancelaredicionhoraruta' class="btn btn-danger btn-block">Cancelar</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
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
            <livewire:operaciones.rutas.agregar-servicio :ruta="$form->ruta" wire:model="form.dia_id_calendario" />

            <livewire:operaciones.rutas.listar-compras :ruta="$form->ruta" />

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

                    console.log('firma' + firma);

                    if (op == 1 || boveda_pase == 1) {
                        //!total_ruta || 
                        if (total_ruta > 10000000 && (firma === undefined || firma.length === 0)) {

                            valida10m();
                        } else {

                            if (firma && firma[0].status_ruta_firma10_m_s == 0) {
                                //en espera de validaciones
                                Swal.fire({
                                    title: '¡Atencion!',
                                    text: "Se espera la confirmación para poder llevar más de 10 Millones",
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 4000
                                })
                            } else if ((firma && firma[0].status_ruta_firma10_m_s == 1) || op == 1) {
                                //aprobada
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
                            } else if (firma && firma[0].status_ruta_firma10_m_s == 2) {
                                //rechazada
                                //validar si ya es menor a 10
                                if (total_ruta < 10000000) {
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
                                } else {
                                    Swal.fire({
                                        title: '¡Atencion!',
                                        text: "La ruta no puede llevar más de 10 Millones",
                                        icon: 'warning',
                                        showCancelButton: false,
                                        timer: 4000
                                    })
                                }
                            } else {
                                //no hay firma
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
                Livewire.on('successRutaHora', function([message]) {
                    var ruta = message[1];

                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        text: message[1],
                        showConfirmButton: false,
                        timer: 4000
                    });
                });
                Livewire.on('successRutaServicios', function([message]) {
                    var ruta = message[1];

                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        text: message[1],
                        showConfirmButton: false,
                        timer: 4000
                    });
                });
                
                Livewire.on('errorRutaHora', function(message) {
                    console.log(message);
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

            const rutaDiaId = @json($this->form->diaid); // Día de la semana permitido (1=lunes, ..., 6=sábado, 0=domingo)
            const inputFecha = document.getElementById('ruta_fecha');

// Función para calcular el próximo día permitido a partir de una fecha dada
function calcularProximoDia(fecha, diaPermitido) {
    let diaActual = fecha.getUTCDay(); // Obtener el día actual (0: Domingo, 1: Lunes, ..., 6: Sábado)
    // Convertir día de 1-7 a 0-6 (Día de la semana en JavaScript)
    const diaPermitidoJS = diaPermitido === 7 ? 0 : diaPermitido; 
    const diasHastaPermitido = (diaPermitidoJS - diaActual + 7) % 7;
    return new Date(fecha.setDate(fecha.getDate() + diasHastaPermitido));
}

// Función para habilitar solo los días permitidos
function habilitarDiasPermitidos() {
    const hoy = new Date();
    const proximoDiaPermitido = calcularProximoDia(new Date(hoy), rutaDiaId); // Encontrar el próximo día permitido

    // Establecer el valor inicial del input como el próximo día permitido
    inputFecha.value = proximoDiaPermitido.toISOString().split('T')[0]; // Establecer el valor inicial del input

    // Establecer el día mínimo permitido
    inputFecha.setAttribute('min', proximoDiaPermitido.toISOString().split('T')[0]);

    // Añadir evento para validar la selección
    inputFecha.addEventListener('input', function () {
        const fechaSeleccionada = new Date(this.value);
        const diaSeleccionado = fechaSeleccionada.getUTCDay(); // Obtener el día seleccionado

        // Verificar si el día seleccionado es el permitido
        if (diaSeleccionado !== rutaDiaId) {
            Swal.fire({
                                        title: '¡Atencion!',
                                        text: "Solo puede selecionar dias : "+getDiaNombre(rutaDiaId),
                                        icon: 'warning',
                                        showCancelButton: false,
                                        timer: 4000
                                    })
            this.value = proximoDiaPermitido.toISOString().split('T')[0]; // Restablecer al próximo día permitido
        }
    });
}

// Función para obtener el nombre del día
function getDiaNombre(dia) {
    const nombresDias = {
        1: "Lunes",
        2: "Martes",
        3: "Miércoles",
        4: "Jueves",
        5: "Viernes",
        6: "Sábado",
        0: "Domingo" // En JavaScript, 0 es Domingo
    };
    return nombresDias[dia];
}

// Habilitar los días permitidos al cargar
habilitarDiasPermitidos();

        </script>
    @endpush
</div>
