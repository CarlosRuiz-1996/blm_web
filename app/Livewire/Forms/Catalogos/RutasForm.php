<?php

namespace App\Livewire\Forms\catalogos;

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

    public function getAllRutasEstados()
    {
        return CtgRutasEstado::all();
    }
    public function getAllRutasRiesgos()
    {
        return CtgRutasRiesgo::all();
    }
    public function getAllRutasDias()
    {
        return CtgRutaDias::all();
    }
    public function getAllRutas()
    {
        return CtgRutas::all();
    }
    public function store($op)
    {
        $this->validate();


        $this->name = strtoupper($this->name);

        if ($op == 1) {
            CtgRutasEstado::create($this->only(['name']));
        } elseif ($op == 2) {
            CtgRutas::create($this->only(['name']));
        }elseif ($op == 3) {
            CtgRutasRiesgo::create($this->only(['name']));
        }
        elseif ($op == 4) {
            CtgRutaDias::create($this->only(['name']));
        }
        $this->reset();
    }


    public function update($ctg)
    {
        $this->validate();

        $ctg->update([
            'name' => strtoupper($this->name),
        ]);
        $this->reset();
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
        } else if ($op == 2){
            $ctg->update([
                'status_ctg_ruta' => 0,
            ]);
        }else if ($op == 3){
            $ctg->update([
                'status_ctg_rutas_riesgos' => 0,
            ]);
        }
        else if ($op == 4){
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
        } else if ($op == 2){
            $ctg->update([
                'status_ctg_ruta' => 1,
            ]);
        } else if ($op == 3){
            $ctg->update([
                'status_ctg_rutas_riesgos' => 1,
            ]);
        }
        else if ($op == 4){
            $ctg->update([
                'status_ctg_ruta_dias' => 1,
            ]);
        }
        $this->reset();
    }
}
