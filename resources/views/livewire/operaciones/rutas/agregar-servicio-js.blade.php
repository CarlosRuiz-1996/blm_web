<div>
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-3">Servicios</h1>
        <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
            data-target="#servicios" wire:click='getServicios()'>
            Agregar Servicio
            <i class="fa fa-plus" aria-hidden="true"></i>

        </button>
    </div>


    {{-- Modal vehiculos --}}
    <x-adminlte-modal wire:ignore.self id="servicios" title="Agregar servicios a la ruta" theme="info"
        icon="fas fa-car" size='xl' disable-animations>



        <table class="table table-hover table-striped">
            <thead class="table-info">
                <th></th>
                <th>Servcicio</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Monto</th>

                <th>Papeleta</th>
                <th>Contenedor</th>

            </thead>
            <tbody>


                @foreach ($servicios as $servicio)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model='selectServicios.{{ $servicio->id }}'
                                onclick="Checheado({{ $servicio->id }})" id='check{{ $servicio->id }}' />
                        </td>
                        <td>{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                        <td>{{ $servicio->anexo->cliente->razon_social }}</td>
                        <td>{{ $servicio->sucursal->direccion .
                            ' ' .
                            $servicio->sucursal->cp->cp .
                            '' .
                            $servicio->sucursal->cp->estado->name }}
                        </td>
                        <td>
                            <input disabled id='monto{{ $servicio->id }}' type="text" class="form-control"
                                wire:model='montoArray.{{ $servicio->id }}'>
                            @error('montoArray.' . $servicio->id)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input disabled id='folio{{ $servicio->id }}' type="text" class="form-control"
                            >
                        </td>
                        <td>
                            <input disabled id='envase{{ $servicio->id }}' type="text" class="form-control">
                        </td>
                    </tr>
                @endforeach



            </tbody>
        </table>
        <button wire:click='addServicios()'>Guardar</button>
    </x-adminlte-modal>


    @push('js')
        <script>
            function Checheado(i) {
                var check = $("#check" + i);
                var monto = $("#monto" + i);
                var folio = $("#folio" + i);
                var envase = $("#envase" + i);


                if (check.prop("checked")) {
                    monto.prop("disabled", false);
                    folio.prop("disabled", false);
                    envase.prop("disabled", false);
                } else {
                    monto.prop("disabled", true);
                    folio.prop("disabled", true);
                    envase.prop("disabled", true);

                    monto.val('');
                    folio.val('');
                    envase.val('');
                }


            }
        </script>
    @endpush
</div>
