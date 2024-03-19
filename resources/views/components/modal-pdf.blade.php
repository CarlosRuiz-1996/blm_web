<!-- modal.blade.php -->
<div class="modal fade" id="modalpdf" tabindex="-1" role="dialog" {{ $attributes }}>
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($pdfUrl)
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="{{ asset('storage/' . $pdfUrl) }}" style="border: none;"></iframe>
                </div>
                @else
                <div class="text-center">
                    <h3>CARGANDO DOCUMENTO</h3>
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

