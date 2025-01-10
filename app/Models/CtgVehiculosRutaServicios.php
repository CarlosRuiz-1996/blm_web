<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtgVehiculosRutaServicios extends Model
{
    use HasFactory;

    protected $table = 'ctg_vehiculos_ruta_servicios';

    protected $fillable = [
        'ctg_vehiculo_id',
        'ruta_servicio_id',
    ];

    /**
     * Relación con el modelo CtgVehiculos.
     *
     * @return BelongsTo
     */
    public function vehiculoRuta(): BelongsTo
    {
        return $this->belongsTo(CtgVehiculos::class, 'ctg_vehiculo_id');
    }

    /**
     * Relación con el modelo RutaServicios.
     *
     * @return BelongsTo
     */
    public function rutaServicioVehiculo(): BelongsTo
    {
        return $this->belongsTo(RutaServicio::class, 'ruta_servicio_id');
    }
}
