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


    public $cliente_bancoServ_serach;
    public $papeleta_bancoServ_serach;
    public $fechaini_bancoServ_serach;
    public $fechafin_bancoServ_serach;
    public $tipoServ_bancoServ_serach;
    public $status_bancoServ_serach;
    public function getAllBancosServicios()
    {
        return BancosServicios::where(function ($query) {

            if ($this->papeleta_bancoServ_serach) {
                $query->where('papeleta', 'ILIKE', '%' . $this->papeleta_bancoServ_serach . '%');
            }
            // Rango de fechas
            if ($this->fechaini_bancoServ_serach && $this->fechafin_bancoServ_serach) {
                $query->whereBetween('fecha_entrega', [$this->fechaini_bancoServ_serach, $this->fechafin_bancoServ_serach]);
            } elseif ($this->fechaini_bancoServ_serach) {
                $query->where('fecha_entrega', '=', $this->fechaini_bancoServ_serach);
            } elseif ($this->fechafin_bancoServ_serach) {
                $query->where('fecha_entrega', '=', $this->fechafin_bancoServ_serach);
            }

            if ($this->tipoServ_bancoServ_serach) {

                $query->where('tipo_servicio', $this->tipoServ_bancoServ_serach);
            }
            if ($this->status_bancoServ_serach) {
                $query->where('status_bancos_servicios', $this->status_bancoServ_serach);
            }

            if ($this->cliente_bancoServ_serach) {
                $query->orWhereHas('servicio', function ($query2) {
                    $query2->whereHas('cliente', function ($query3) {
                        $query3->where('razon_social', 'ILIKE', '%' . $this->cliente_bancoServ_serach . '%');
                    });
                });
            }
        })->paginate(10);
    }

    public $fechaini_compra_search;
    public $fechafin_compra_search;

    public $banco_compra_search;
    public $monto_compra_search;
    public $status_compra_search;

    public function getAllComprasEfectivo()
    {
        return CompraEfectivo::where(function ($query) {
            if ($this->monto_compra_search) {
                $query->where('total', 'ILIKE', '%' . $this->monto_compra_search . '%');
            }
            if ($this->status_compra_search) {
                $query->where('status_compra_efectivos',  $this->status_compra_search);
            }
            // Rango de fechas
            if ($this->fechaini_compra_search && $this->fechafin_compra_search) {
                $query->whereBetween('fecha_compra', [$this->fechaini_compra_search, $this->fechafin_compra_search]);
            } elseif ($this->fechaini_compra_search) {
                $query->where('fecha_compra', '=', $this->fechaini_compra_search);
            } elseif ($this->fechafin_compra_search) {
                $query->where('fecha_compra', '=', $this->fechafin_compra_search);
            }
            if ($this->banco_compra_search) {
                $query->orWhereHas('consignatario', function ($query) {
                    $query->where('name', 'ILIKE', '%' . $this->banco_compra_search . '%');
                });
            }
        })
        ->orderBy('id','DESC')
            ->paginate(10);
    }
}
