<div>
    <div class="nav-item dropdown" >
        <a class="nav-link" data-toggle="dropdown" href="#">

            @if ($totalNotifications)
                <i class="fas fa-bell"></i>
            @else
                <i class="fa fa-bell-slash" aria-hidden="true"></i>
            @endif
            <span class="badge badge-danger navbar-badge">
                {{ $totalNotifications }}
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            @if ($totalNotifications)
                <div class="dropdown-header">{{ $totalNotifications }} nuevas notificaciones</div>
            @else
                <div class="dropdown-header"> Sin notificaciones</div>
            @endif
            @foreach ($notificationes as $notification)
                <a wire:click="$dispatch('confirm', ['{{ $notification }}'])" class="dropdown-item">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-sm">{{ $notification->message }}</p>
                            <p class="text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
            {{-- <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a> --}}
        </div>






    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('confirm', ([notification]) => {
                    const notif = JSON.parse(notification);

                    Swal.fire({
                        title: '¡Confirmación!',
                        text: notif.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('validar-10m', {
                                respuesta: 1,
                                notification: notif.id
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            @this.dispatch('validar-10m', {
                                respuesta: 0,
                                notification: notif.id
                            });
                        }
                    });
                });

                Livewire.on('success-firma10', function([res]) {

                    Swal.fire({
                        icon: res[1],
                        title: res[0],
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });
        </script>
    @endpush

</div>
