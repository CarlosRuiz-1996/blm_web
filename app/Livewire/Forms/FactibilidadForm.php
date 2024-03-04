<?php

namespace App\Livewire\Forms;

use App\Models\Factibilidad;
use App\Models\FactibilidadImagen;
use App\Models\Sucursal;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FactibilidadForm extends Form
{

    public $sucursal;
    public $correo;
    public $phone;
    public $contacto;
    public $cargo;
    public $fecha_inicio_servicio;
    public $fecha_evaluacion;
    public $direccion;
    public $ejecutivo;

    public function getSucursales(){
        return Sucursal::where('rpt_factibilidad_status',0)->get();
    }
    public function DetalleSucursal($sucursal){
        $this->sucursal = $sucursal->sucursal;
        $this->correo = $sucursal->correo;
        $this->phone = $sucursal->phone;
        $this->contacto = $sucursal->contacto;
        $this->cargo = $sucursal->cargo;
        $this->fecha_inicio_servicio = $sucursal->fecha_inicio_servicio;
        $this->fecha_evaluacion = $sucursal->fecha_evaluacion;
        $this->direccion = $sucursal->direccion;
    }

    //formulario...
    public $sucursal_id;
    public $user_id;
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
        // $factibilidad = Factibilidad::create($this->only([
        //     'sucursal_id',
        //     'user_id',
        //     'tiposervicio',
        //     'comohacerservicio',
        //     'horarioservicio',
        //     'personalparaservicio',
        //     'tipoconstruccion',
        //     'nivelproteccionlugar',
        //     'perimetro',
        //     'peatonales',
        //     'vehiculares',
        //     'ctrlacesos',
        //     'guardiaseg',
        //     'armados',
        //     'corporacion_armados',
        //     'alarma',
        //     'tiposenial',
        //     'tipoderespuesta',
        //     'tipodefalla',
        //     'camaras',
        //     'cofre',
        //     'descripcion_asalto',
        //     'tipodezona',
        //     'conviene',
        // ]));

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
    }
}
