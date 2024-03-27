<?php

namespace App\Livewire\Forms\Catalogos;

use App\Models\CtgVehiculos;
use App\Models\CtgVehiculosMarca;
use App\Models\CtgVehiculosModelo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VehiculosForm extends Form
{
    public $name;

    public $descripcion, $serie, $anio, $ctg_vehiculo_marca_id, $ctg_vehiculo_modelo_id, $placas, $status_ctg_vehiculos;
    protected $rules = [
        'descripcion' => 'required',
        'serie' => 'required',
        // 'anio' => 'required', 
        'ctg_vehiculo_marca_id' => 'required',
        'ctg_vehiculo_modelo_id' => 'required',
        'placas' => 'required',
    ];
    public function getAllVehiculos()
    {
        return CtgVehiculos::all();
    }
    public function getAllMarcas()
    {
        return CtgVehiculosMarca::all();
    }
    public function getAllModelos()
    {
        return CtgVehiculosModelo::all();
    }
    public function getAllModelosByMarca()
    {
        return CtgVehiculosModelo::where('ctg_vehiculo_marca_id',$this->ctg_vehiculo_marca_id)->get();
    }
    public function store($op)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'ctg_vehiculo_marca_id' => $op == 1 ? '' : 'required'
        ], [
            'name.required' => 'Debe de ingresar el nombre',
            'ctg_vehiculo_marca_id.required' => 'Debe de ingresar la marca'
        ]);

        $this->name = strtoupper($this->name);

        if ($op == 1) {
            CtgVehiculosMarca::create($this->only(['name']));
        } elseif ($op == 2) {
            CtgVehiculosModelo::create($this->only(['name', 'ctg_vehiculo_marca_id']));
        }
        $this->reset();
    }


    public function update($ctg, $op)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'ctg_vehiculo_marca_id' => $op == 1 ? '' : 'required'
        ], [
            'name.required' => 'Debe de ingresar el nombre',
            'ctg_vehiculo_marca_id.required' => 'Debe de ingresar la marca'
        ]);

        $ctg->name = strtoupper($this->name);
        if ($op == 2) {
            $ctg->ctg_vehiculo_marca_id = $this->ctg_vehiculo_marca_id;
        }
        $ctg->save();

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
                'status_ctg_vehiculos_marcas' => 0,
            ]);
        } else {
            $ctg->update([
                'status_ctg_vehiculos_modelos' => 0,
            ]);
        }
        $this->reset();
    }

    public function reactivar($ctg, $op)
    {
        if ($op == 1) {
            $ctg->update([
                'status_ctg_vehiculos_marcas' => 1,
            ]);
        } else {
            $ctg->update([
                'status_ctg_vehiculos_modelos' => 1,
            ]);
        }
        $this->reset();
    }


    public function storeVehiculo()
    {
        try {

            $this->validate();
            CtgVehiculos::create($this->only(['descripcion', 'serie', 'anio', 'ctg_vehiculo_modelo_id', 'placas']));
            $this->reset();
            return 1;
        } catch (\Exception $e) {

            return 0;
        }
    }


    public function updateVehiculo(CtgVehiculos $vehiculo)
    {
        try {

            $this->validate();
            $vehiculo->update($this->only(['descripcion', 'serie', 'anio', 'ctg_vehiculo_modelo_id',  'placas']));
            $this->reset();
            return 1;
        } catch (\Exception $e) {

            return 0;
        }
    }


    public function bajaVehiculo(CtgVehiculos $vehiculo)
    {

        $vehiculo->update([
            'status_ctg_vehiculos' => 0,
        ]);
        $this->reset();
    }

    public function reactivarVehiculo(CtgVehiculos $vehiculo)
    {

        $vehiculo->update([
            'status_ctg_vehiculos' => 1,
        ]);
        $this->reset();
    }

    public function deleteVehiculo($vehiculo)
    {

        try {
            DB::beginTransaction();
            $vehiculo->delete();
            DB::commit();
            $this->reset();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->reset();
            return 0;
        }
    }
}
