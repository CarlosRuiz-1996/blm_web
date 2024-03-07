<!-- modal.blade.php -->
<div class="modal fade" id="modalpdf" tabindex="-1" role="dialog" {{ $attributes }}>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if($pdfUrl)
                <iframe src="{{ asset('storage/' . $pdfUrl) }}" style="border: none; width: 100%; height: 500px;"></iframe>
                @else
                <H3 class="text-center">CARGANDO DOCUMENTO</H3>
                @endif
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
