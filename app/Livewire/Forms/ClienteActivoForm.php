<?php

namespace App\Livewire\Forms;

use App\Models\Anexo1;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Ctg_Cp;
use App\Models\Ctg_Tipo_Cliente;
use App\Models\Memorandum;
use App\Models\MemorandumServicios;
use App\Models\Servicios;
use App\Models\servicios_conceptos_foraneos;
use App\Models\SucursalServicio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
    public $ctg_tipo_cliente_id = 0;
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
        'ctg_cp_id' => 'El campo colonia es obligatorio.'
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
    public function store()
    {


        $this->validate(); //validacion


        $this->password =  bcrypt(strtolower($this->rfc_cliente)); //contraseña sera el rfc en minusculas

        // ingreso en la tabla usuarios
        $user = User::create($this->only(['name', 'paterno', 'materno', 'email', 'password']));
        $user->roles()->sync(4); //asigno rol 4 que sera de cliente.

        $this->user_id = $user->id; //usuario_id para relacionar con la tabla cliente
        //creo cliente
        Cliente::create($this->only([
            'user_id', 'puesto', 'direccion', 'ctg_cp_id', 'razon_social', 'rfc_cliente',
            'phone', 'ctg_tipo_cliente_id'
        ]));


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
        $codigo = Ctg_Cp::where('cp', 'like', '%' . $this->cp . '%')->get();

        $this->colonias = $codigo;
    }

    public function updated()
    {


        $this->validate(); //validacion
        $this->password =  bcrypt(strtolower($this->rfc_cliente)); //contraseña sera el rfc en minusculas
        $this->cliente->user->update($this->only(['name', 'paterno', 'materno', 'email', 'password']));

        $this->cliente->update($this->only([
            'puesto', 'direccion', 'ctg_cp_id', 'razon_social', 'rfc_cliente',
            'phone', 'ctg_tipo_cliente_id'
        ]));


        // $this->reset();
    }

    public function getCotizaciones($cliente_id)
    {
        return Cotizacion::where('cliente_id', $cliente_id)->orderBy('id', 'DESC')->paginate(10);
    }

    public function getServicios()
    {
        return Servicios::where('cliente_id', $this->cliente->id)
            ->where(function ($query) {
                $query->where('status_servicio', '>=', 3)
                    ->orWhere('status_servicio', 0);
            })->orderBy('id','DESC')
            ->paginate(10);
    }

    public function updateServicio(Servicios $servicio, $accion)
    {
        if ($accion == 1) {
            //para darlo de baja se cambia el status a 1.
            $servicio->status_servicio = 0;
            $servicio->save();
        } else {
            //para reactivarlo se cambia status a 3.
            $servicio->status_servicio = 3;
            $servicio->save();
        }
    }


    //guardar datos complementarios:

    public function saveComplementarios($dataforaneo, $data, $listaForaneosguarda)
    {

        try {
            DB::beginTransaction();
            $servicio = "";
            //crea servicio
            if (empty($dataforaneo)) {
                foreach ($data as $datos) {
                    // Realizar la inserción en la base de datos
                    $servicio = Servicios::create([
                        'precio_unitario' => $datos['preciounitario'],
                        'cantidad' => $datos['cantidad'],
                        'subtotal' => $datos['total'],
                        'ctg_servicios_id' => $datos['servicioId'],
                        'servicio_especial' => $datos['isAdmin'] ? 1 : 0,
                        'status_servicio' => 3,
                        'cliente_id' => $this->cliente->id,
                    ]);
                }
            } else {
                foreach ($dataforaneo as $datosf) {
                    // Realizar la inserción en la base de datos
                    $servicio = Servicios::create([
                        'precio_unitario' => $datosf['sumatotal'],
                        'cantidad' => 1,
                        'subtotal' => $datosf['sumatotal'],
                        'servicio_especial' => 1,
                        'status_servicio' => 3,
                        'kilometros' => $datosf['km'],
                        'kilometros_costo' => $datosf['costokm'],
                        'miles' => $datosf['miles'],
                        'miles_costo' => $datosf['milesprecio'],
                        'servicio_foraneo' => 1,
                        'gastos_operaciones' => $datosf['goperacion'],
                        'iva' => $datosf['totaliva'],
                        'cliente_id' => $this->cliente->id,
                        'foraneo_destino' => $datosf['destinoruta'],
                        'foraneo_inicio'  => $datosf['inicioruta'],
                        'montotransportar_foraneo'  => $datosf['cantidadlleva'],

                    ]);
                }
                foreach ($listaForaneosguarda as $concepto) {
                    servicios_conceptos_foraneos::create([
                        'concepto' => $concepto['consepforaneo'], // Aquí ajusta según la estructura de tu array
                        'costo' => $concepto['precioconsepforaneo'], // Aquí ajusta según la estructura de tu array
                        'servicio_id' => $servicio->id,
                        'cantidadfora' => $concepto['cantidadfora'],
                    ]);
                }
            }



            $servcios_sucursal = Session::get('servicio-sucursal', []);
            $servcios_memo = Session::get('servicio-memo', []);
            $sucursal_servicio="";
            if (!empty($servcios_sucursal) && is_array($servcios_sucursal)) {
                $anexo1 = Anexo1::create(['cliente_id' => $this->cliente->id]);
                $registro = $servcios_sucursal[0];
                $sucursal_servicio = SucursalServicio::create([
                    'servicio_id' => $servicio->id,
                    'sucursal_id' => $registro['sucursal_id'],
                    'anexo1_id' => $anexo1->id
                ]);
            }

            if (!empty($servcios_memo) && is_array($servcios_memo)) {
                $registro = $servcios_memo[0];
                $memorandum = Memorandum::create(
                    [
                        'grupo' => $registro['grupo'],
                        'ctg_tipo_solicitud_id' => $registro['ctg_tipo_solicitud'],
                        'ctg_tipo_servicio_id' => $registro['ctg_tipo_servicio'],
                        'observaciones' => '',
                        'cliente_id' => $this->cliente->id,
                        'status_memoranda' => 2
                    ]
                );
                
                MemorandumServicios::create([
                    'sucursal_servicio_id' =>$sucursal_servicio->id,
                    'memoranda_id' => $memorandum->id,
                    'ctg_dia_servicio_id' => $registro['horarioEntrega'],
                    'ctg_dia_entrega_id' => $registro['diaEntrega'],
                    'ctg_horario_servicio_id' => $registro['horarioServicio'],
                    'ctg_horario_entrega_id' => $registro['diaServicio'],
                    'ctg_consignatario_id' => $registro['consignatorio'],
                ]);
                Log::info("termina-MemorandumServicios");
            }
      
            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al intentar guardar los datos: ' . $e->getMessage());
            return 0;
        }
    }
}
