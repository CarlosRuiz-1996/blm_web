<div wire:init='loadDiferencias'>
    <div class="table-responsive">
        <table class="table">

         
            <!-- Encabezados de la tabla -->
            <thead class="table-info">
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>Cliente</th>
                    <th>Fecha_comprobante</th>
                    <th>Folio</th>
                    <th>Sello_seguridad</th>

                    <th>Importe_indicado</th>
                    <th>Importe_comprobado</th>
                  
                    <th>Estatus_incosistencia</th>
                    <th>PDF</th>


                </tr>
            </thead>
            <!-- Cuerpo de la tabla -->
            <tbody>
                @if (count($diferencias))
                    @foreach ($diferencias as $diferencia)
                        <tr>
                            {{-- <td>{{ $diferencia->id }}</td> --}}
                            <td>{{ $diferencia->cliente->razon_social }}</td>
                            <td>{{ $diferencia->fecha_comprobante }}</td>
                            <td>{{ $diferencia->folio }}</td>
                            <td>{{ $diferencia->sello_seguridad }}</td>

                            <td>{{ $diferencia->importe_indicado }}</td>
                            <td>{{ $diferencia->importe_comprobado }}</td>
                            <td>{{$diferencia->status_incosistencia ==1?'REVISIÓN':'RESUELTA'}}</td>
                        
                            <td>
                                <a href="{{ route('acta_diferencia.pdf', $diferencia) }}" target="_blank">
                                    <i title="Descargar Archivo" style="color: red;" class="fas fa-file-pdf fa-2x" aria-hidden="true"></i>
                                </a>
                            </td>

                            
                        </tr>
                    @endforeach
                @else
                    @if ($readyToLoad)
                        <tr>
                            <td colspan="8">No hay datos disponibles</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border" style="width: 5rem; height: 5rem; border-width: 0.5em;"
                                    role="status">
                                </div>
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
    <!-- Paginación -->
    @if ($diferencias && $diferencias->hasPages())
    <div class="col-md-12 text-center">
        {{ $diferencias->links('pagination::bootstrap-4') }}
    </div>
@endif
</div>
