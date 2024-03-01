<?php

namespace App\Livewire;

use App\Models\Ctg_Cp;
use App\Models\Ctg_Estado;
use App\Models\Ctg_Municipio;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\Ctg_Tipo_Cliente;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CrearTablaCotizacion extends Component
{
    public $data = [];
    public $servicioId;
    public $nombreServicio;
    public $tipoServicio;
    public $unidadMedida;
    public $precioUnitario;
    public $editarPrecio;
    public $cantidad=1;
    public $isAdmin;
    public $total;
    public $estados;
    public $municipios;
    public $cp;
    public $colonias;
    public $cp_invalido;
    public $ctg_cp_id="";
    public $puesto="";
    public $nombreContacto="";
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
    
    
    
    public function mount()
    {
        $this->tipoClientelist=Ctg_Tipo_Cliente::all();
        $this->condicionpagolist=collect([
            (object)['id' => 1, 'nombre' => 'Efectivo'],
            (object)['id' => 2, 'nombre' => 'Transferencia Electrónica'],]);
        $this->colonias = collect();
        $this->servicios=ctg_servicios::all();
        $this->precio_servicio=ctg_precio_servicio::all();
        $this->totalreal=0;
    }

    public function render()
    {
        return view('livewire.crear-tabla-cotizacion');
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
                WHERE cp LIKE CONCAT('%', ".$this->cp." , '%')
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
        $this->totalreal=floatval($this->total)+floatval($this->totalreal);
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

    public function validaInfo()
    {
        $this->validate([
            'cp' => 'required|digits_between:1,5',
            'razonSocial' => 'required|string',
            'rfc' => 'required|string',
            'ctg_tipo_cliente_id' => 'required|in:1,2,3',
            'nombreContacto' => 'required|string',
            'puesto' => 'required|string',
            'estados' => 'required|string',
            'municipios' => 'required|string',
            'ctg_cp_id' => 'required|numeric',
            'calleNumero' => 'required|string',
            'telefono' => 'required|string',
            'correoElectronico' => 'required|email',
            'vigencia' => 'required',
            'condicionpago' => 'required',
            
            
        ], [
            'razonSocial.required' => 'La Razón Social es requerida.',
            'rfc.required' => 'El RFC es requerido.',
            'ctg_tipo_cliente_id.required' => 'El Tipo de Cliente es requerido.',
            'nombreContacto.required' => 'El Nombre del Contacto es requerido.',
            'puesto.required' => 'El Puesto es requerido.',
            'cp.digits_between' => 'El código postal debe tener entre 1 y 5 dígitos.',
            'cp.required' => 'Código postal requerido.',
            'municipios.required' => 'La Alcaldía/Municipio es requerida.',
            'ctg_cp_id.required' => 'La colonia es requerida.',
            'calleNumero.required' => 'La Calle y Número es requerida.',
            'telefono.required' => 'El Teléfono es requerido.',
            'correoElectronico.required' => 'El Correo Electrónico es requerido.',
            'vigencia.required' => 'La vigencia es requerido.',
            'condicionpago.required' => 'Condicion de pago es requerido.',
            'estados.required' => 'El estado es requerido.',
            

        ]);
            $codigo = DB::select("
                SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
                FROM ctg_cp cp 
                LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
                LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
                WHERE cp LIKE CONCAT('%', ".$this->cp." , '%')
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

    public function updatedServicioId($value)
    {
        $servicios=ctg_servicios::where('id',$value)->get();
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
