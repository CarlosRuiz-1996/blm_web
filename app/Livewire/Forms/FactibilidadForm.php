<?php

namespace App\Livewire\Forms;

use App\Models\Anexo1;
use App\Models\Factibilidad;
use App\Models\FactibilidadImagen;
use App\Models\FactibilidadRpt;
use App\Models\Sucursal;
use App\Models\SucursalServicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public $user_id; //id del evaluador para la factibilidad
    public $cliente_id;


    public function getSucursales(Anexo1 $anexo)
    {
        // return SucursalServicio::where('anexo1_id', '=', $anexo->id)->get();


        return Sucursal::with('sucursal_servicio')
            ->whereHas('sucursal_servicio', function ($query) use ($anexo) {
                $query->where('anexo1_id', $anexo->id);
            })
            ->get();
    }
    public function DetalleSucursal($sucursal)
    {


        $this->sucursal_name = $sucursal->sucursal;
        $this->correo = $sucursal->correo;
        $this->phone = $sucursal->phone;
        $this->contacto = $sucursal->contacto;
        $this->cargo = $sucursal->cargo;
        $this->fecha_inicio_servicio = $sucursal->fecha_inicio_servicio;
        $this->fecha_evaluacion = $sucursal->fecha_evaluacion;

        //direccion de la sucursal
        $this->direccion = $this->direccionSucursal($sucursal);
        //servicios
        $this->servicios = SucursalServicio::where('sucursal_id', '=', $sucursal->id)->get();
    }

    public function direccionSucursal(Sucursal $sucursal)
    {
        $codigo = DB::select("
            SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
            FROM ctg_cp cp 
            LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
            LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
            WHERE cp LIKE CONCAT('%', '" . $sucursal->cp->cp . "' , '%')
            
        ");

        $colonia = '';
        foreach ($codigo as $c) {
            if ($sucursal->ctg_cp_id == $c->id) {
                $colonia = $c->colonia;
            }
        }

        return 'Calle ' . $sucursal->direccion . ', Colonia ' . $colonia . ', ' . $sucursal->cp->cp . ' ' . $codigo[0]->municipio . ' ' . $codigo[0]->name;
    }
    public function DetalleReporte($sucursal)
    {
        $this->sucursal = $sucursal;
        $this->sucursal_id = $sucursal->id;
        $this->sucursal_name = $sucursal->sucursal;
        $this->fecha_evaluacion = $sucursal->fecha_evaluacion;
        $this->razon_social = $sucursal->cliente->razon_social;
        $this->rfc = $sucursal->cliente->rfc_cliente;
        $this->evaluador = auth()->user()->name . ' ' . auth()->user()->paterno . ' ' . auth()->user()->materno;
        $this->user_id = auth()->user()->id;
        //direccion de la sucursal

        $this->direccion = $this->direccionSucursal($sucursal);
    }
    //formulario...
    public $sucursal_id;
    public $tiposervicio;
    public $otro_tiposervicio = '';
    public $comohacerservicio;
    public $horarioservicio;
    public $personalparaservicio;
    public $tipoconstruccion;
    public $otro_tipoconstruccion = '';
    public $nivelproteccionlugar;
    public $perimetro;
    public $peatonales;
    public $vehiculares;
    public $ctrlacesos;
    public $guardiaseg;
    public $otros_guardiaseg = '';
    public $armados;
    public $corporacion_armados = '';
    public $alarma;
    public $tiposenial;
    public $otros_tiposenial = '';
    public $tipoderespuesta;
    public $tipodefalla;
    public $camaras;
    public $cofre;
    public $descripcion_asalto;
    public $tipodezona;
    public $conviene;
    public $observaciones;
    public $status_factibilidad;
    public $anexo1_id;
    public $factibilidad_id;
    protected $rules = [
        'tiposervicio' => 'required',
        'comohacerservicio' => 'required',
        'personalparaservicio' => 'required',
        'tipoconstruccion' => 'required',
        'nivelproteccionlugar' => 'required',
        'perimetro' => 'required',
        'peatonales' => 'required',
        'vehiculares' => 'required',
        'ctrlacesos' => 'required',
        'guardiaseg' => 'required',
        'armados' => 'required',
        'alarma' => 'required',
        'tiposenial' => 'required',
        'tipoderespuesta' => 'required',
        'tipodefalla' => 'required',
        'camaras' => 'required',
        'cofre' => 'required',
        'descripcion_asalto' => 'required',
        'tipodezona' => 'required',
        'conviene' => 'required',


    ];

    public function store(Anexo1 $anexo, $img_fachada, $img_acceso, $img_seguridad)
    {

        try {
            DB::beginTransaction();
           
            $this->anexo1_id = $anexo->id;

            if (count($anexo->factibilidad) > 0) {
                $factibilidad = $anexo->factibilidad[0];
                $this->factibilidad_id = $factibilidad->id;
            } else {
                $factibilidad = Factibilidad::create($this->only(['cliente_id', 'user_id', 'anexo1_id']));
                $this->factibilidad_id = $factibilidad->id;
            }

            $this->factibilidad_id = $factibilidad->id;
            $factibilidad_rpt = FactibilidadRpt::create($this->only([
                'sucursal_id',
                'factibilidad_id',
                'tiposervicio',
                'otro_tiposervicio',
                'comohacerservicio',
                'horarioservicio',
                'personalparaservicio',
                'tipoconstruccion',
                'otro_tipoconstruccion',
                'nivelproteccionlugar',
                'perimetro',
                'peatonales',
                'vehiculares',
                'ctrlacesos',
                'guardiaseg',
                'otros_guardiaseg',
                'armados',
                'corporacion_armados',
                'alarma',
                'tiposenial',
                'otros_tiposenial',
                'tipoderespuesta',
                'tipodefalla',
                'camaras',
                'cofre',
                'descripcion_asalto',
                'tipodezona',
                'conviene',
                'observaciones',
            ]));


            // //actualizo el status de la sucursal.
            $this->sucursal->rpt_factibilidad_status = 1;
            $this->sucursal->update();

            //actualizo el estado del anexo para controlar el proceso
            $anexo->status_anexo = 2;
            $anexo->update();


            //verifico si aun quedan sucursales por validar.
            $sucursales = SucursalServicio::where('anexo1_id', '=', $anexo->id)
                ->whereHas('sucursal', function ($query) {
                    $query->where('rpt_factibilidad_status', 0);
                })
                ->count();



            if ($sucursales == 0) {
                Log::info('TERMINA FACTIBILIDAD Y  ANEXO');

                //indico que la factibilidad termino
                $factibilidad->status_factibilidad = 1;
                $factibilidad->update();
                $anexo->status_anexo = 3;
                $anexo->update();
            }


            if ($img_fachada) {
                $namef = 'foto_fachada.' . $img_fachada->getClientOriginalExtension();
                $img_fachada->storeAs(
                    path: 'documentos/' . $anexo->cliente->rfc_cliente . '/fotografias_establecimiento',
                    name: $namef
                );


                FactibilidadImagen::create([
                    'factibilidad_rpt_id' => $factibilidad_rpt->id,
                    'imagen' => $namef,
                ]);
            }

            if ($img_acceso != '') {
                $namea = 'foto_acceso.' . $img_acceso->getClientOriginalExtension();
                $img_acceso->storeAs(
                    path: 'documentos/' . $anexo->cliente->rfc_cliente . '/fotografias_establecimiento',
                    name: $namea
                );


                FactibilidadImagen::create([
                    'factibilidad_rpt_id' => $factibilidad_rpt->id,
                    'imagen' => $namea,
                ]);
            }
            if ($img_seguridad != '') {
                $names = 'foto_seguridad.' . $img_seguridad->getClientOriginalExtension();
                $img_seguridad->storeAs(
                    path: 'documentos/' . $anexo->cliente->rfc_cliente . '/fotografias_establecimiento',
                    name: $names
                );


                FactibilidadImagen::create([
                    'factibilidad_rpt_id' => $factibilidad_rpt->id,
                    'imagen' => $names,
                ]);
            }

            DB::commit();

            $this->reset([
                'sucursal_id', 'factibilidad_id', 'sucursal',
                'user_id', 'anexo1_id',
                'tiposervicio',
                'otro_tiposervicio',
                'comohacerservicio',
                'horarioservicio',
                'personalparaservicio',
                'tipoconstruccion',
                'otro_tipoconstruccion',
                'nivelproteccionlugar',
                'perimetro',
                'peatonales',
                'vehiculares',
                'ctrlacesos',
                'guardiaseg',
                'otros_guardiaseg',
                'armados',
                'corporacion_armados',
                'alarma',
                'tiposenial',
                'otros_tiposenial',
                'tipoderespuesta',
                'tipodefalla',
                'camaras',
                'cofre',
                'descripcion_asalto',
                'tipodezona',
                'conviene',
                'observaciones'
            ]);
            return 1;
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error al intentar guardar los datos: ' . $e->getMessage());

            $this->validate();
            return 0;
        }
    }
}
