<?php

namespace App\Livewire\Rh;

use App\Models\Ctg_Area;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class EmpleadosPerfil extends Component
{
    use WithFileUploads;
    public $id; // Definir una propiedad para almacenar el ID del empleado
    public $isOpenempleado = false;
    public $employeeId;
    public $nombreContacto, $apepaterno, $apematerno, $telefono, $correoElectronico, $fechaNacimiento, $sexo, $roles_user = [];
    public $area, $revisor, $cp, $estados, $municipios, $colonias, $calleNumero, $tallaCamisa, $tallaPantalon, $tallaZapatos;
    public $nombreEmergencia1, $telefonoEmergencia1, $parentescoEmergencia1, $direccionEmergencia1;
    public $nombreEmergencia2, $telefonoEmergencia2, $parentescoEmergencia2, $direccionEmergencia2;
    public $alergias, $tipoSangre, $SueldoMensual,$cve_empleado;
    public $roles;
    public $areas,$fechaIngreso;
    public $cp_invalido,$ctg_cp_id;
    public $umf,$hospital;
    

    public $foto;


    //incializa el componenete  con los valosres de los catalogos
    public function mount($id)
    {
        $this->roles=Role::all();
        $this->colonias = collect();
        $this->areas = Ctg_Area::all();
        $this->roles = Role::all();
        $this->id = $id; // Asignar el ID recibido a la propiedad $id
    }
   
//abre modal de edicion y asigna valores encontrados para el empleado
    public function openModal($id)
    {
        $employee = Empleado::find($id);
        $this->employeeId = $employee->id;
        $this->nombreContacto = $employee->user->name;
        $this->apepaterno = $employee->user->paterno;
        $this->apematerno = $employee->user->materno;
        $this->telefono = $employee->phone;
        $this->correoElectronico = $employee->user->email;
        $this->fechaNacimiento = $employee->fecha_nacimiento;
        $this->sexo = $employee->sexo;
        $this->cve_empleado = $employee->cve_empleado;
        $this->cp = $employee->codigoPostal->cp;
        $this->colonias = $employee->colonias;
        $this->calleNumero = $employee->direccion;
        $this->tallaCamisa = $employee->talla_camisa;
        $this->tallaPantalon = $employee->talla_pantalon;
        $this->tallaZapatos = $employee->talla_zapatos;
        $this->nombreEmergencia1 = $employee->nombre_emergencia1;
        $this->telefonoEmergencia1 = $employee->telefono_emergencia1;
        $this->parentescoEmergencia1 = $employee->parentesco_emergencia1;
        $this->direccionEmergencia1 = $employee->direccion_emergencia1;
        $this->nombreEmergencia2 = $employee->nombre_emergencia2;
        $this->telefonoEmergencia2 = $employee->telefono_emergencia2;
        $this->parentescoEmergencia2 = $employee->parentesco_emergencia2;
        $this->direccionEmergencia2 = $employee->direccion_emergencia2;
        $this->alergias = $employee->alergias;
        $this->tipoSangre = $employee->tipo_sangre;
        $this->umf = $employee->umf;
        $this->hospital = $employee->hospital;
        $this->isOpenempleado = true; // Abre el modal
        $this->fechaIngreso = \Carbon\Carbon::parse($employee->fecha_ingreso)->format('Y-m-d'); // Aquí el formato
        $this->ctg_cp_id=$employee->ctg_cp_id;
        $this->validarCp();
    }
        public function closeModal(){
            $this->isOpenempleado = false; // Abre el modal
        }

        public function activarempleado($id)
        {
            // Busca al empleado por ID y actualiza su estado a activo (1)
            $empleado = Empleado::find($id);
            if ($empleado) {
                $empleado->status_empleado = 1; // Activo
                $empleado->save();
        
                // Verifica si el empleado tiene un usuario asociado y actíva
                if ($empleado->user) {
                    $empleado->user->status_user = 1; // Activo
                    $empleado->user->save();
                }
            }
        }
        
    public function desactivarempleado($id)
    {
        // Busca al empleado por ID y actualiza su estado a inactivo (0)
        $empleado = Empleado::find($id);
        if ($empleado) {
            // Desactiva el empleado
            $empleado->status_empleado = 0;
            $empleado->save();

            // Verifica que el empleado tenga un usuario asociado y desactívalo
            if ($empleado->user) {
                $empleado->user->status_user = 0; // Cambia a activo si es lo que necesitas
                $empleado->user->save();
            }
        }
    }

    public function editarempleado(){

    }

    public function render()
    {
        // Buscar al empleado utilizando el ID
        $empleado = Empleado::find($this->id);

        // Pasar el empleado a la vista
        return view('livewire.rh.empleados-perfil', ['empleado' => $empleado]);
    }
   //valida coidgo postal  y obtiene estado y municipio
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
                WHERE cp LIKE CONCAT('%', '" . $this->cp . "' , '%')
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

        public function guardarInformacion()
        {
            // Validar los datos recibidos
            $this->validate([
                'nombreContacto' => 'required|string|max:255',
                'apepaterno' => 'required|string|max:255',
                'apematerno' => 'required|string|max:255',
                'telefono' => 'required|string|max:15',
                'correoElectronico' => 'required|email|max:255',
                'fechaNacimiento' => 'required|date',
                'sexo' => 'required|string',
                'calleNumero' => 'required|string|max:255',
                'foto' => 'nullable|image|max:1024', 
            ]);

            // Actualizar el empleado
            $employee = Empleado::find($this->employeeId);
            $employee->user->name = $this->nombreContacto;
            $employee->user->paterno = $this->apepaterno;
            $employee->user->materno = $this->apematerno;
            $employee->phone = $this->telefono;
            $employee->user->email = $this->correoElectronico;
            $employee->fecha_nacimiento = $this->fechaNacimiento;
            $employee->sexo = $this->sexo;
            $employee->cve_empleado = $this->cve_empleado;
            $employee->direccion = $this->calleNumero;
            $employee->talla_camisa = $this->tallaCamisa;
            $employee->talla_pantalon = $this->tallaPantalon;
            $employee->talla_zapatos = $this->tallaZapatos;
            $employee->nombre_emergencia1 = $this->nombreEmergencia1;
            $employee->telefono_emergencia1 = $this->telefonoEmergencia1;
            $employee->parentesco_emergencia1 = $this->parentescoEmergencia1;
            $employee->direccion_emergencia1 = $this->direccionEmergencia1;
            $employee->nombre_emergencia2 = $this->nombreEmergencia2;
            $employee->telefono_emergencia2 = $this->telefonoEmergencia2;
            $employee->parentesco_emergencia2 = $this->parentescoEmergencia2;
            $employee->direccion_emergencia2 = $this->direccionEmergencia2;
            $employee->alergias = $this->alergias;
            $employee->tipo_sangre = $this->tipoSangre;
            $employee->umf = $this->umf;
            $employee->hospital = $this->hospital;
            $employee->fecha_ingreso = \Carbon\Carbon::parse($this->fechaIngreso); // Formatear si es necesario
            $employee->ctg_cp_id = $this->ctg_cp_id; // Asegúrate de que este campo se actualice adecuadamente
            if ($this->foto) {
                $this->foto->storeAs(path: 'fotosEmpleados/', name: $this->employeeId . '.png');
            }

            // Guardar los cambios
            $employee->user->save(); // Guardar cambios del usuario
            $employee->save(); // Guardar cambios del empleado
            $this->isOpenempleado = false; // Abre el modal
            $this->dispatch('empleadoupdate', ['El empleado se modifico con éxito']);
        }
}
