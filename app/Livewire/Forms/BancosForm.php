<?php

namespace App\Livewire\Forms;

use App\Models\BancosServicios;
use App\Models\Cliente;
use App\Models\ClienteMontos;
use App\Models\CompraEfectivo;
use App\Models\CtgConsignatario;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BancosForm extends Form
{
    //
    public $cliente;
    public $actual_monto;
    public $nuevo_monto;

    public $ingresa_monto;
    protected $rules = [
        'ingresa_monto' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'ingresa_monto.required' => 'El monto es obligatorio',
        'ingresa_monto.numeric' => 'El monto debe ser un número',
        'ingresa_monto.min' => 'El monto debe ser mayor que 0',
    ];


    public $searchCliente;
    public function getAllClientes()
    {
        return Cliente::where('status_cliente', 1)
            ->where(function ($query) {
                $query->orWhere('razon_social', 'ilike', '%' . $this->searchCliente . '%')
                    ->orWhere('rfc_cliente', 'ilike', '%' . $this->searchCliente . '%')
                ;
            })
            ->paginate(10);
    }

    public function getCountResguadoClientes()
    {
        return Cliente::where('status_cliente', 1)->sum('resguardo');
    }

    public function addMonto()
    {

        $this->validate();

        try {
            DB::beginTransaction();

            $this->cliente->resguardo = $this->nuevo_monto;
            $this->cliente->save();

            ClienteMontos::create([
                'cliente_id' => $this->cliente->id,
                'monto_old' => $this->actual_monto,
                'monto_in' => $this->ingresa_monto,
                'monto_new' => $this->nuevo_monto,
                'empleado_id' => Auth::user()->empleado->id,
                'ctg_area_id' => Auth::user()->empleado->ctg_area_id
            ]);
            DB::commit();
            return 1;
        } catch (\Exception $e) {

            DB::rollBack();
            return 0;
        }
    }

    public $searchClienteActivo;
    public function getAllClientesActivo()
    {
        return Cliente::where('status_cliente', 1)
            ->where(function ($query) {
                $query->orWhere('razon_social', 'ilike', '%' . $this->searchClienteActivo . '%')
                    ->orWhere('rfc_cliente', 'ilike', '%' . $this->searchClienteActivo . '%')
                ;
            })
            ->get();
    }

    public function getAllConsignatorio()
    {
        return CtgConsignatario::all();
    }


    public function getAllBancosServicios(){
        return BancosServicios::paginate(10);
    }
    public function getAllComprasEfectivo(){
        return CompraEfectivo::paginate(10);
    }
}
