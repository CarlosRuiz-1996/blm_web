<?php

namespace App\Livewire\Forms\Catalogos;

use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgRutasEstado;
use App\Models\CtgRutasRiesgo;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RutasForm extends Form
{
    #[Validate('required', message: 'Debes ingresar el nombre')]
    public $name;

    public function getAllRutasEstados($search)
    {
        return CtgRutasEstado::where('name','ilike','%'.$search.'%')
        ->orderBy('id', 'DESC')->paginate(10);
        
    }
    public function getAllRutasRiesgos($search)
    {
        return CtgRutasRiesgo::where('name','ilike','%'.$search.'%')
        ->orderBy('id', 'DESC')->paginate(10);
    }
    public function getAllRutasDias($search)
    {
        return CtgRutaDias::where('name','ilike','%'.$search.'%')
        ->orderBy('id', 'DESC')->paginate(10);
    }
    public function getAllRutas($search)
    {
        return CtgRutas::where('name','ilike','%'.$search.'%')
        ->orderBy('id', 'DESC')->paginate(10);
    }
    public function store($op)
    {
        DB::beginTransaction();

        try {
            $this->validate();
    
            // $this->name = strtoupper($this->name);
            $this->name = mb_strtoupper($this->name, 'UTF-8');

            $modelMap = [
                1 => CtgRutasEstado::class,
                2 => CtgRutas::class,
                3 => CtgRutasRiesgo::class,
                4 => CtgRutaDias::class,
            ];
    
            $modelClass = $modelMap[$op];
    
            $modelClass::create($this->only(['name']));
            $this->reset();
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $modelError = [
                1 =>'El estado ya existe',
                2 => 'El nombre de la ruta ya existe',
                3 => 'El riesgo ya existe',
                4 => 'El dia ya existe',
            ];
    
            $msgError = $modelError[$op];
            $this->addError('name', $msgError);
            return 0;
        }
    }


    public function update($ctg)
    {
        $this->validate();



        DB::beginTransaction();

        try {
            $ctg->update([
                'name' => mb_strtoupper($this->name, 'UTF-8'),
            ]);
            $this->reset();
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('name', 'El dia ya existe');

            return 0;

        }
    }

    public function delete($ctg)
    {

        try {
            DB::beginTransaction();
            $ctg->delete();
            DB::commit();
            $this->reset();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->reset();
            return 0;
        }
    }


    public function baja($ctg, $op)
    {
        if ($op == 1) {
            $ctg->update([
                'status_ctg_rutas_estados' => 0,
            ]);
        } else if ($op == 2) {
            $ctg->update([
                'status_ctg_ruta' => 0,
            ]);
        } else if ($op == 3) {
            $ctg->update([
                'status_ctg_rutas_riesgos' => 0,
            ]);
        } else if ($op == 4) {
            $ctg->update([
                'status_ctg_ruta_dias' => 0,
            ]);
        }
        $this->reset();
    }

    public function reactivar($ctg, $op)
    {
        if ($op == 1) {
            $ctg->update([
                'status_ctg_rutas_estados' => 1,
            ]);
        } else if ($op == 2) {
            $ctg->update([
                'status_ctg_ruta' => 1,
            ]);
        } else if ($op == 3) {
            $ctg->update([
                'status_ctg_rutas_riesgos' => 1,
            ]);
        } else if ($op == 4) {
            $ctg->update([
                'status_ctg_ruta_dias' => 1,
            ]);
        }
        $this->reset();
    }
}
