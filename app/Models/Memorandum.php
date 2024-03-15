<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    use HasFactory;
    protected $table = 'memoranda';
    protected $fillable = [
        'grupo',
        'ctg_tipo_solicitud_id',
        'ctg_tipo_servicio_id',
        'observaciones',
        'status_memoranda',
        'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
