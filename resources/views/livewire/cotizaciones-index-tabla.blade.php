<div class="col-md-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <form wire:submit.prevent="BuscarCotizacion">
                <div class="row">

                    <div class="col-md-2">
                        <x-input-validado :readonly="false" label="Id:" placeholder="Ingrese el ID"
                            wire-model="idcotizacion" wire-attribute="idcotizacion" type="text" />
                    </div>
                    <div class="col-md-2">
                        <x-input-validado :readonly="false" label="Fecha Inicio:" placeholder="Ingrese fecha inicio"
                            wire-model="fechainicio" wire-attribute="fechainicio" type="date" />
                    </div>

                    <div class="col-md-2">
                        <x-input-validado :readonly="false" label="Fecha fin:" placeholder="Ingrese fecha fin"
                            wire-model="fechafin" wire-attribute="fechafin" type="date" />
                    </div>
                    <div class="col-md-3">
                        <x-input-validado :readonly="false" label="Nombre cliente:" placeholder="Ingrese nombre cliente"
                            wire-model="nombrecliente" wire-attribute="nombrecliente" type="text" />
                    </div>

                    <div class="col-md-3 mt-2">
                        <div class="form-group mt-4">
                            <button class="btn btn-info btn-block" type="submit">Buscar</button>
                        </div>
                    </div>
                </div>

        </div>
        </form>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Razon Social</th>
                            <th>Estatus</th>
                            <th>Total</th>
                            <th>Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->fecha }}</td>
                                <td><a
                                        href="{{ route('cliente.detalles', $item->cliente_id) }}">{{ $item->razon_social }}</a>
                                </td>
                                <td>{{ $item->status_cotizacion }}</td>
                                <td>{{ $item->total }}</td>
                                <td><a href="{{ route('cotizacion.pdf', $item->id) }}" class="btn text-danger" target="_blank">
                                        <i class="fas fa-file-pdf"></i> 
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (optional($data)->isNotEmpty())
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
            @if (!optional($data)->isNotEmpty())
                <h4 class="text-center">Sin datos para mostrar</h4>
            @endif

        </div>
    </div>
</div>
