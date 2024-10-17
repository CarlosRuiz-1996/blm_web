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
    public $papeleta;
    public $monto = 0;
    public $servicio;
    public $morralla =false;
    public $billetes =false;
    public $from_change;
    public $cambios = [];


    public function getDenominaciones()
    {
        $consulta= $consulta=ctgDenominacion::orderBy('id', 'DESC')->get();
        if($this->morralla && $this->billetes ){
        $consulta=ctgDenominacion::orderBy('id', 'DESC')->get();
        }elseif(!$this->morralla && $this->billetes){
            $consulta=ctgDenominacion::where('ctg_tipo_moneda_id',2)->orderBy('id', 'DESC')->get();
        }elseif($this->morralla && !$this->billetes){
            $consulta=ctgDenominacion::where('ctg_tipo_moneda_id',3)->orderBy('id', 'DESC')->get();
        }
        return  $consulta;
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
