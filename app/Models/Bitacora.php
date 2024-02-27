<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $table = 'bitacoras';
    protected $fillable = [
        'accion','new','old','user_id','user_ip'
    ];

    public function ctg_accion()
    {
        return $this->belongsTo(BitacoraAccion::class, 'accion');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
