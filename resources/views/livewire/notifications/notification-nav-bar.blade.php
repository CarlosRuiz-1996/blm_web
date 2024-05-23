<div>
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-bell"></i>
            <span class="badge badge-danger navbar-badge">
                {{count($notificationes)}}
                {{-- {{Auth::user()->empleado->id}} --}}
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @foreach ($notificationes as $notification)
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> {{ $notification->message }}
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
            @endforeach
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
        </div>
    </li>
</div>
