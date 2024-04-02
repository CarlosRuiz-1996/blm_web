<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\cotizacion;
use App\Models\cotizacion_servicio;
use App\Models\Ctg_Cp;
use App\Models\Ctg_Estado;
use App\Models\Ctg_Municipio;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\Ctg_Tipo_Cliente;
use App\Models\servicios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class CotizacionNuevo extends Component
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
    public $datoscliente;
    public $pais = "México";
    public $nombretipocliente;
    public $listcliente;
    public $listuser;
    public $tipocliente;
    public $iduser;
    public $idcodpostal;
    public $listcp;
    public $colonia;
    public $listestado;
    public $listmunicipio;
    public $id;
    public $valoridcoti;
    public $valoriidser;
    public function mount(Request $request)
    {
        // Obtener el valor del parámetro "id" de la URL
        $id = $request->route('id');
        $this->id = $id;
        $this->datoscliente = Cliente::where('id', $id)->get();
        $this->listcliente = Ctg_Tipo_Cliente::where('id', $this->datoscliente[0]->ctg_tipo_cliente_id)->get();
        $this->ctg_tipo_cliente_id = $this->listcliente[0]->id;
        $this->tipocliente = $this->listcliente[0]->name;
        $this->telefono = $this->datoscliente[0]->phone;
        $this->rfc = $this->datoscliente[0]->rfc_cliente;
        $this->razonSocial = $this->datoscliente[0]->razon_social;
        $this->idcodpostal = $this->datoscliente[0]->ctg_cp_id;
        $this->listcp = Ctg_Cp::where('id', $this->idcodpostal)->get();
        $this->colonia = $this->listcp[0]->colonia;
        $this->cp = $this->listcp[0]->cp;

        $this->listestado = Ctg_Estado::where('id', $this->listcp[0]->ctg_estado_id)->get();
        $this->listmunicipio = Ctg_Municipio::where('id', $this->listcp[0]->ctg_municipio_id)->get();
        $this->estados = $this->listestado[0]->name;
        $this->municipios = $this->listmunicipio[0]->municipio;

        $this->puesto = $this->datoscliente[0]->puesto;
        $this->calleNumero = $this->datoscliente[0]->direccion;
        $this->iduser = $this->datoscliente[0]->user_id;
        $this->listuser = User::where('id', $this->iduser)->get();
        $this->correoElectronico = $this->listuser[0]->email;
        $this->nombreContacto = $this->listuser[0]->name . ' ' . $this->listuser[0]->paterno . ' ' . $this->listuser[0]->materno;
        //dd($this->datoscliente);
        $this->tipoClientelist = Ctg_Tipo_Cliente::all();
        $this->condicionpagolist = collect([
            (object)['id' => 1, 'nombre' => 'Efectivo'],
            (object)['id' => 2, 'nombre' => 'Transferencia Electrónica'],
        ]);
        $this->colonias = collect();
        $this->servicios = ctg_servicios::all();
        $this->precio_servicio = ctg_precio_servicio::all();
        $this->totalreal = 0;
    }

    public function render()
    {
        return view('livewire.cotizacion-nuevo');
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

    public function llenartabla()
    {
        $this->validate([
            'servicioId' => 'required',
            'nombreServicio' => 'required',
            'tipoServicio' => 'required',
            'unidadMedida' => 'required',
            'precioUnitario' => 'required|numeric',
            'editarPrecio' => 'numeric',
            'cantidad' => 'numeric',
            'total' => 'required|numeric',
        ], [
            'servicioId.required' => 'El campo Servicio es requerido.',
            'nombreServicio.required' => 'El campo Nombre de Servicio es requerido.',
            'tipoServicio.required' => 'El campo Tipo de Servicio es requerido.',
            'unidadMedida.required' => 'El campo Unidad de Medida es requerido.',
            'precioUnitario.required' => 'El campo Precio Unitario es requerido.',
            'precioUnitario.numeric' => 'El campo Precio Unitario debe ser un número.',
            'editarPrecio.numeric' => 'El campo Editar Precio debe ser un número.',
            'total.required' => 'El campo Total es requerido.',
            'total.numeric' => 'El campo Total debe ser un número.',
        ]);
        $this->totalreal = floatval($this->totalreal) + floatval($this->totalreal);
        $especial = $this->isAdmin ? 'Especial' : 'Normal';
        $this->data[] = [
            'id' => count($this->data) + 1,
            'servicioId' => $this->servicioId,
            'nombreservicio' => $this->nombreServicio,
            'cantidad' => $this->cantidad,
            'tiposervicio' => $this->tipoServicio,
            'unidadmedida' => $this->unidadMedida,
            'preciounitario' => $this->precioUnitario,
            'editarPrecio' => $this->editarPrecio,
            'isAdmin' => $especial,
            'total' => $this->total,
        ];

        // Limpiar los campos después de agregar un nuevo elemento
        $this->limpiarCampos();

        return view('livewire.crear-tabla-cotizacion');
    }

    private function limpiarCampos()
    {
        // Restablecer los valores de los campos a su estado inicial o valores predeterminados
        $this->servicioId = '';
        $this->nombreServicio = '';
        $this->tipoServicio = '';
        $this->unidadMedida = '';
        $this->precioUnitario = '';
        $this->editarPrecio = '';
        $this->cantidad = 1;
        $this->isAdmin = '';
        $this->total = '';
    }
    
    #[On('save-cotizacion')]
    public function validaInfo()
    {

        $this->valoridcoti = cotizacion::create([
            'total' => $this->totalreal,
            'vigencia' => $this->vigencia,
            'ctg_tipo_pago_id' => $this->condicionpago,
            'cliente_id' => $this->id,
            'status_cotizacion' => 1,
        ]);

        // Obtener el ID de la cotización recién creada
        $cotizacionIdreturn = $this->valoridcoti->id;

        foreach ($this->data as $datos) {
            $this->valoriidser = servicios::create([
                'precio_unitario' => $datos['preciounitario'],
                'cantidad' => $datos['cantidad'],
                'subtotal' => $datos['total'],
                'ctg_precio_servicio_id' => $datos['id'],
                'ctg_servicios_id' => $datos['servicioId'],
                'servicio_especial' => $datos['isAdmin'] ? 1 : 0,
                'status_servicio' => 1,
            ]);

            // Obtener el ID del servicio recién creado
            $servicioIdreturn = $this->valoriidser->id;
            cotizacion_servicio::create([
                'cotizacion_id' => $cotizacionIdreturn,
                'servicio_id' => $servicioIdreturn,
                'status_cotizacion_servicio' => '1'
            ]);
        }
        $this->dispatch('success-cotizacion',['La cotización se creo con exito']);
    }

    public function updatedServicioId($value)
    {
        $servicios = ctg_servicios::where('id', $value)->get();
        $this->nombreServicio = $servicios[0]->descripcion;
        $this->tipoServicio = $servicios[0]->tipo;
        $this->unidadMedida = $servicios[0]->unidad;
    }
    public function updatedCantidad()
    {
        $precioUnitarioNumerico = floatval($this->precioUnitario);
        $cantidadNumerica = floatval($this->cantidad);

        $this->total = $precioUnitarioNumerico * $cantidadNumerica;
    }
    public function updatedPrecioUnitario()
    {
        $precioUnitarioNumerico = floatval($this->precioUnitario);
        $cantidadNumerica = floatval($this->cantidad);

        $this->total = $precioUnitarioNumerico * $cantidadNumerica;
    }
}
