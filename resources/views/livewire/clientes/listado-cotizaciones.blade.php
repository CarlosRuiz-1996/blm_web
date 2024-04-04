<div class="col-md-12">
    <div class="card card-outline card-info">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Estatus</th>
                            <th>Total</th>
                            <th>Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cotizaciones as $cotizacion)
                            <tr>
                                <td>{{ $cotizacion->id }}</td>
                                <td>{{ $cotizacion->created_at }}</td>

                                <td>{{ $cotizacion->status_cotizacion }}</td>
                                <td>{{ $cotizacion->total }}</td>
                                <td><a href="{{ route('cotizacion.pdf', $cotizacion->id) }}" class="btn text-danger"
                                        target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (optional($cotizaciones)->isNotEmpty())
                    <div class="d-flex justify-content-center">
                        {{ $cotizaciones->links() }}
                    </div>
                @endif
            </div>
            @if (!optional($cotizaciones)->isNotEmpty())
                <h4 class="text-center">Sin datos para mostrar</h4>
            @endif

        </div>
    </div>
</div>
