<?php

namespace App\Livewire\Forms;

use App\Models\Cotizacion;
use App\Models\CotizacionServicio;
use App\Models\Servicios;
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
    public $direccion_completa;

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
    protected $rules = [

        'referencias' => 'required',
        'sucursal' => 'required',
        'contacto' => 'required',
        'cargo' => 'required',
        'fecha_evaluacion' => 'required',
        'phone' => 'required|max:10|min:8',
        'correo' => 'required|email',
        'direccion' => 'required',
        'ctg_cp_id' => 'required',
        'fecha_inicio_servicio' => 'required',
        'cp' => 'required|max:5',
        'sucursal_id' => 'required'

    ];
    public function store_sucursal()
    {
        $this->validate();

        Sucursal::create($this->only([
            'cliente_id', 'ctg_cp_id', 'direccion', 'referencias',
            'sucursal', 'contacto', 'cargo', 'correo', 'phone', 'fecha_evaluacion', 'fecha_inicio_servicio'
        ]));
        $this->reset([
            'ctg_cp_id', 'direccion', 'referencias',
            'sucursal', 'contacto', 'cargo', 'correo', 'phone', 'fecha_evaluacion', 'fecha_inicio_servicio', 'cp'
        ]);
    }

    public function getAllServicios($id)
    {
        // return Cotizacion::find($id);;
        return CotizacionServicio::where('cotizacion_id', '=', $id)->get();
    }

    public function getAllSucursal()
    {

        return Sucursal::where('cliente_id', '=', $this->cliente_id)->get();
    }


    public function getAllSucursalById($id)
    {

        $sucursal = Sucursal::findOrFail($id)->toArray();
        $sucursal = Sucursal::with('cp')->findOrFail($id)->toArray();

        $this->sucursal = $sucursal['sucursal'];
        $this->correo = $sucursal['correo'];
        $this->phone = $sucursal['phone'];
        $this->contacto = $sucursal['contacto'];
        $this->cargo = $sucursal['cargo'];

        $codigo = DB::select("
            SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
            FROM ctg_cp cp 
            LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
            LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
            WHERE cp LIKE CONCAT('%', " . $sucursal['cp']['cp'] . " , '%')
        ");

        $colonia='';
        foreach ($codigo as $c) {
            if ($sucursal['ctg_cp_id'] == $c->id) {
                $colonia = $c->colonia;
            }
        }

        $this->direccion_completa = 'Calle ' . $sucursal['direccion'] .', Colonia '.$colonia. ', '.$sucursal['cp']['cp'].' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function getSucursalName()
    {
        $this->validateOnly('sucursal_id');
        return Sucursal::find($this->sucursal_id);
    }

    //relacion de sucursales y servicios
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
