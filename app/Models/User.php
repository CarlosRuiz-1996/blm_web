<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'paterno',
        'materno',
        'email',
        'password',
        'image_path',
        // 'ctg_area_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function adminlte_image()
    {
        //retorno una imagen
        return 'https://picsum.photos/200/300';
    }

    public function adminlte_desc()
    {
        //retorno una imagen

        return 'Administrador';
    }


    public function adminlte_profile_url()
    {
        //retorno una imagen

        return 'user/profile';
    }

    ///relacion con mi empleado
    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }
    ///relacion con mi cliente
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
    // bitacora
    public static function boot()
    {
        parent::boot();
        self::created(function ($user) {
            Bitacora::create([
                'accion' => 1,
                'user_id' => $user->id,
                'user_ip' => request()->ip(),
                'new' => json_encode($user->toJson()), // O cualquier otro formato que prefieras
                'fecha_registro' => now(),
            ]);
        });

        self::updated(function ($user) {
            Bitacora::create([
                'accion' => 2,
                'user_id' => $user->id,
                'user_ip' => request()->ip(),
                'new' => json_encode($user), // O cualquier otro formato que prefieras
                'old' => json_encode($user->getOriginal()), // Obtener los datos originales antes de la actualización
                'fecha_registro' => now(),
            ]);
        });

        self::deleted(function ($user) {
            Bitacora::create([
                'accion' => 3,
                'user_id' => $user->id,
                'user_ip' => request()->ip(),
                'old' => json_encode($user->toJson()), // O cualquier otro formato que prefieras
                'fecha_registro' => now(),
            ]);
        });
    }
}
