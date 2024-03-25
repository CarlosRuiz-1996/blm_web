<div>

    {{-- Example button to open modal --}}
    <button title="Agrega una nueva ruta al catalogo" class="btn btn-primary m-2" data-toggle="modal"
        data-target="#ctg-ruta">
        Nombre Ruta
        <i class="fa fa-plus" aria-hidden="true"></i>

    </button>

    {{-- Themed --}}
    <x-adminlte-modal wire:ignore.self id="ctg-ruta" title="Agrega el nombre de una nueva ruta" theme="info"
        icon="fas fa-plus" size='md' disable-animations>

        <x-input-validado label="Nombre de la ruta:" placeholder="ruta" wire-model="name" type="text" />

        <x-adminlte-button class="btn-flat" wire:click="$dispatch('confirm')" type="submit" label="Guardar"
            theme="info" icon="fas fa-lg fa-save" />

    </x-adminlte-modal>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', () => {

                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "El nombre de la ruta se guardara y podra usarlo en otras ocaciones",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('save-ctg-ruta');
                        }
                    })
                })

                Livewire.on('success', function([message]) {
                    Swal.fire({
                        icon: 'success',
                        title: message[0],
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var previousUrl = document.referrer;
                            window.location.href = previousUrl;
                        }
                    });
                });


                Livewire.on('error', function([message]) {
                    Swal.fire({
                        icon: 'error',
                        title: message[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });

                //mensaje para alert de agregar nueva ruta al cth
                Livewire.on('success-ctg', function(message) {
                    Swal.fire({
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $('#ctg-ruta').modal('hide');
                });

            });
        </script>
    @endpush
</div>
