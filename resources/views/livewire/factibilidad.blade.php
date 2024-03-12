<div>
    <div class="container-fluid">

        <div class="row">
            {{-- cabecera --}}
            <div class="col-md-12">
                <livewire:cliente-cabecera :cliente="$form->cliente_id" />
            </div>
            {{-- sucursales --}}
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead class="table-info">
                                <th>PDA</th>
                                <th>Tipo de servicio</th>
                                <th>Sucursal</th>
                                <th>Domicilio</th>
                                <th>Detalles</th>
                                <th>Repote</th>
                            </thead>
                            <tbody>
                                @foreach ($sucursales as $sucursal)
                                    <tr>


                                        <td>{{ $sucursal->servicio->ctg_servicio->folio }}</td>
                                        <td>{{ $sucursal->servicio->ctg_servicio->descripcion }}</td>
                                        <td>{{ $sucursal->sucursal->sucursal }}</td>
                                        <td>{{ $sucursal->sucursal->direccion .
                                            ' ' .
                                            $sucursal->sucursal->cp->cp .
                                            ' ' .
                                            $sucursal->sucursal->cp->estado->name .
                                            ' ' }}
                                        </td>


                                        <td class="text-center">

                                            <button class="btn btn-xs btn-default text-primary mx-1 shadow"
                                                title="Detalles de la sucursal" data-toggle="modal"
                                                wire:click='DetalleSucursal({{ $sucursal->sucursal->id }})'
                                                data-target="#modalDetalles">
                                                <i class="fa fa-lg fa-fw fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            @if ($sucursal->sucursal->rpt_factibilidad_status == 0)
                                                <button class="btn btn-xs btn-default text-success mx-1 shadow"
                                                    title="LLenar reporte" data-toggle="modal"
                                                    wire:click='DetalleReporte({{ $sucursal->sucursal->id }})'
                                                    data-target="#modalRpt">
                                                    <i class="fa fa-2x fa-fw fa-file"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                    title="Ver reporte" wire:click="showPDF({{ $sucursal->sucursal }})">
                                                    <i class="fas fa-2x fa-file-pdf"></i>
                                                </button>
                                            @endif
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



    {{-- detalles sucursal --}}
    <x-adminlte-modal wire:ignore.self id="modalDetalles" title="Detalles de sucursal" theme="info" icon="fas fa-bolt"
        size='lg' disable-animations>
        <div class="col-md-12 mb-3">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-input-validado label="Evaluación:" placeholder="" wire-model="form.fecha_evaluacion"
                        type="text" />
                </div>

                <div class="col-md-4 mb-3">
                    <x-input-validado label="Inicio de servicio:" placeholder="" wire-model="form.fecha_inicio_servicio"
                        type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validado label="Ejecutivo de cuenta:" placeholder="" wire-model="form.ejecutivo" required
                        type="text" />
                </div>

                <div class="col-md-4 mb-3">
                    <x-input-validado label="Sucursal:" placeholder="" wire-model="form.sucursal_name" required
                        type="email" />
                </div>

                <div class="col-md-4 mb-3">
                    <x-input-validado label="Domicilio:" placeholder="" wire-model="form.direccion" type="text" />
                </div>

                <div class="col-md-4 mb-3">
                    <x-input-validado label="Correo:" placeholder="" wire-model="form.correo" type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validado label="Telefono:" placeholder="" wire-model="form.phone" type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validado label="Contacto:" placeholder="" wire-model="form.contacto" type="text" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-input-validado label="Cargo:" placeholder="" wire-model="form.cargo" type="text" />
                </div>
                <div class="col-md-12 mb-3">
                    <x-input-validado label="Direccion:" placeholder="" wire-model="form.direccion" type="text" />
                </div>
                <div class="col-md-12 mb-3">
                    @if ($form->servicios)
                        <table class="table table-striped">
                            <thead class="table-info">
                                <th>PDA</th>
                                <th>SERVICIO CONTRATADO PARA SOLICITUD DE EVALUACIÓN DE RIESGO </th>
                                <th>FECHA REQUERIDA PARA EVALUACIÓN</th>
                            </thead>
                            <tbody>

                                @foreach ($form->servicios as $servicio)
                                    <td>{{ $servicio->servicio->ctg_servicio->folio }}</td>
                                    <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                    <td>{{ $form->fecha_evaluacion }}</td>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <h5>Sin servicios asignados</h5>
                    @endif
                </div>

            </div>
        </div>


    </x-adminlte-modal>



    {{-- reporte generar --}}
    <x-adminlte-modal wire:ignore.self id="modalRpt" title="Reporte de factibilidad" theme="info" icon="fas fa-bolt"
        size='xl' disable-animations>

        {{-- encabezado --}}
        <div class="row g-3">
            <div class="form-group ml-3 col-md-3">
                <label for="fecha_ev" class="form-label">Fecha de Evaluación:</label>
                <input type="text" readonly class="form-control" style="text-transform:uppercase;"
                    wire:model='form.fecha_evaluacion' />
            </div>

            <div class="form-group  col-md-4">
                <label for="razon" class="form-label">Razón Social:</label>
                <input type="text" readonly wire:model="form.razon_social" class="form-control"
                    style="text-transform:uppercase;" />
            </div>

            <div class="form-group col-md-4">
                <label for="rfc" class="form-label">RFC:</label>
                <input type="text" readonly wire:model="form.rfc" class="form-control"
                    style="text-transform:uppercase;" />
            </div>
            <div class="form-group ml-3  col-md-3">
                <label for="sucursal" class="form-label">Sucursal:</label>
                <input type="text" readonly class="form-control"
                    style="text-transform:uppercase;"wire:model='form.sucursal_name' />
            </div>

            <div class="form-group  col-md-8">
                <label for="razonsocial" class="form-label">Domicilio:</label>
                <input type="text" readonly class="form-control"
                    style="text-transform:uppercase;"wire:model='form.direccion' />
            </div>
            <div class="form-group ml-3 col-md-5">
                <label class="control-label ">NOMBRE DEL EVALUADOR:</label>
                <div class="">
                    <input class="form-control" type="text" style="text-transform:uppercase;"
                        wire:model='form.evaluador' readonly>

                </div>
            </div>

        </div>
        {{-- nav --}}
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $active_form }}" wire:click='CambiarTab' id="form-tab" data-toggle="pill"
                    href="#form" role="tab" aria-controls="form" aria-selected="true">FORMULARIO</a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $active_img }}" wire:click='CambiarTab' id="img-tab" data-toggle="pill"
                    href="#img" role="tab" aria-controls="img" aria-selected="false">FOTOGRAFIAS DEL
                    ESTABLECIMIENTO (OPCIONAL)</a>
            </li>
            <!-- Puedes agregar más pestañas según sea necesario -->
        </ul>
        {{-- content del nav --}}
        <div class="tab-content " id="custom-tabs-one-tabContent">
            <div class="tab-pane fade {{ $active_form }}" id="form" role="tabpanel"
                aria-labelledby="form-tab">
                {{-- formulario --}}
                <div class="row g-3">
                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label">1. Indique el tipo de servicio que se le realizará al
                            cliente:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pregunta1" value="0"
                                wire:model='form.tiposervicio'>
                            <label class="form-check-label">
                                R.V.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pregunta1" value="1"
                                wire:model='form.tiposervicio'>
                            <label class="form-check-label">
                                E.V.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2" name="pregunta1"
                                wire:model='form.tiposervicio'>
                            <label class="form-check-label">
                                E. MON. MET.
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta1otro" value="3"
                                wire:model='form.tiposervicio'>
                            <label class="form-check-label">
                                OTROS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-control" type="text" id="pregunta1text"
                                wire:model='form.otro_tiposervicio' style="text-transform:uppercase;">
                        </div>
                        <x-input-error for="form.tiposervicio" />
                    </div>

                    <div class="form-group ml-5 col-md-5">
                        <label class="control-label">2. Indique cómo se realizará el servicio:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0"
                                wire:model='form.comohacerservicio'>
                            <label class="form-check-label">
                                Recorrido
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1"
                                wire:model='form.comohacerservicio'>
                            <label class="form-check-label">
                                Negocio del cliente
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2"
                                wire:model='form.comohacerservicio'>
                            <label class="form-check-label">
                                En unidad blindada
                            </label>
                        </div>
                        <x-input-error for="form.comohacerservicio" />

                    </div>

                    <div class="form-group ml-3 col-md-5">
                        <label class="form-label">3. Dia y horario de servicio:</label>
                        {{-- <select class="form-control" wire:model='form.horarioservicio'>
                            <option value="0" selected>Seleccione</option>
                            @foreach ($horarios as $hora)
                                <option value="{{ $hora->id }}">{{ $hora->name }}</option>
                            @endforeach
                        </select> --}}
                        <input class="form-control ml-5 col-sm-8" value="1" placeholder="LUN A VIE 18:0 A 20:00"
                            wire:model='form.horarioservicio' readonly>
                        <x-input-error for="form.horarioservicio" />
                        <label class="control-label">Numero de personas que se requieren para el servicio:</label>
                        <input class="form-control ml-5 col-sm-8" type="number"
                            wire:model='form.personalparaservicio' style="text-transform:uppercase;">
                        <x-input-error for="form.personalparaservicio" />
                    </div>

                    {{-- 4 --}}
                    <div class="form-group ml-5 col-md-6">
                        <label class="control-label ">4. Tipo de construcción en donde se realizará el
                            servicio:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pregunta4" value="0"
                                wire:model='form.tipoconstruccion'>
                            <label class="form-check-label">
                                concreto
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="pregunta4"
                                wire:model='form.tipoconstruccion'>
                            <label class="form-check-label">
                                Madera
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2" name="pregunta4"
                                wire:model='form.tipoconstruccion'>
                            <label class="form-check-label">
                                Tabla roca
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="3" name="pregunta4"
                                wire:model='form.tipoconstruccion'>
                            <label class="form-check-label">
                                vidrio
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta4otro" value="4"
                                wire:model='form.tipoconstruccion'>
                            <label class="form-check-label">
                                Otros
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-control" type="text" id="pregunta4text"
                                wire:model='form.otro_tipoconstruccion' style="text-transform:uppercase;">
                        </div>
                        <x-input-error for="form.tipoconstruccion" />

                    </div>
                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label ">5. Nivel de protección con el que cuenta el ligar:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta5" value="0"
                                wire:model='form.nivelproteccionlugar'>
                            <label class="form-check-label">
                                NIVEL I
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta5" value="1"
                                wire:model='form.nivelproteccionlugar'>
                            <label class="form-check-label">
                                NIVEL II
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta5" value="2"
                                wire:model='form.nivelproteccionlugar'>
                            <label class="form-check-label">
                                NIVEL III
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pregunta5" value="3"
                                wire:model='form.nivelproteccionlugar'>
                            <label class="form-check-label">
                                NIVEL IV
                            </label>
                        </div>
                        <x-input-error for="form.nivelproteccionlugar" />
                        <label class="control-label ml-3">El perímetro de la instalación se encuentra bardeado:
                        </label>
                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="pregunta5-5" value="0"
                                wire:model='form.perimetro'>
                            <label class="form-check-label">
                                No
                            </label>
                        </div>
                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="pregunta5-5" value="1"
                                wire:model='form.perimetro'>
                            <label class="form-check-label">
                                Si
                            </label>
                        </div>
                        <x-input-error for="form.perimetro" />

                    </div>

                    <div class="form-group ml-5 col-md-5">
                        <label class="control-label">6. Indique el número de accesos para llegar al lugar de la
                            recolección
                            o entrega de valores.</label>
                        <div class="form-group">
                            <label class="control-label">Peatonales</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="number" wire:model='form.peatonales'
                                    style="text-transform:uppercase;">
                                <x-input-error for="form.peatonales" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Vehiculares</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="number" wire:model='form.vehiculares'
                                    style="text-transform:uppercase;">
                                <x-input-error for="form.vehiculares" />

                            </div>
                        </div>
                    </div>

                    <div class="form-group ml-3 col-md-4">
                        <label class="control-label">7. Cuenta con control de accesos y registro de visitantes en
                            bitácora:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.ctrlacesos'
                                id="pregunta7" value="0">
                            <label class="form-check-label">
                                No
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.ctrlacesos'
                                id="pregunta7" value="1">
                            <label class="form-check-label">
                                Si
                            </label>
                        </div>
                        <x-input-error for="form.ctrlacesos" />
                    </div>

                    {{-- 8 --}}
                    <div class="form-group  col-md-7">
                        <div class="row g-3">
                            <div class="form-group  col-md-7">
                                <label class="control-label">8. Cuenta con servicio de guardias de
                                    seguridad:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregunta8"
                                        wire:model='form.guardiaseg' id="pregunta8" value="0">
                                    <label class="form-check-label">
                                        Propios
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregunta8"
                                        wire:model='form.guardiaseg' id="pregunta8" value="1">
                                    <label class="form-check-label">
                                        Seguridad privada
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pregunta8"
                                        wire:model='form.guardiaseg' id="pregunta8otros" value="2" required>
                                    <label class="form-check-label">
                                        Otros
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" id="pregunta8cual-label">(Indique cuales)</label>
                                    <input class="form-control" type="text" wire:model='form.otros_guardiaseg'
                                        id="pregunta8cual" style="text-transform:uppercase;">
                                </div>
                                <x-input-error for="form.guardiaseg" />

                            </div>

                            <div class="form-group col-md-5 mt-4">
                                <label class="control-label ">Armados</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name='pregunta8-5'
                                        wire:model='form.armados' value="0">
                                    <label class="form-check-label">
                                        NO
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name='pregunta8-5'
                                        wire:model='form.armados' id="pregunta85" value="1">
                                    <label class="form-check-label">
                                        SI
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" id="corporación">Nombre de la corporación</label>
                                    <div class="">
                                        <input class="form-control" type="text"
                                            wire:model='form.corporacion_armados' id="pregunta85nombrecorporacion"
                                            style="text-transform:uppercase;">
                                    </div>
                                </div>
                                <x-input-error for="form.armados" />
                            </div>


                        </div>
                    </div>



                    <div class="form-group ml-3 col-md-6">
                        <label class="control-label">9. Las instalaciones del cliente cuentan con algún tipo de
                            alarma</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" wire:model='form.alarma'>
                            <label class="form-check-label">
                                Botón de pánico, incendio, sismo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" wire:model='form.alarma'>
                            <label class="form-check-label">
                                NINGUNA
                            </label>
                        </div>
                        <x-input-error for="form.alarma" />
                    </div>


                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label">10. El sistema de alarma transmite la señal a:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.tiposenial'
                                name="pregunta10" id="pregunta10" value="0" required>
                            <label class="form-check-label">
                                Seguridad pública
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.tiposenial'
                                name="pregunta10" id="pregunta10" value="1" required>
                            <label class="form-check-label">
                                C.E.R.I
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.tiposenial'
                                name="pregunta10" id="pregunta10" value="2" required>
                            <label class="form-check-label">
                                Central alarmas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model='form.tiposenial'
                                name="pregunta10" id="pregunta10otro" value="3" required>
                            <label class="form-check-label">
                                Otros
                            </label>
                            <div class="">
                                <input class="form-control" type="text" wire:model='form.otros_tiposenial'
                                    name="pregunta10-5" id="pregunta10-5" style="text-transform:uppercase;"
                                    value="">
                            </div>
                        </div>
                        <x-input-error for="form.tiposenial" />


                    </div>

                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label ">11. Tiempo de respuesta que tiene para atender el llamado de
                            alarma:</label>
                        <div class="">
                            <input class="form-control" type="number" style="text-transform:uppercase;"
                                wire:model='form.tipoderespuesta'>
                        </div>
                        <x-input-error for="form.tipoderespuesta" />
                    </div>
                    {{-- 12 --}}
                    <div class="form-group ml-5 col-md-5">
                        <label class="control-label">12. En caso de falla de energía eléctrica cuenta con:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0"
                                wire:model='form.tipodefalla'>
                            <label class="form-check-label">
                                Generador de luz de emergencia
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1"
                                wire:model='form.tipodefalla'>
                            <label class="form-check-label">
                                Lámparas de iluminación de emergencia
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2"
                                wire:model='form.tipodefalla'>
                            <label class="form-check-label">
                                Bloqueos de seguridad en puertas de área segura.
                            </label>
                        </div>
                        <x-input-error for="form.tipodefalla" />
                    </div>

                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label">13. Cuenta con cámaras de seguridad el establecimiento:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" wire:model='form.camaras'>
                            <label class="form-check-label">
                                CIRCUITO CERRADO DE TELEVISIÓN
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" wire:model='form.camaras'>
                            <label class="form-check-label">
                                VIDEOGRABACIÓN
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2" wire:model='form.camaras'>
                            <label class="form-check-label">
                                NINGUNA
                            </label>
                        </div>
                        <x-input-error for="form.camaras" />
                    </div>
                    <div class="form-group ml-5 col-md-5">
                        <label class="control-label">14. Cuenta con cofre de seguridad operado por su personal o en
                            renta
                            con PRO-BLM.</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" wire:model='form.cofre'>
                            <label class="form-check-label">
                                Propio
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" wire:model='form.cofre'>
                            <label class="form-check-label">
                                en renta
                            </label>
                        </div>
                        <x-input-error for="form.cofre" />

                    </div>


                    <div class="form-group ml-3 col-md-11">
                        <label class="control-label ">15. Mencione la siniestralidad (asaltos o intentos de asalto)
                            que
                            ha
                            sufrido el cliente, anotando fechas
                            aproximadas y en qué forma se realizaron</label>
                        <div class="">
                            <input class="form-control" type="text" wire:model='form.descripcion_asalto'
                                style="text-transform:uppercase;">
                        </div>
                        <x-input-error for="form.descripcion_asalto" />

                    </div>


                    <div class="form-group ml-3 col-md-11">
                        <label class="control-label col-sm-8">16. Indique el nombre del ejecutivo de ventas que
                            realizó
                            la
                            contratación del servicio:</label>
                        <div class="">
                            <input class="form-control" type="text" style="text-transform:uppercase;">
                        </div>
                    </div>

                    <div class="form-group ml-3 col-md-5">
                        <label class="control-label ">17. Evalúe la zona donde se encuentra ubicada la recolección
                            de
                            valores, de acuerdo a los siguientes parámetros:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0"
                                wire:model='form.tipodezona'>
                            <label class="form-check-label">
                                De riesgo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1"
                                wire:model='form.tipodezona'>
                            <label class="form-check-label">
                                Regular
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="2"
                                wire:model='form.tipodezona'>
                            <label class="form-check-label">
                                Segura
                            </label>
                        </div>
                        <x-input-error for="form.tipodezona" />

                    </div>


                    <div class="form-group ml-5 col-md-6">
                        <label class="control-label">18. Indique si es conveniente para SERVICIOS INTEGRADOS PRO-BLM
                            DE
                            MÉXICO S.A. DE C.V., la realización del servicio en mención.</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0"
                                wire:model='form.conviene'>
                            <label class="form-check-label">
                                No
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1"
                                wire:model='form.conviene'>
                            <label class="form-check-label">
                                Si
                            </label>
                        </div>
                        <x-input-error for="form.conviene" />

                    </div>


                    <div class="form-group ml-3 col-md-11">
                        <label class="control-label ">OBSERVACIONES: </label>
                        <div class="">
                            <input class="form-control" type="text" style="text-transform:uppercase;"
                                wire:model='form.observaciones' placeholder="opcional">
                        </div>
                    </div>

                    <x-adminlte-button class="btn-flat ml-3" wire:click='save_form' label="Guardar" theme="primary"
                        icon="fas fa-lg fa-save" />

                </div>
            </div>

            {{-- imagenes --}}
            <div class="tab-pane fade {{ $active_img }}" id="img" role="tabpanel"
                aria-labelledby="img-tab">
                <div class="row g-3">

                    {{-- fotos --}}
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Subir Imagen</th>
                                            <th>Vista previa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>FOTO FACHADA</td>
                                            <td>
                                                <input type="file" id="foto_fachada" style="display: none;"
                                                    wire:model='foto_fachada'>
                                                <button type="button" class="btn btn-primary"
                                                    wire:click='subirImagen'
                                                    onclick="document.getElementById('foto_fachada').click();">
                                                    @if ($foto_fachada == '')
                                                        Seleccionar Imagen
                                                    @else
                                                        Cambiar Imagen
                                                    @endif
                                                </button>
                                            </td>
                                            <td>
                                                <div wire:loading wire:target='foto_fachada'
                                                    class="alert alert-primary" role="alert">
                                                    <strong>Imagen cargando!</strong>
                                                    <span>En un momento se
                                                        vizualizara su imagen.</span>
                                                </div>
                                                @if ($foto_fachada)
                                                    <img class="img-thumbnail h-10 w-10"
                                                        src="{{ $foto_fachada->temporaryUrl() }}" />
                                                @endif

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>FOTO ACCESOS (PEATONAL Y VEHICULAR)</td>
                                            <td>
                                                <input type="file" id="foto_accesos" style="display: none;"
                                                    wire:model='foto_accesos'>
                                                <button type="button" class="btn btn-primary"
                                                    wire:click='subirImagen'
                                                    onclick="document.getElementById('foto_accesos').click();">
                                                    @if ($foto_accesos == '')
                                                        Seleccionar Imagen
                                                    @else
                                                        Cambiar Imagen
                                                    @endif
                                                </button>
                                            </td>
                                            <td>

                                                <div wire:loading wire:target='foto_accesos'
                                                    class="alert alert-primary" role="alert">
                                                    <strong>Imagen cargando!</strong>
                                                    <span>En un momento se
                                                        vizualizara su imagen.</span>
                                                </div>
                                                @if ($foto_accesos)
                                                    <img class="img-thumbnail h-10 w-10"
                                                        src="{{ $foto_accesos->temporaryUrl() }}" />
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>FOTO SISTEMAS DE SEGURIDAD</td>
                                            <td>
                                                <input type="file" id="foto_seguridad" wire:model='foto_seguridad'
                                                    style="display: none;">
                                                <button type="button" class="btn btn-primary"
                                                    wire:click='subirImagen'
                                                    onclick="document.getElementById('foto_seguridad').click();">
                                                    @if ($foto_seguridad == '')
                                                        Seleccionar Imagen
                                                    @else
                                                        Cambiar Imagen
                                                    @endif
                                                </button>
                                            </td>
                                            <td>

                                                <div wire:loading wire:target='foto_seguridad'
                                                    class="alert alert-primary" role="alert">
                                                    <strong>Imagen cargando!</strong>
                                                    <span>En un momento se
                                                        vizualizara su imagen.</span>
                                                </div>
                                                @if ($foto_seguridad)
                                                    <img class="img-thumbnail h-10 w-10"
                                                        src="{{ $foto_seguridad->temporaryUrl() }}" />
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>



    </x-adminlte-modal>

    {{-- vista reporte --}}
    <div wire:ignore class="modal fade" id="PDFModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Vista previa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="idframePDF" frameborder="0" scrolling="no" width="100%" height="600px"></iframe>
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


                Livewire.on('success', function(message) {
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: true,
                        timer: 2500
                    })
                    $('#modalRpt').modal('hide');
                });

                Livewire.on('error', function(message) {


                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                });

                Livewire.on('pdfGenerated', function(pdfBase64) {
                    $('#PDFModal').modal('show');
                    $('#idframePDF').attr('src', 'data:application/pdf;base64,' + pdfBase64[0]);
                });
            });


            $(document).ready(function() {
                //limpiar datos de los detalles
                $('#modalDetalles').on('hidden.bs.modal', function() {
                    @this.dispatch('limpiar');
                });
                $('#modalRpt').on('hidden.bs.modal', function() {
                    @this.dispatch('limpiar');
                });



                var pregunta1otro = $("#pregunta1otro");
                var pregunta1text = $("#pregunta1text");

                // Ocultar el campo de texto al cargar la página
                pregunta1text.hide();

                // Manejar el cambio en los radio buttons
                $("input[name='pregunta1']").on("change", function() {
                    if ($("#pregunta1otro").is(":checked")) {
                        pregunta1text.show(); // Mostrar el campo de texto si OTROS está marcado
                    } else {
                        pregunta1text.val('')
                            .hide(); // Borrar el contenido del campo y ocultarlo si OTROS no está marcado
                    }
                });

                // Manejar el cambio en el radio button OTROS
                pregunta1otro.on("change", function() {
                    if ($(this).is(":checked")) {
                        pregunta1text.show(); // Mostrar el campo de texto si OTROS está marcado
                    } else {
                        pregunta1text.val('')
                            .hide(); // Borrar el contenido del campo y ocultarlo si OTROS no está marcado
                    }
                });

                //pregunta 4
                var pregunta4otro = $("#pregunta4otro");
                var pregunta4text = $("#pregunta4text");

                // Ocultar el campo de texto al cargar la página
                pregunta4text.hide();

                // Manejar el cambio en los radio buttons
                $("input[name='pregunta4']").on("change", function() {
                    if ($("#pregunta4otro").is(":checked")) {
                        pregunta4text.show(); // Mostrar el campo de texto si Otros está marcado
                    } else {
                        pregunta4text.val('')
                            .hide(); // Borrar el contenido del campo y ocultarlo si Otros no está marcado
                    }
                });

                // Manejar el cambio en el radio button Otros
                pregunta4otro.on("change", function() {
                    if ($(this).is(":checked")) {
                        pregunta4text.show(); // Mostrar el campo de texto si Otros está marcado
                    } else {
                        pregunta4text.val('')
                            .hide(); // Borrar el contenido del campo y ocultarlo si Otros no está marcado
                    }
                });

                //pregunta 8

                var pregunta8cual_label = $("#pregunta8cual-label");
                var pregunta8otros = $("#pregunta8otros");
                var pregunta8cual = $("#pregunta8cual");

                // Ocultar el campo de texto al cargar la página
                pregunta8cual.hide();
                pregunta8cual_label.hide();
                // Manejar el cambio en los radio buttons
                $("input[name='pregunta8']").on("change", function() {
                    if ($("#pregunta8otros").is(":checked")) {
                        pregunta8cual.show(); // Mostrar el campo de texto si la opción Otros está seleccionada
                        pregunta8cual_label.show();
                    } else {
                        pregunta8cual.val('')
                            .hide(); // Ocultar el campo de texto si la opción Otros no está seleccionada
                        pregunta8cual_label.hide();
                    }
                });

                //armados 8.5
                var pregunta85nombrecorporacion = $("#pregunta85nombrecorporacion");
                var corporación = $("#corporación");
                // Ocultar el campo de texto al cargar la página
                pregunta85nombrecorporacion.hide();
                corporación.hide();
                // Manejar el cambio en los radio buttons
                $("#pregunta85, input[name='pregunta8-5']").on("change", function() {
                    if ($("#pregunta85").is(":checked")) {
                        pregunta85nombrecorporacion
                            .show(); // Mostrar el campo de texto si la opción "SI" está seleccionada
                        corporación.show();
                    } else {
                        pregunta85nombrecorporacion.val('')
                            .hide(); // Ocultar el campo de texto si la opción "NO" está seleccionada
                        corporación.hide();
                    }
                });

                //pregunta 10
                var pregunta10otro = $("#pregunta10otro");
                var pregunta10_5 = $("#pregunta10-5");

                // Ocultar el campo de texto al cargar la página
                pregunta10_5.hide();

                // Manejar el cambio en los radio buttons
                $("input[name='pregunta10']").on("change", function() {
                    if ($("#pregunta10otro").is(":checked")) {
                        pregunta10_5.show(); // Mostrar el campo de texto si la opción "Otros" está seleccionada
                    } else {
                        pregunta10_5
                            .hide(); // Ocultar el campo de texto si la opción "Otros" no está seleccionada
                    }
                });
            });
        </script>
    @endpush

</div>
