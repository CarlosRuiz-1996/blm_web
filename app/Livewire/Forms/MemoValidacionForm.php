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
        // return Memorandum::whereNotExists(function ($query) use ($area) {
        //     $query->select(DB::raw(1))
        //         ->from('memoranda_validacion')
        //         ->join('revisor_areas', 'memoranda_validacion.revisor_areas_id', '=', 'revisor_areas.id')
        //         ->whereRaw('memoranda_validacion.memoranda_id = memoranda.id')
        //         ->where('revisor_areas.id', $area);
        // })
        //     ->get();

        // return Memorandum::where(function ($query) {
        //     $query->whereRaw('8 != (select count(id) from memoranda_validacion mv where mv.memoranda_id = memoranda.id)');
        // })
        // ->whereExists(function ($query) {
        //     $query->select(DB::raw(1))
        //           ->from('memorandum_cotizacion')
        //           ->whereRaw('memorandum_cotizacion.memoranda_id = memoranda.id');
        // })
        // ->get();
        // return Memorandum::select('memoranda.*')
        //     ->join('memorandum_cotizacion', 'memorandum_cotizacion.memoranda_id', '=', 'memoranda.id')
        //     ->join('memoranda_validacion', 'memoranda_validacion.memoranda_id', '=', 'memoranda.id')
        //     ->join('revisor_areas', 'revisor_areas.id', '=', 'memoranda_validacion.revisor_areas_id')
        //     ->join('ctg_area', 'ctg_area.id', '=', 'revisor_areas.ctg_area_id')
        //     ->where('memoranda.status_memoranda', 1)
        //     ->where('ctg_area.id', $area)
        //     ->groupBy('memoranda.id')
        //     ->havingRaw('COUNT(memoranda_validacion.id) < 8')
        //     ->get();

        
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
        // return MemorandumValidacion::where('revisor_areas_id', $area)->get();
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

        try {
            DB::beginTransaction();
            $this->validate();


            $empleado_id = auth()->user()->empleado->id;

            if ($admin == 1) {


                $limit = MemorandumValidacion::where('memoranda_id', $memorandum_id)->count();
                $limit = 8 - $limit;
                $bandera = "";
                for ($i = 1; $i <= $limit; $i++) {
                    $revisor = RevisorArea::where('empleado_id', $empleado_id)
                        ->where('ctg_area_id', $i)->first();
                
                    if ($revisor) {
                        $existe = MemorandumValidacion::where('memoranda_id', $memorandum_id)
                            ->where('revisor_areas_id', $revisor->id)
                            ->exists();
                
                        if (!$existe) {
                            MemorandumValidacion::create([
                                'memoranda_id' => $memorandum_id,
                                'revisor_areas_id' => $revisor->id,
                                'status_validacion_memoranda' => $this->cumple
                            ]);
                            $bandera .= $revisor->id . '-/';
                        }
                    }
                }                
            } else {

                $revisor = RevisorArea::where('empleado_id', $empleado_id)
                    ->where('ctg_area_id', $area)->first();

                MemorandumValidacion::create([
                    'memoranda_id' => $memorandum_id,
                    'revisor_areas_id' => $revisor->id,
                    'status_validacion_memoranda' => $this->cumple
                ]);
            }
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            $this->validate();
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Info: ' . $e);
            return 0;
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
