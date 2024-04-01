<?php

namespace App\Livewire\Forms;

use App\Models\Anexo1;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionServicio;
use App\Models\Servicios;
use App\Models\Sucursal;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public $status_sucursal;

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

    ];
    public function store_sucursal()
    {

        try {
            DB::beginTransaction();

            $this->validate();
            $this->status_sucursal = 0;

            Sucursal::create($this->only([
                'cliente_id', 'ctg_cp_id', 'direccion', 'referencias',
                'sucursal', 'contacto', 'cargo', 'correo', 'phone', 'fecha_evaluacion', 'fecha_inicio_servicio', 'status_sucursal'
            ]));
            $this->reset([
                'ctg_cp_id', 'direccion', 'referencias',
                'sucursal', 'contacto', 'cargo', 'correo', 'phone', 'fecha_evaluacion', 'fecha_inicio_servicio', 'cp', 'status_sucursal'
            ]);
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            $this->validate();
            DB::rollBack();
            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // Log::info('Info: ' . $e);
            return 0;
        }
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

        $colonia = '';
        foreach ($codigo as $c) {
            if ($sucursal['ctg_cp_id'] == $c->id) {
                $colonia = $c->colonia;
            }
        }

        $this->direccion_completa = 'Calle ' . $sucursal['direccion'] . ', Colonia ' . $colonia . ', ' . $sucursal['cp']['cp'] . ' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function getSucursalName()
    {
        // $this->validateOnly('sucursal_id');
        $this->validateOnly('sucursal_id', [
            'sucursal_id' => 'required|numeric',
        ], [
            'sucursal_id.required' => 'El campo de sucursal es obligatorio.',
        ]);
        return Sucursal::find($this->sucursal_id);
    }

    //guardar relacion de sucursales y servicios
    public function store(Cotizacion $cotizacion)
    {

        try {
            DB::beginTransaction();

            $anexo1 = Anexo1::create(['cliente_id' => $this->cliente_id, 'cotizacion_id' => $cotizacion->id]);
            //array donde se guarda la relacion de sucursales y servicios
            $servcios = Session::get('servicio-sucursal', []);
            foreach ($servcios as $servicio) {
                SucursalServicio::create([
                    'servicio_id' => $servicio['servicio_id'],
                    'sucursal_id' => $servicio['sucursal_id'],
                    'anexo1_id' => $anexo1->id
                ]);
                $sucursalIds[] = $servicio['sucursal_id'];
                $serviciosIds[] = $servicio['servicio_id'];
            }
            // Buscar todas las sucursales involucradas de una vez
            $sucursales = Sucursal::whereIn('id', $sucursalIds)->get();
            // Actualizar el estatus de cada sucursal
            foreach ($sucursales as $sucursal) {
                $sucursal->status_sucursal = 1;
                $sucursal->update();
            }
            // Buscar todas las servicio involucradas de una vez
            $servicios = Servicios::whereIn('id', $serviciosIds)->get();
            // Actualizar el estatus de cada sucursal
            foreach ($servicios as $servicio) {
                $servicio->status_servicio = 2;
                $servicio->update();
            }

            //actualiza la cotizacion
            $cotizacion->status_cotizacion = 4;
            $cotizacion->update();
            DB::commit();
            $this->reset();
            return 1;
        } catch (\Exception $e) {
            $this->reset();
            DB::rollBack();

            return 0;
        }
    }

    //se agrega la direccion fiscal como una sucursal
    public function direcconFiscal()
    {
        $cliente = Cliente::find($this->cliente_id);

        $sucursales = Sucursal::where('cliente_id', $this->cliente_id)->get();

        if ($sucursales->isEmpty()) {
            $this->ctg_cp_id = $cliente->ctg_cp_id;
            $this->direccion = $cliente->direccion;
            $this->referencias = 'Sin referencias';
            $this->sucursal = 'Direccion fiscal';
            $this->contacto = $cliente->user->name.' '.$cliente->user->paterno.' '.$cliente->user->materno;
            $this->cargo = $cliente->puesto;
            $this->correo = $cliente->user->email;
            $this->phone = $cliente->phone;
            $this->fecha_evaluacion = now();
            $this->fecha_inicio_servicio = now();
            $this->cp = $cliente->cp->cp;

            $this->store_sucursal();
        }
    }
}
