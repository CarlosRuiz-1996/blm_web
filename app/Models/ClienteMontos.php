<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteMontos extends Model
{
    use HasFactory;
    protected $table = "cliente_montos";
    protected $fillable=['cliente_id','monto_old','monto_in','monto_new','empleado_id','ctg_area_id','tipo'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function area()
    {
        return $this->belongsTo(Ctg_Area::class, 'ctg_area_id');
    }
}
