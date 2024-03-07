<?php

namespace App\Livewire\Forms;

use App\Models\Cliente;
use App\Models\Ctg_Cp;
use App\Models\Ctg_Tipo_Cliente;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ClienteActivoForm extends Form
{

    public $cliente;
    public $estado;
    public $municipio;
    public $colonias;
    public $cp;
    public $ctg_cp_id;
    public $cp_invalido = "";
    public $direccion;
    public $razon_social;
    public $rfc_cliente;
    public $ctg_tipo_cliente_id=0;
    public $phone;
    public $puesto;
    public $email;
    public $name;
    public $paterno;
    public $materno;

    public $password;
    public $user_id;
    protected $rules = [
        'name' => 'required',
        'paterno' => 'required',
        'materno' => 'required',
        'razon_social' => 'required',
        'rfc_cliente' => 'required',
        'ctg_tipo_cliente_id' => 'required|not_in:0',
        'phone' => 'required|max:10|min:8',
        'puesto' => 'required',
        'email' => 'required|email',
        'cp' => 'required|max:5',
        'direccion' => 'required',
        'ctg_cp_id' => 'required',

    ];

    protected $messages = [
        'rfc_cliente.required' => 'El campo rfc es obligatorio.',
        'ctg_tipo_cliente_id.required' => 'El campo tipo de cliente es obligatorio.',
        'phone.required' => 'El campo teléfono es obligatorio.',
        'email.required' => 'El campo correo es obligatorio.',
        'direccion.required' => 'El campo calle y numero es obligatorio.',
        'name.required' => 'El campo nombre es obligatorio.',
        'paterno.required' => 'El campo apellido paterno es obligatorio.',
        'materno.required' => 'El campo apellido materno es obligatorio.',
        'ctg_cp_id'=>'El campo colonia es obligatorio.'
    ];
    public function ctg_tipo_cliente()
    {
        return Ctg_Tipo_Cliente::all();
    }

    public function validarCp()
    {
            $codigo = DB::select("
                SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
                FROM ctg_cp cp 
                LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
                LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
                WHERE cp LIKE CONCAT('%', ".$this->cp." , '%')
            ");
        if ($codigo) {
            $this->municipio = $codigo[0]->municipio;
            $this->estado = $codigo[0]->name;
            $this->colonias = $codigo;
            $this->cp_invalido = "";
        } else {
            $this->cp_invalido = "Codigo postal no valido";
        }
    }
    public function store(){


        $this->validate();//validacion

        
        $this->password =  bcrypt(strtolower($this->rfc_cliente));//contraseña sera el rfc en minusculas

        // ingreso en la tabla usuarios
        $user = User::create($this->only(['name','paterno','materno' ,'email', 'password']));
        $user->roles()->sync(4); //asigno rol 4 que sera de cliente.

        $this->user_id = $user->id;//usuario_id para relacionar con la tabla cliente
        //creo cliente
        Cliente::create($this->only([ 'user_id','puesto', 'direccion', 'ctg_cp_id', 'razon_social', 'rfc_cliente', 
        'phone', 'ctg_tipo_cliente_id']));


        $this->reset();
    }

    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;
        $this->razon_social = $cliente->razon_social;
        $this->rfc_cliente = $cliente->rfc_cliente;
        $this->ctg_tipo_cliente_id = $cliente->ctg_tipo_cliente_id;
        $this->phone = $cliente->phone;
        $this->phone = $cliente->phone;
        $this->email = $cliente->user->email;
        $this->name = $cliente->user->name;
        $this->paterno = $cliente->user->paterno;
        $this->materno = $cliente->user->materno;
        $this->puesto = $cliente->puesto;

        $this->cp = $cliente->cp->cp;
        $this->ctg_cp_id = $cliente->cp->id;
        $this->municipio = $cliente->cp->municipio->municipio;
        $this->estado = $cliente->cp->estado->name;
        $this->direccion = $cliente->direccion;
        // 
        $this->cp_invalido = "";
        $codigo = Ctg_Cp::where('cp','like','%'.$this->cp.'%')->get();

        $this->colonias = $codigo;

    }

    public function updated(){


        $this->validate();//validacion
        $this->password =  bcrypt(strtolower($this->rfc_cliente));//contraseña sera el rfc en minusculas
        $this->cliente->user->update($this->only(['name','paterno','materno' ,'email', 'password']));
        
        $this->cliente->update($this->only([ 'puesto', 'direccion', 'ctg_cp_id', 'razon_social', 'rfc_cliente', 
        'phone', 'ctg_tipo_cliente_id']));


        // $this->reset();
    }

}
