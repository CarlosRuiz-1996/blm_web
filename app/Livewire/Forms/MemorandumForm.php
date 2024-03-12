<?php

namespace App\Livewire\Forms;

use App\Models\Anexo1;
use App\Models\CtgConsignatario;
use App\Models\CtgDiaEntrega;
use App\Models\CtgDiaServicio;
use App\Models\CtgHorarioEntrega;
use App\Models\CtgHorarioServicio;
use App\Models\CtgTipoServicio;
use App\Models\CtgTipoSolicitud;
use App\Models\Factibilidad;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemorandumForm extends Form
{
    public $razon_social;
    public $rfc_cliente;
    public $ejecutivo;
    public $fecha_solicitud;

    public $grupo;
    public $ctg_tipo_solicitud_id;
    public $ctg_tipo_servicio_id;


    //catalogos
    public function getAllTipoSolicitud()
    {
        return CtgTipoSolicitud::all();
    }

    public function getAllTipoServicio()
    {
        return CtgTipoServicio::all();
    }

    public function getAllHorarioEntega()
    {
        return CtgHorarioEntrega::all();
    }

    public function getAllDiaEntega()
    {
        return CtgDiaEntrega::all();
    }
    public function getAllHorarioServicio()
    {
        return CtgHorarioServicio::all();
    }

    public function getAllDiaServicio()
    {
        return CtgDiaServicio::all();
    }
    public function getAllConsignatorio()
    {
        return CtgConsignatario::all();
    }

    
    //tablas

    public function getFactibilidadAll()
    {
        return Factibilidad::where('status_factibilidad', '=', 1)->get();
    }

    public function getSucursales($id)
    {

        $sucursal_servicio = SucursalServicio::with('sucursal', 'servicio')
            ->where('anexo1_id', $id)
            ->get();

        $sucursales = [];
        foreach ($sucursal_servicio as $ss) {
            $sucursal = $ss->sucursal;
            $servicio = $ss->servicio;

            // Agregar la sucursal al arreglo de sucursales si aún no está presente
            if (!array_key_exists($sucursal->id, $sucursales)) {
                $sucursales[$sucursal->id] = [
                    'sucursal' => $sucursal,
                    'servicios' => []
                ];
            }

            // Agregar el servicio al arreglo de servicios de la sucursal
            $sucursales[$sucursal->id]['servicios'][] = $servicio;
        }

        return $sucursales;
    }
}
