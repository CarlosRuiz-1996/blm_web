<div>
    <div class="row">
        <div class="col-md-6">
            <h3 for="">Servicios</h3>
        </div>
        <div class="col-md-6">
            <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-primary">Agregar Servicios</button>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th>Servicio</th>
                            <th>Monto del servicio</th>
                            <th>Sucursal</th>
                            <th>Estatus</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->ctg_servicio->descripcion }}</td>
                                <td>${{ number_format($servicio->ruta_servicio->monto, 2, ',', '.')  }}</td>
                                <td>
                                    {{$servicio->sucursal->sucursal->sucursal}}
                                </td>
                                <td>
                                    {{$servicio->status_servicio !=0?`<i class="fa fa-circle"></i>`:'INACTIVO'}}
                                    @if($servicio->status_servicio !=0)
                                        <i class="fa fa-circle" style="color: green"></i>
                                    @else
                                    <i class="fa fa-circle" style="color: red"></i>
                                    @endif
                                </td>
                                <td>
                                    
                                    @if($servicio->status_servicio !=0)
                                    <button class="btn btn-danger" wire:click='updateServicio({{$servicio->id}},1)'>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-primary" wire:click='updateServicio({{$servicio->id}},2)'>
                                        Reactivar
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
