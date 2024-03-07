<?php

namespace App\Livewire\Forms;

use App\Models\Factibilidad;
use App\Models\FactibilidadImagen;
use App\Models\FactibilidadRpt;
use App\Models\Sucursal;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FactibilidadForm extends Form
{

    public $servicios;
    public $sucursal;
    public $sucursal_name;
    public $correo;
    public $phone;
    public $contacto;
    public $cargo;
    public $fecha_inicio_servicio;
    public $fecha_evaluacion;
    public $direccion;
    public $ejecutivo;
    public $sucursales_direccion;
    public $razon_social;
    public $rfc;
    public $evaluador;
    public $user_id;//id del evaluador para la factibilidad
    public $cliente_id;


    public function getSucursales(){
        $sucursal =  Sucursal::where('rpt_factibilidad_status','=',0)
        ->where('cliente_id','=',$this->cliente_id)
        ->get();

        return $sucursal;
    }
    public function DetalleSucursal($sucursal){


        $this->sucursal_name = $sucursal->sucursal;
        $this->correo = $sucursal->correo;
        $this->phone = $sucursal->phone;
        $this->contacto = $sucursal->contacto;
        $this->cargo = $sucursal->cargo;
        $this->fecha_inicio_servicio = $sucursal->fecha_inicio_servicio;
        $this->fecha_evaluacion = $sucursal->fecha_evaluacion;

        //direccion de la sucursal
        $codigo = DB::select("
            SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
            FROM ctg_cp cp 
            LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
            LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
            WHERE cp LIKE CONCAT('%', " . $sucursal->cp->cp . " , '%')
        ");

        $colonia='';
        foreach ($codigo as $c) {
            if ($sucursal->ctg_cp_id == $c->id) {
                $colonia = $c->colonia;
            }
        }

        $this->direccion = 'Calle ' . $sucursal->direccion.', Colonia '.$colonia. ', '.$sucursal->cp->cp.' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;

        //servicios
        $this->servicios = SucursalServicio::where('sucursal_id','=',$sucursal->id)->get();
        

    }
    public function DetalleReporte($sucursal){
        $this->sucursal = $sucursal;
        $this->sucursal_id = $sucursal->id;
        $this->sucursal_name = $sucursal->sucursal;
        $this->fecha_evaluacion = $sucursal->fecha_evaluacion;
        $this->razon_social = $sucursal->cliente->razon_social;
        $this->rfc = $sucursal->cliente->rfc_cliente;
        $this->evaluador= auth()->user()->name . ' ' . auth()->user()->paterno . ' ' . auth()->user()->materno;
        $this->user_id = auth()->user()->id;
        //direccion de la sucursal
        $codigo = DB::select("
            SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
            FROM ctg_cp cp 
            LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
            LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
            WHERE cp LIKE CONCAT('%', " . $sucursal->cp->cp . " , '%')
        ");

        $colonia='';
        foreach ($codigo as $c) {
            if ($sucursal->ctg_cp_id == $c->id) {
                $colonia = $c->colonia;
            }
        }

        $this->direccion = 'Calle ' . $sucursal->direccion.', Colonia '.$colonia. ', '.$sucursal->cp->cp.' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
    }
    //formulario...
    public $sucursal_id;
    public $tiposervicio;
    public $otro_tiposervicio;
    public $comohacerservicio;
    public $horarioservicio;
    public $personalparaservicio;
    public $tipoconstruccion;
    public $otro_tipoconstruccion;
    public $nivelproteccionlugar;
    public $perimetro;
    public $peatonales;
    public $vehiculares;
    public $ctrlacesos;
    public $guardiaseg;
    public $otros_guardiaseg;
    public $armados;
    public $corporacion_armados;
    public $alarma;
    public $tiposenial;
    public $otros_tiposenial;
    public $tipoderespuesta;
    public $tipodefalla;
    public $camaras;
    public $cofre;
    public $descripcion_asalto;
    public $tipodezona;
    public $conviene;
    public $observaciones;
    public $status_factibilidad;

    public $factibilidad_id;
    protected $rules = [
        'tiposervicio'=>'required',
        // 'otro_tiposervicio'=>'required',
        'comohacerservicio'=>'required',
        'horarioservicio'=>'required',
        'personalparaservicio'=>'required',
        'tipoconstruccion'=>'required',
        // 'otro_tipoconstruccion'=>'required',
        'nivelproteccionlugar'=>'required',
        'perimetro'=>'required',
        'peatonales'=>'required',
        'vehiculares'=>'required',
        'ctrlacesos'=>'required',
        'guardiaseg'=>'required',
        // 'otros_guardiaseg'=>'required',
        'armados'=>'required',
        'corporacion_armados'=>'required',
        'alarma'=>'required',
        'tiposenial'=>'required',
        // 'otros_tiposenial'=>'required',
        'tipoderespuesta'=>'required',
        'tipodefalla'=>'required',
        'camaras'=>'required',
        'cofre'=>'required',
        'descripcion_asalto'=>'required',
        'tipodezona'=>'required',
        'conviene'=>'required',
        // 'observaciones'=>'required',
        // 'status_factibilidad'=>'required',

    ];

    public function store($img_fachada,$img_acceso,$img_seguridad){

        $this->validate();
        $factibilidad = Factibilidad::create($this->only(['cliente_id','user_id',]));
        $this->factibilidad_id = $factibilidad->id;
        FactibilidadRpt::create($this->only([
            'sucursal_id',
            'tiposervicio',
            'comohacerservicio',
            'horarioservicio',
            'personalparaservicio',
            'tipoconstruccion',
            'nivelproteccionlugar',
            'perimetro',
            'peatonales',
            'vehiculares',
            'ctrlacesos',
            'guardiaseg',
            'armados',
            'corporacion_armados',
            'alarma',
            'tiposenial',
            'tipoderespuesta',
            'tipodefalla',
            'camaras',
            'cofre',
            'descripcion_asalto',
            'tipodezona',
            'conviene',
            'factibilidad_id'
        ]));

        //actualizo el status de la sucursal.
        $this->sucursal->rpt_factibilidad_status = 1;
        $this->sucursal->update();

        //verifico si aun quedan sucursales por validar.
        $sucursales=  Sucursal::where('cliente_id','=',$this->cliente_id)
            ->where('rpt_factibilidad_status','=',0)                
            ->count();


        if($sucursales==0){
            $factibilidad->status_factibilidad = 1;
        }


        // if($img_fachada!=''){
        //     //  $img_fachada->store('products'); guardara la imagen en la ruta de la carpeta del usuario

        //     FactibilidadImagen::create([
        //         'factibilidad_id'=>$factibilidad->id,
        //         'imagen'->$img_fachada,
        //     ]);
        // }

        // if($img_acceso!=''){
        //     //  $img_acceso->store('products'); guardara la imagen en la ruta de la carpeta del usuario

        //     FactibilidadImagen::create([
        //         'factibilidad_id'=>$factibilidad->id,
        //         'imagen'->$img_acceso,
        //     ]);
        // }
        // if($img_seguridad!=''){
        //     //  $img_seguridad->store('products'); guardara la imagen en la ruta de la carpeta del usuario

        //     FactibilidadImagen::create([
        //         'factibilidad_id'=>$factibilidad->id,
        //         'imagen'->$img_seguridad,
        //     ]);
        // }


        $this->reset([
            'sucursal_id','factibilidad_id','sucursal',
            'user_id',
            'tiposervicio',
            'comohacerservicio',
            'horarioservicio',
            'personalparaservicio',
            'tipoconstruccion',
            'nivelproteccionlugar',
            'perimetro',
            'peatonales',
            'vehiculares',
            'ctrlacesos',
            'guardiaseg',
            'armados',
            'corporacion_armados',
            'alarma',
            'tiposenial',
            'tipoderespuesta',
            'tipodefalla',
            'camaras',
            'cofre',
            'descripcion_asalto',
            'tipodezona',
            'conviene',
        ]);
    }
}
