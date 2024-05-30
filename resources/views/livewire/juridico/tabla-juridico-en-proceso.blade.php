<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header" >
                    <form>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputId">Id</label>
                                    <input type="text" class="form-control" id="inputId" placeholder="Ingresa el Id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="razonsocial">Razon Social</label>
                                    <input type="text" class="form-control" id="razonsocial" placeholder="Ingrese razon social">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputFechaInicio">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputFechafin">Fecha Fin</label>
                                    <input type="date" class="form-control" id="inputFechafin" placeholder="Ingresa Fecha fin">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-primary">
                                <tr>
                                    <th class="col-md-2 text-center">No.</th>
                                    <th class="col-md-4 text-center">Razon Social</th>
                                    <th class="col-md-4 text-center">Fecha de Solicitud</th>
                                    <th class="col-md-2 text-center">Validación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listSolicitudes as $item)
                                    <tr>
                                        <td class="col-md-2 text-center">{{ $item->expediente_digital_id }}</td>
                                        <td class="col-md-4 text-center">{{ $item->razon_social }}</td>
                                        <td class="col-md-4 text-center">{{ $item->fecha_solicitud }}</td>
                                        <td class="col-md-2 text-center">
                                            @if(($item->status_juridico == 2 && $item->documentos_count == $item->ctg_doc_total) && ($item->ctg_docbene_total==$item->documentosbene_count || $item->documentosbene_count==0 ))
                                            <a wire:click="$dispatch('crearvalidacion',{{$item->cliente_id}})" class="btn btn-primary">Continuar Llenando</a>
                                            @else
                                            <h6 class="text-warning">Faltan documentos</h6>
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
    @push('js')
    <script>
        Livewire.on('crearvalidacion', id =>  {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Continuara la validacion de documentos!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, continuar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Obtener el ID del ítem desde id
                    var itemId = id;
                    console.log(itemId);
                    // Construir la URL de redireccionamiento con el ID del ítem
                    var redirectUrl = "{{ route('juridico.validajuridico', ':itemId') }}";
                    redirectUrl = redirectUrl.replace(':itemId', itemId);

                    // Redireccionar a la URL construida
                    window.location.href = redirectUrl;
                }
            });
        });
    </script>
     @endpush
</div>
