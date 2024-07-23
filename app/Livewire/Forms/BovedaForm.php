<?php

namespace App\Livewire\Forms;

use App\Models\CambioEfectivo;
use App\Models\CambioEfectivoDenominaciones;
use App\Models\ctgDenominacion;
use App\Models\ctgTipoMoneda;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Auth;

class BovedaForm extends Form
{
    public $folio;
    public $monto = 0;
    public $servicio;

    public $from_change;
    public $cambios = [];


    public function getDenominaciones()
    {
        return ctgDenominacion::orderBy('id', 'DESC')->get();
    }


    public function getCambios(){
        return CambioEfectivo::orderBy('id', 'DESC')->paginate(10);
    }
    public function saveCambioEfectivo()
    {

        $cambio_efectivo = CambioEfectivo::create([
            'monto' => $this->monto,
            'empleado_boveda_id' => Auth::user()->empleado->id,
            'from_change' => $this->from_change
        ]);
        foreach ($this->cambios as $index => $cambio) {
            CambioEfectivoDenominaciones::create([
                'monto' => $cambio['monto'],
                'ctg_denominacion_id' => $cambio['denominacion'],
                'cambio_efectivo_id' => $cambio_efectivo->id
            ]);
        }
    }
}
