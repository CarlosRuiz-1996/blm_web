<div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h3 class="modal-title" id="exampleModalLongTitle">Núeva Cotización</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-center">Cliente Nuevo</h4>
                    <a href="{{ route('clientesactivos.CotizacionesNuevas') }}" class="btn btn-info btn-block ">Crear
                        cliente</a>
                    <hr class="m-4">
                    <h4 class="text-center">Cliente Existente</h4>

                    <div class="form-group">
                        <label for="nombrecliente">Nombre Cliente:</label>
                        <input type="text" class="form-control" id="nombrecliente"
                            placeholder="Ingrese nombre de cliente a buscar" wire:model.live="nombrecliente"
                            autocomplete="off" />
                        @if (count($sugerencias) > 0)
                            <div class="list-group mt-2">
                                @foreach ($sugerencias as $sugerencia)
                                    <div class="list-group-item"
                                        wire:click="seleccionarCliente('{{ $sugerencia->id }}', '{{ $sugerencia->name }} {{ $sugerencia->paterno }} {{ $sugerencia->materno }}')">
                                        {{ $sugerencia->name }} {{ $sugerencia->paterno }} {{ $sugerencia->materno }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <a class="btn btn-info btn-block"
                        href="{{ route('clientesactivos.cotizardenuevo', $idseleccionado) }}">Crear</a>
                </div>
            </div>
        </div>
    </div>
</div>
