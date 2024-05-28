<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table="notifications";
    protected $fillable=['empleado_id_send', 'ctg_area_id', 'message', 'status_notificacion','tipo','ruta_firma_id'];



    public function firma(){
        return $this->belongsTo(RutaFirma10M::class, 'ruta_firma_id');
    }
}
