<?php

namespace App\Livewire\Forms;

use App\Models\Memorandum;
use App\Models\MemorandumValidacion;
use App\Models\RevisorArea;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemoValidacionForm extends Form
{


    #[Validate('required', message: 'Debe seleccionar un dictamen')]
    public $cumple;

    public function getPendientes($area)
    {
        return Memorandum::whereNotExists(function ($query) use ($area) {
            $query->select(DB::raw(1))
                ->from('memoranda_validacion')
                ->join('revisor_areas', 'memoranda_validacion.revisor_areas_id', '=', 'revisor_areas.id')
                ->whereRaw('memoranda_validacion.memoranda_id = memoranda.id')
                ->where('revisor_areas.id', $area);
        })
            ->get();
    }

    public function getValidados($area)
    {
        return MemorandumValidacion::where('revisor_areas_id', $area)->get();
    }

    public function store($area, $memorandum_id)
    {

        $this->validate();

        if($area ==8){
            $area = 9;
        }
        $empleado_id = auth()->user()->empleado->id;

        $revisor = RevisorArea::where('user_id', $empleado_id)
            ->where('ctg_area_id', $area)->first();

            if (!$revisor) {
                return 0;
            } else {
                MemorandumValidacion::create([
                    'memoranda_id' => $memorandum_id,
                    'revisor_areas_id' => $revisor->id,
                    'status_validacion_memoranda' => $this->cumple
                ]);
                return 1;
            }

      
    }
}
