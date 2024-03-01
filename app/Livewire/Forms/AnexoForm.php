<?php

namespace App\Livewire\Forms;

use App\Models\Sucursal;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\DB;
use Livewire\Form;
use Illuminate\Support\Facades\Session;

class AnexoForm extends Form
{
    //datos generales de la sucursal
    public $sucursal;
    public $correo;
    public $phone;
    public $contacto;
    public $cargo;
    public $fecha_inicio_servicio;
    public $fecha_evaluacion;
    public $cliente_id;

    //direccion sucursal
    public $estado;
    public $municipio;
    public $colonias;
    public $cp;
    public $ctg_cp_id;
    public $cp_invalido = "";
    public $referencias;
    public $direccion;

    //servicio-sucursal
    public $sucursal_id;
    public $servicio_id;

    public function validarCp()
    {

        $codigo = DB::select("
                SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
                FROM ctg_cp cp 
                LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
                LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
                WHERE cp LIKE CONCAT('%', " . $this->cp . " , '%')
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

    public function store_sucursal()
    {

        Sucursal::create($this->only([
            'cliente_id', 'ctg_cp_id', 'direccion', 'referencias',
            'sucursal', 'contacto', 'cargo', 'correo', 'phone', 'fecha_evaluacion', 'fecha_inicio_servicio'
        ]));
        $this->reset();
    }

    public function getAllServicios($id)
    {
            
    }

    public function getAllSucursal()
    {

        return Sucursal::all();
    }


    public function store()
    {
        //guardar productos y comidas para el cliente
        $servcios = Session::get('servicio-sucursal', []);

        if (count($servcios)) {
            foreach ($servcios as $servicio) {


                SucursalServicio::create([
                    'servicio_id' => $servicio['servicio_id'],
                    'sucursal_id' => $servicio['sucursal_id'],   
                ]);
            }
        }

        $this->reset();
    }
}
