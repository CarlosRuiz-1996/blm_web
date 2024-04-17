<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicios_conceptos_foraneos extends Model
{
    use HasFactory;
    protected $table = 'servicio_concepto_foraneos';
    protected $fillable = [
        'concepto', 'costo', 'servicio_id'
    ];
}
