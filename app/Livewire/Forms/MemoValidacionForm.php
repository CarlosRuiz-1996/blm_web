<?php

namespace App\Livewire\Forms;

use App\Models\Memorandum;
use App\Models\MemorandumValidacion;
use App\Models\RevisorArea;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Log;

class MemoValidacionForm extends Form
{


    #[Validate('required', message: 'Debe seleccionar un dictamen')]
    public $cumple;

    public function getPendientes($area)
    {

        return Memorandum::select('memoranda.*')
            ->join('memorandum_cotizacion as mc', 'mc.memoranda_id', '=', 'memoranda.id')
            ->whereNotIn('memoranda.id', function ($query) use ($area) {
                $query->select('mv.memoranda_id')
                    ->from('memoranda_validacion as mv')
                    ->join('revisor_areas as ra', 'ra.id', '=', 'mv.revisor_areas_id')
                    ->join('ctg_area as ca', 'ca.id', '=', 'ra.ctg_area_id')
                    ->where('ca.id', $area);
            })
            ->groupBy('memoranda.id')
            ->get();
    }

    public function getValidados($area)
    {
        return Memorandum::select('memoranda.*')
            ->join('memorandum_cotizacion', 'memorandum_cotizacion.memoranda_id', '=', 'memoranda.id')
            ->join('memoranda_validacion', 'memoranda_validacion.memoranda_id', '=', 'memoranda.id')
            ->join('revisor_areas', 'revisor_areas.id', '=', 'memoranda_validacion.revisor_areas_id')
            ->join('ctg_area', 'ctg_area.id', '=', 'revisor_areas.ctg_area_id')
            ->where('memoranda.status_memoranda', 1)
            ->where('ctg_area.id', $area)
            ->groupBy('memoranda.id')
            ->havingRaw('COUNT(memoranda_validacion.id) < 8')
            ->paginate(10);
    }

    public function store($area, $memorandum_id, $admin)
    {
        $this->validate();
        try {
            DB::beginTransaction();
            


            $empleado_id = auth()->user()->empleado->id;
           
            if ($admin == 1) {
                dd('if');    
                for ($i = 1; $i <= 8; $i++) {
                    $revisor = RevisorArea::where('empleado_id', $empleado_id)
                        ->where('ctg_area_id', $i)->first();
                    $existe = MemorandumValidacion::where('memoranda_id', $memorandum_id)
                        ->whereHas('revisor_areas', function ($query) use ($i) {
                            $query->whereHas('area', function ($query2) use ($i) {
                                $query2->where('id', $i);
                            });
                        })
                        ->exists();
                    if (!$existe) {
                        MemorandumValidacion::create([
                            'memoranda_id' => $memorandum_id,
                            'revisor_areas_id' => $revisor->id,
                            'status_validacion_memoranda' => $this->cumple
                        ]);
                        
                    }
                
                }
            } else {
               
                $revisor = RevisorArea::where('empleado_id', $empleado_id)
                    ->where('ctg_area_id', $area)->first();
                if (!$revisor) {
                    throw new \Exception('No tienes permisos para validar el memorandum.');
                }


                $existe = MemorandumValidacion::where('memoranda_id', $memorandum_id)
                    ->whereHas('revisor_areas', function ($query) use ($area) {
                        $query->whereHas('area', function ($query2) use ($area) {
                            $query2->where('id', $area);
                        });
                    })
                    ->exists();
                if ($existe) {
                    throw new \Exception('Ya ha sido validado por esta area.');
                }

                MemorandumValidacion::create([
                    'memoranda_id' => $memorandum_id,
                    'revisor_areas_id' => $revisor->id,
                    'status_validacion_memoranda' => $this->cumple
                ]);
            }
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /*admin*/

    public function getPendientesAdmin()
    {
        // return Memorandum::whereNotExists(function ($query) {
        //     $query->select(DB::raw(1))
        //         ->from('memoranda_validacion')
        //         ->join('revisor_areas', 'memoranda_validacion.revisor_areas_id', '=', 'revisor_areas.id')
        //         ->whereRaw('memoranda_validacion.memoranda_id = memoranda.id')
        //         // ->where('revisor_areas.id', $area)
        //     ;
        // })
        //     ->get();
        return Memorandum::select('memoranda.*')
            ->join('memorandum_cotizacion', 'memorandum_cotizacion.memoranda_id', '=', 'memoranda.id')
            ->join('memoranda_validacion', 'memoranda_validacion.memoranda_id', '=', 'memoranda.id')
            ->join('revisor_areas', 'revisor_areas.id', '=', 'memoranda_validacion.revisor_areas_id')
            ->join('ctg_area', 'ctg_area.id', '=', 'revisor_areas.ctg_area_id')
            ->where('memoranda.status_memoranda', 1)
            ->groupBy('memoranda.id')
            ->havingRaw('COUNT(memoranda_validacion.id) < 8')
            ->get();
    }

    public function getValidadosAdmin()
    {
        return Memorandum::select('memoranda.*')
            ->join('memorandum_cotizacion', 'memorandum_cotizacion.memoranda_id', '=', 'memoranda.id')
            ->join('memoranda_validacion', 'memoranda_validacion.memoranda_id', '=', 'memoranda.id')
            ->join('revisor_areas', 'revisor_areas.id', '=', 'memoranda_validacion.revisor_areas_id')
            ->join('ctg_area', 'ctg_area.id', '=', 'revisor_areas.ctg_area_id')
            ->where('memoranda.status_memoranda', 2)
            ->groupBy('memoranda.id')
            ->havingRaw('COUNT(memoranda_validacion.id) = 8')
            ->paginate(10);
    }
}
