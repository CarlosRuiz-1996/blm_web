<?php

namespace App\Models;

use App\Livewire\Memorandum\MemorandumValidando;
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

    public function tipo_solicitud()
    {
        return $this->belongsTo(CtgTipoSolicitud::class, 'ctg_tipo_solicitud_id');
    }
    public function tipo_servicio()
    {
        return $this->belongsTo(CtgTipoServicio::class, 'ctg_tipo_servicio_id');
    }


    public function memo_servicio()
    {
        return $this->hasMany(MemorandumServicios::class, 'memoranda_id');
    }


    public function memo_cotizacion()
    {
        return $this->hasOne(MemorandumCotizacion::class, 'memoranda_id');
    }

    public function memo_validacion (){
        return $this->hasOne(MemorandumValidacion::class, 'memoranda_id');
    }
}
