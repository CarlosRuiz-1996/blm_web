<?php

namespace App\Livewire\Forms;

use App\Models\Cliente;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BancosForm extends Form
{
    //
    public $cliente;
    public $actual_monto;
    public $nuevo_monto;
    public $ingresa_monto;
    public function getAllClientes()
    {
        return Cliente::where('status_cliente', 1)->paginate(10);
    }

    public function getCountResguadoClientes()
    {
        return Cliente::where('status_cliente', 1)->sum('resguardo');
    }
}
