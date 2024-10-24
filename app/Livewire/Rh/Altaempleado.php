<?php

namespace App\Livewire\Rh;

use App\Models\Ctg_Area;
use App\Models\Empleado;
use App\Models\RevisorArea;
use App\Models\User;
use Spatie\Permission\Models\Role;
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
    public $fechaIngreso;
    public $SueldoMensual;
    public $roles;
    public $tallaCamisa, $tallaPantalon, $tallaZapatos;
    public $nombreEmergencia1, $telefonoEmergencia1, $parentescoEmergencia1, $direccionEmergencia1;
    public $nombreEmergencia2, $telefonoEmergencia2, $parentescoEmergencia2, $direccionEmergencia2;
    public $alergias, $tipoSangre, $umf, $hospital;
     use WithFileUploads;
    public $roles_user = [];
    public $revisor;
    public function mount()
    {
        $this->roles=Role::all();
        $this->colonias = collect();
        $this->areas = Ctg_Area::all();
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
    #[On('save-empleado')]
    public function guaradaempleado()
    {
        


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
            'roles_user' => 'array',

        ]);


        try {
            DB::transaction(function () {
                $id = User::create([
                    'name' => $this->nombreContacto,
                    'paterno' => $this->apepaterno,
                    'materno' => $this->apematerno,
                    'email' => $this->correoElectronico,
                    'password' => bcrypt(123456789),
                ]);

                if ($this->roles_user) {
                    // $user->assignRole($request->input('roles'));
                    $id->roles()->sync($this->roles_user);
                }
                $idempleado = Empleado::create([
                    'user_id' => $id->id,
                    'direccion' => $this->calleNumero,
                    'ctg_cp_id' => $this->ctg_cp_id,
                    'sexo' => $this->sexo,
                    'phone' => $this->telefono,
                    'ctg_area_id' => $this->area,
                    'status_empleado' => 1,
                    'fecha_nacimiento' => $this->fechaNacimiento,
                    'cve_empleado' => $this->cve_empleado,
                    'talla_camisa' => $this->tallaCamisa,
                    'talla_pantalon' => $this->tallaPantalon,
                    'talla_zapatos' => $this->tallaZapatos,
                    'nombre_emergencia1' => $this->nombreEmergencia1,
                    'telefono_emergencia1' => $this->telefonoEmergencia1,
                    'parentesco_emergencia1' => $this->parentescoEmergencia1,
                    'direccion_emergencia1' => $this->direccionEmergencia1,
                    'nombre_emergencia2' => $this->nombreEmergencia2,
                    'telefono_emergencia2' => $this->telefonoEmergencia2,
                    'parentesco_emergencia2' => $this->parentescoEmergencia2,
                    'direccion_emergencia2' => $this->direccionEmergencia2,
                    'alergias' => $this->alergias,
                    'tipo_sangre' => $this->tipoSangre,
                    'umf' => $this->umf,
                    'hospital' => $this->hospital,
                    'fecha_ingreso' =>$this->fechaIngreso
                ]);
                if ($this->image) {
                    $this->image->storeAs(path: 'fotosEmpleados/', name: $idempleado->id . '.png');
                }

                if ($this->revisor) {
                    foreach ($this->roles_user as $r) {
                        if ($r != 1 && $r != 3) {
                            RevisorArea::create([
                                'empleado_id' => $idempleado->id,
                                'ctg_area_id' => $this->area,
                            ]);
                        } else {
                            for ($i = 1; $i <= 8; $i++) {
                                RevisorArea::create([
                                    'empleado_id' => $idempleado->id,
                                    'ctg_area_id' => $i,
                                ]);
                            }
                        }
                    }
                }
            });
            $this->dispatch('success', ['El empleado se creó con éxito']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->dispatch('error', ['Ocurrió un error al registrar el empleado']);
        }
    }
}
