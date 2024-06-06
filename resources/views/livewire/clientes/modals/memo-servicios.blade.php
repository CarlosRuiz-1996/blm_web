<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
{{-- elegir sucursal --}}
<div class="modal fade" wire:ignore.self id="modalMemo" tabindex="-1" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header bg-info">
            <h5 class="modal-title" id="exampleModalLabel">Complementos del servicio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
            <button type="button"  class="btn btn-primary" 
                >Terminar</button>
            <button type="button"  class="btn btn-secondary"
                data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
</div>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('open-memo', () => {
                    console.log('entra en memo')
                    $('#modalMemo').modal('show');
                })


            });

           
        </script>
    @endpush
</div>
