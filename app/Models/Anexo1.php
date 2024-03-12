<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo1 extends Model
{
    use HasFactory;
    protected $table = 'anexo1';
    protected $fillable = [
        'cliente_id', 'status_anexo', 'cotizacion_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function sucursal_sercio()
    {
        return $this->hasMany(SucursalServicio::class, 'anexo1_id');
    }

    
    public function factibilidad()
    {
        return $this->hasMany(Factibilidad::class, 'anexo1_id');
    }
}
