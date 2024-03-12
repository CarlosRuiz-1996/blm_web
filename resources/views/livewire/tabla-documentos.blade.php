<div>
    <h4 class="text-center">Documentos Cliente</h4>
    <hr>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-md-5 text-center">Documento</th>
                    <th class="col-md-4 text-center">Archivo Adjunto</th>
                    <th class="col-md-4 text-center">Vista Previa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documentos as $docu)
                    <tr>
                        <td class="col-md-5">{{ $docu->name }}</td>
                        <td class="col-md-4">
                            @php $documentoEncontrado = false; @endphp
                    @php $nombreDocumento = ''; @endphp
                    @foreach ($documentosexpediente as $doc)
                        @if ($doc->ctg_documentos_id == $docu->id)
                            {{ $doc->document_name }}
                            @php
                                $documentoEncontrado = true;
                                $nombreDocumento = $doc->document_name;
                            @endphp
                        @endif
                    @endforeach
                        </td>
                        <td class="col-md-2 text-center f-2">
                            @if ($documentoEncontrado)
                            <button data-toggle="modal" data-target="#modalpdf" wire:click="openModal('documentos/{{$rfc}}/{{$nombreDocumento}}')">
                                <i class="fas fa-eye text-success"></i>
                            </button>
                        @else
                            <!-- Manejo si no se encuentra el documento -->
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h4 class="text-center">Documentos Beneficiario(Opcional)</h4>
    <hr>
    <div class="table-responsive">
    <table class="table">
        <thead class="table-info">
            <tr>
                <th class="col-md-5 text-center">Documento</th>
                <th class="col-md-4 text-center">Archivo Adjunto</th>
                <th class="col-md-4 text-center">Vista Previa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documentos_beneficiarios as $doca)
            <tr>
                <td class="col-md-5">
                    {{ $doca->name }}
                </td>
                <td class="col-md-4">
                    @php $documentoEncontradobe = false; @endphp
                    @php $nombreDocumentoBene = ''; @endphp
                    @foreach ($documentosexpedienteBene as $documentobene)
                        @if ($documentobene->ctg_documentos_benf_id == $doca->id)
                            {{ $documentobene->document_name }}
                            @php
                                $documentoEncontradobe = true;
                                $nombreDocumentoBene = $documentobene->document_name;
                            @endphp
                        @endif
                    @endforeach
                </td>
                <td class="col-md-4 text-center f-2">
                    @if ($documentoEncontradobe)
                        <button data-toggle="modal" data-target="#modalpdf" wire:click="openModal('documentos/{{$rfc}}/beneficiario/{{$nombreDocumentoBene}}')">
                            <i class="fas fa-eye text-success"></i>
                        </button>
                    @else
                        <!-- Manejo si no se encuentra el documento -->
                    @endif
                </td>
            </tr>
        @endforeach
        
        </tbody>                                
    </table>
</div>
<x-modal-pdf title="PDF Modal" pdfUrl="{{ $pdfUrl }}" wire:ignore.self>
</x-modal-pdf>
</div>
