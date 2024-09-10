<div>
    <div class="nav-item dropdown">
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
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" wire:key="myUniqueKey">

            @if ($totalNotifications)
                <div class="dropdown-header bg-info">{{ $totalNotifications }} nuevas notificaciones</div>
            @else
                <div class="dropdown-header bg-info"> Sin notificaciones</div>
            @endif
            @foreach ($notificationes as $notification)
                <a href="javascript:void(0);"
                    @if ($notification->tipo == 2) wire:click="$dispatch('confirm', ['{{ $notification }}'])"
                    @elseif ($notification->tipo == 1)
                        wire:click="deleteNotification({{ $notification->id }})" 
                    @elseif ($notification->tipo == 3)
                    wire:click="$dispatch('banco-valida', ['{{ $notification }}'])" @endif
                    class="dropdown-item">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-xs"><strong>{{ $notification->message }}</strong></p>
                            <p class="text-muted text-xs text-right">{{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>

                <hr class="p-0 m-0">
            @endforeach
            {{-- <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a> --}}
        </div>






    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                var notif = null;
                @this.on('confirm', ([notification]) => {
                    var notif = JSON.parse(notification);
                    Swal.fire({
                        title: '¡Confirmación!',
                        text: notif.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, adelante!',
                        cancelButtonText: 'Negar'
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

                @this.on('banco-valida', ([notification]) => {
                    var notif = JSON.parse(notification);
                    Swal.fire({
                        title: '¡Confirmación!',
                        icon: "question",
                        text: notif.message,
                        input: "text",
                        inputAttributes: {
                            autocapitalize: "off",
                            placeholder: 'Ingresa contraseña'
                        },
                        showCancelButton: true,
                        confirmButtonText: "Confirmar",
                        showLoaderOnConfirm: true,

                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.dispatch('banco-validar', {
                                respuesta: 1,
                                notification: notif.id,
                                password: result.value
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            @this.dispatch('banco-validar', {
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

                Livewire.on('alerta',  function([res]){
                    Swal.fire({
                        icon: res[0], // 'success' o 'error'
                        title: 'Validación',
                        text:  res[1]
                    });
                });
            });
        </script>
    @endpush


</div>
