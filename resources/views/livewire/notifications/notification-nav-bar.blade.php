<div class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-bell"></i>
        <span class="badge badge-danger navbar-badge">
            {{ $totalNotifications }}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-header">{{ $totalNotifications }} nuevas notificaciones</div>
        @foreach ($notificationes as $notification)
            <a href="#" class="dropdown-item">
                <div class="media">
                    <div class="media-body">
                        <p class="text-sm">{{ $notification->message }}</p>
                        <p class="text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </a>
        @endforeach
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
    </div>
</div>
