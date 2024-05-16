<?php

namespace App\Livewire\Rh;

use App\Models\Ctg_Area;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Altaempleado extends Component
{
    public $data = [];
    public $servicioId;
    public $nombreServicio;
    public $tipoServicio;
    public $unidadMedida;
    public $precioUnitario;
    public $editarPrecio;
    public $cantidad = 1;
    public $isAdmin;
    public $total;
    public $estados;
    public $municipios;
    public $cp;
    public $colonias;
    public $cp_invalido;
    public $ctg_cp_id = "";
    public $puesto = "";
    public $nombreContacto = "";
    public $razonSocial;
    public $rfc;
    public $ctg_tipo_cliente_id;
    public $tipoClientelist;
    public $calleNumero;
    public $telefono;
    public $correoElectronico;
    public $vigencia;
    public $condicionpago;
    public $condicionpagolist;
    public $servicios;
    public $precio_servicio;
    public $totalreal;
    public $valoridcoti;
    public $valoriidser;
    public $valoridcliente;
    public $valoridusuario;
    public $password;
    public $apepaterno;
    public $apematerno;
    public $editarPreciocheck;
    public $cantidadcheck;
    public $cantidadhabilitado = false;
    public $editarPreciohabilitado = false;
    public $sexo;
    public $areas;
    public $area;
    public $image;
    public $fechaNacimiento;
    public $cve_empleado;
    
   use WithFileUploads;


    public function mount()
    {
        $this->colonias = collect();
        $this->areas= Ctg_Area::all();
    }
    public function render()
    {
        return view('livewire.rh.altaempleado');
    }

    public function validarCp()
    {
        $this->validate([
            'cp' => 'required|digits_between:1,5',
        ], [
            'cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'cp.required' => 'Código postal requerido.',

        ]);
        $codigo = DB::select("
                SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
                FROM ctg_cp cp 
                LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
                LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
                WHERE cp LIKE CONCAT('%', " . $this->cp . " , '%')
            ");
        if ($codigo) {
            $this->municipios = $codigo[0]->municipio;
            $this->estados = $codigo[0]->name;
            $this->colonias = $codigo;
            $this->cp_invalido = "";
        } else {
            $this->cp_invalido = "Codigo postal no valido";
        }
    }        
    #[On('save-empleado')]
    public function guaradaempleado(){
            $this->validate([
            'nombreContacto' => 'required|string|max:255',
            'apepaterno' => 'required|string|max:255',
            'apematerno' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:20',
            'correoElectronico' => 'required|email|max:255|unique:users,email',
            'fechaNacimiento' => 'required|date',
            'sexo' => 'required|in:Masculino,Femenino',
            'area' => 'required',
            'cp' => 'required|string|max:10',
            'estados' => 'required|string|max:255',
            'municipios' => 'required|string|max:255',
            'ctg_cp_id' => 'required',
            'calleNumero' => 'required|string|max:255',
            'cve_empleado' => 'required|string|max:255|unique:empleados,cve_empleado',
        ]);
        try {
            DB::transaction(function () {
        $id= User::create([
            'name' => $this->nombreContacto,
            'paterno' => $this->apepaterno,
            'materno' => $this->apematerno,
            'email' => $this->correoElectronico,
            'password' => bcrypt(123456789),
        ]);
        $idempleado=Empleado::create([
            'user_id' => $id->id,
            'direccion' => $this->calleNumero,
            'ctg_cp_id' => $this->ctg_cp_id,
            'sexo' => $this->sexo,
            'phone' => $this->telefono,
            'ctg_area_id' => $this->area,
            'status_empleado' => 1,
            'fecha_nacimiento' => $this->fechaNacimiento,
            'cve_empleado' => $this->cve_empleado,
        ]);
        if($this->image){
            $this->image->storeAs(path: 'fotosEmpleados/', name: $idempleado->id.'.png');
        }
    });      
        $this->dispatch('success', ['El empleado se creó con éxito']);
    } catch (\Exception $e) {
        dd($e->getMessage()); 
        $this->dispatch('error', ['Ocurrió un error al registrar el empleado']);
    }

    }
}
