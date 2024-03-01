<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'puesto',
        'direccion',
        'ctg_cp_id',
        'razon_social',
        'rfc_cliente',
        'phone',
        'resguardo',
        'status_cliente',
        'ctg_tipo_cliente_id',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cp()
    {
        return $this->belongsTo(Ctg_Cp::class, 'ctg_cp_id');
    }

    public function tipo_cliente()
    {
        return $this->belongsTo(Ctg_Tipo_Cliente::class, 'ctg_tipo_cliente_id');
    }
}
