<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    protected $table = 'servicios';
    protected $fillable = [
        'precio_unitario',
        'cantidad',
        'subtotal',
        'ctg_precio_servicio_id',
        'ctg_servicios_id',
        'servicio_especial',
        'status_servicio',
        'kilometros',
        'kilometros_costo',
        'miles',
        'miles_costo',
        'servicio_foraneo',
        'gastos_operaciones',
        'iva',
        'cliente_id',
        'foraneo_inicio',
        'foraneo_destino',
        'montotransportar_foraneo'
    ];

    public function ctg_servicio()
    {
        return $this->belongsTo(CtgServicios::class, 'ctg_servicios_id');
    }

    public function cotizacion_servicio()
    {
        return $this->hasMany(CotizacionServicio::class, 'servicio_id');
    }

    public function sucursal_servicio()
    {
        return $this->hasMany(SucursalServicio::class, 'servicio_id');
    }
    public function sucursal()
    {
        return $this->hasOne(SucursalServicio::class, 'servicio_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function cli()
    {
        return $this->hasOne(Cliente::class, 'cliente_id');
    }

    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_servicios', 'servicio_id', 'ruta_id');
    }


    public function ruta_servicios()
    {
        return $this->hasMany(RutaServicio::class, 'servicio_id');
    }
    public function ruta_servicio()
    {
        return $this->hasOne(RutaServicio::class, 'servicio_id');
    }
    public function conceptosForaneos()
    {
        return $this->hasMany(servicios_conceptos_foraneos::class, 'servicio_id');
    }


    public function direccionCompleta()
    {
        // Accede a la relación y devuelve la dirección completa
        if ($this->sucursal && $this->sucursal->sucursal) {
            return $this->sucursal->sucursal->direccion. ', CP. '
            .$this->sucursal->sucursal->cp->cp .' '.$this->sucursal->sucursal->cp->estado->name;
        }

        // Si alguna relación no existe, devuelve un valor predeterminado o null
        return 'Dirección no disponible';
    }
}
