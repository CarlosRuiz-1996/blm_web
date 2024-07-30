<?php

namespace App\Livewire\Operadores;

use App\Models\Empleado;
use App\Models\Ruta;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use App\Models\ServicioEvidenciaEntrega;
use App\Models\ServicioEvidenciaRecolecta;
use App\Models\ServicioRutaEnvases;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class OperadoresIndex extends Component
{
    public $idrecolecta;
    public $cantidadEnvases;
    public $idserviorutaEnvases;
    public $tiposervicio;
    public $statusEnvases;
    public $inputs = [];
    public $folios = [];
    public $originalFolios = [];
    public $MontoEntregado;
    public $MontoEntrega;
    public $MontoRecolecta;
    public $readyToLoad = false;
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $envasescantidad;
    public $IdservicioReprogramar;
    public $evidencias = [];
    public $motivoReprogramarConcepto;
    #[Validate(['photo.*' => 'image|max:1024'])]
    public $photo = [];
    #[Validate(['photo.*' => 'image|max:1024'])]
    public $photorepro;
    protected $listeners = ['modalCerrado', 'modalCerradoReprogramar'];
    public $papeleta;
    use WithFileUploads;
    public function render()
    {
        $AutorizadoOperador = null;
        $identificado = false;
        $rutaEmpleados = [];
        $nombreUsuario = "";
        $UsuarioLogin = Auth::user();
        if ($UsuarioLogin) {
            $nombreUsuario = $UsuarioLogin->name . ' ' . $UsuarioLogin->paterno . ' ' . $UsuarioLogin->materno;
            $AutorizadoOperador = Empleado::where('user_id', $UsuarioLogin->id)->where('ctg_area_id', 18)->first();
        }
        if ($AutorizadoOperador) {
            $rutaEmpleados = Ruta::join('ruta_empleados', 'rutas.id', '=', 'ruta_empleados.ruta_id')
                ->where('rutas.ctg_rutas_estado_id', 3)
                ->where('ruta_empleados.status_ruta_empleados', 1)
                ->where('ruta_empleados.empleado_id', $AutorizadoOperador->id)
                ->get(['rutas.*']);
            $identificado = true;
        } else {
            $identificado = false;
        }


        // Pasa los resultados a la vista
        return view('livewire.operadores.operadores-index', compact('rutaEmpleados', 'nombreUsuario', 'identificado'));
    }

    public function ModalEntregaRecolecta($id, $tiposervicio)
    {

        $this->tiposervicio = $tiposervicio == 1 ? 'Entrega' : ($tiposervicio == 2 ? 'Recolección' : 'Otro');
        $servicioRuta = RutaServicio::find($id);
        $this->papeleta = $servicioRuta->folio;
        $this->MontoEntrega = $servicioRuta->monto;
        $this->idrecolecta = $id;
        $this->cantidadEnvases = $servicioRuta->envases;
        // Consultar los registros de servicios_envases_rutas para esta ruta
        $serviciosEnvases = ServicioRutaEnvases::where('ruta_servicios_id', $id)->where('status_envases',1)->get();

        // Si hay registros, llenar los arreglos con los valores recuperados
        if ($serviciosEnvases->isNotEmpty()) {
            $this->inputs = $serviciosEnvases->mapWithKeys(function ($item) {
                return [$item->id => [
                    'cantidad' => $item->cantidad,
                    'folio' => $item->folio,
                    'photo' => '',
                    'sello' => $item->sello_seguridad,
                    'violado' => false,
                ]];
            })->toArray();
        } else {

            $this->inputs = [];
            for ($i = 0; $i < $this->envasescantidad; $i++) {
                $this->inputs[] = [
                    'cantidad' => '',
                    'folio' => $this->papeleta,
                    'photo' => '',
                    'sello' => '',
                    'violado' => false,
                ];
            }
        }
    }

    public function envase_recolecta()
    {
        $this->validate([
            'envasescantidad' => 'required',
            'MontoRecolecta' => 'required',
        ], [
            'envasescantidad.required' => 'La cantidad de envases es obligatoria',
            'MontoRecolecta.required' => 'Debe ingresar el monto total',

        ]);
        $this->ModalEntregaRecolecta($this->idrecolecta, $this->tiposervicio == 'Recolección' ? 2 : 1);
    }

    public function ModalAceptar()
    {
        $this->validate([
            'idrecolecta' => 'required',
            'MontoEntrega' => 'required',
            // 'MontoEntregado' => 'required',
            'inputs.*.photo' => 'required|image|max:1024', // Máximo 1MB
        ], [
            'inputs.*.photo.required' => 'La imagen es obligatoria',
        ]);

        try {
            DB::beginTransaction();
            $this->MontoEntregado = 0;
            foreach ($this->inputs as $index => $input) {
                $this->MontoEntregado  += (float)$input['cantidad'];
            }
            // Log::info('Entra if: ');
            if ((float)$this->MontoEntrega == (float)$this->MontoEntregado) {
                // Log::info('RutaServicio: ');
                $servicioRuta = RutaServicio::find($this->idrecolecta);
                $servicioRuta->status_ruta_servicios = 3;
                $servicioRuta->envase_cargado = 0;
                $servicioRuta->save();
                // Log::info('RutaServicioReporte: ');

                RutaServicioReporte::where('ruta_servicio_id',$servicioRuta->id)
                ->where('status_ruta_servicio_reportes',2)->update(['status_ruta_servicio_reportes' => 3]);
              

                //guardar fotos y la evidencia en la tabla
                foreach ($this->inputs as $index => $input) {
                    // Log::info('ServicioEvidenciaEntrega: ');
                    $evidencia = ServicioEvidenciaEntrega::create(['servicio_envases_ruta_id' => $index]);

                    $input['photo']->storeAs(path: 'evidencias/', name: 'evidencia_' . $evidencia->id . '.png');
                }

                //$this->photo->storeAs(path: 'evidencias/', name: 'avatar.png');
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Entrega realizada con exito.'], ['tipomensaje' => 'success'], ['op' => 1]);
            } else {
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada no es la cantidad a entregar:' . $this->MontoEntrega . '-' . $this->MontoEntregado], ['tipomensaje' => 'error']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Hubo un error intenta más tarde.'], ['tipomensaje' => 'error']);

            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // Log::info('Info: ' . $e);
        }
    }
    public function updatedEvidencias()
    {
        // Iterar sobre cada archivo cargado y almacenarlo
        foreach ($this->photo as $index => $evidencia) {
            // Verificar si el archivo se ha cargado correctamente
            if ($evidencia) {
                // Almacenar el archivo
                $path = $evidencia->store('public/evidencias');
                // Actualizar el array con la ruta del archivo almacenado
                $this->photo[$index] = $path;
            }
        }
    }


    public function modalCerrado()
    {
        $this->MontoEntrega = null;
        $this->MontoEntregado = null;
        $this->photo = null;

        $this->reset('tiposervicio', 'inputs', 'idrecolecta', 'envasescantidad', 'MontoRecolecta');
    }
    public function modalCerradoReprogramar()
    {
        $this->IdservicioReprogramar = null;
        $this->motivoReprogramarConcepto = null;
        $this->photorepro = null;
    }


    public function ModalAceptarRecolecta()
    {

        $this->validate([
            'idrecolecta' => 'required',
            'envasescantidad' => 'required',
            'MontoRecolecta' => 'required',
            'inputs.*.cantidad' => 'required', // Máximo 1MB
            'inputs.*.folio' => 'required', // Máximo 1MB
            'inputs.*.sello' => 'required', // Máximo 1MB
            'inputs.*.photo' => 'required|image|max:1024', // Máximo 1MB
        ], [
            'inputs.*.photo.required' => 'La imagen es obligatoria',
            'inputs.*.cantidad.required' => 'La cantidad es obligatoria',
            'inputs.*.folio.required' => 'El folio es obligatoria',
            'inputs.*.sello.required' => 'El sello es obligatoria',
            'MontoRecolecta.required' => 'Debe ingresar el monto total',
            'envasescantidad.required' => 'La cantidad de envases es obligatoria',

        ]);
        try {
            DB::beginTransaction();

            if (!count($this->inputs)) {
                throw new \Exception('No hay envases para guardar');
            }

            //acumulo la cantidad de los envase
            $MontoEnvases = 0;
            $montoEnvaseViolado = 0;
            foreach ($this->inputs as $index => $input) {

                $MontoEnvases  += (float)$input['cantidad'];
                //si esta el monto violado se acumula para despues descontar este valor 
                if ($input['violado']) {
                    $montoEnvaseViolado  += (float)$input['cantidad'];
                }
            }

            if ($MontoEnvases != $this->MontoRecolecta) {
                throw new \Exception('La suma de los envases no coinside con el monto ingresado');
            }

            //si hay violado se resta porque no se llevara.
            $this->MontoRecolecta = $MontoEnvases - $montoEnvaseViolado;
         
            //completo datos del servicio en la ruta
            $servicioruta = RutaServicio::find($this->idrecolecta);
            $servicioruta->monto = $this->MontoRecolecta;
            $servicioruta->envases = $this->envasescantidad;
            $servicioruta->status_ruta_servicios = 3;
            $servicioruta->save();

            // dd($servicioruta);

            $ruta = Ruta::find($servicioruta->ruta_id);
            $ruta->total_ruta = $ruta->total_ruta + $this->MontoRecolecta;
            $ruta->save();


            foreach ($this->inputs as $index => $input) {

                $servicio_envases =  ServicioRutaEnvases::create([
                    'ruta_servicios_id' => $servicioruta->id,
                    'tipo_servicio' => 2,
                    'cantidad' => $input['cantidad'],
                    'folio' => $input['folio'],
                    'sello_seguridad' => $input['sello'],
                ]);
                $evidencia = ServicioEvidenciaRecolecta::create(
                    [
                        'servicio_envases_ruta_id' => $servicio_envases->id,
                        'violate' => $input['violado']
                    ]
                );
                $nombreRutaGuardaImg = 'Servicio_' . $servicioruta->id . '_recolecta_' . $evidencia->id . '_evidencia.png';
                $input['photo']->storeAs(path: 'evidencias/EntregasRecolectas/', name: $nombreRutaGuardaImg);
            }

            RutaServicioReporte::create([
                'servicio_id' => $servicioruta->servicio_id,
                'ruta_id' => $servicioruta->ruta_id,
                'monto' => $servicioruta->monto,
                'folio' => $servicioruta->folio,
                'envases' => $servicioruta->envases,
                'tipo_servicio' => $servicioruta->tipo_servicio,
                'area'=>3
            ]);



            // $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La recolecta se completo correctamente'], ['tipomensaje' => 'success']);
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La recolecta se completo correctamente.'], ['tipomensaje' => 'success'], ['op' => 1]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            $this->dispatch('error', [$e->getMessage()]);

            Log::info('Info: ' . $e);
        }
    }

    public function empezarRuta($id)
    {
        $ruta = Ruta::find($id);
        $ruta->status_ruta = 2;
        $ruta->save();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta a iniciado'], ['tipomensaje' => 'success']);
    }
    public function TerminarRuta($id)
    {
        $serviciosPendientes = RutaServicio::where('ruta_id', $id)
            ->where('status_ruta_servicios', 2)
            ->count();

        if ($serviciosPendientes > 0) {
            // Si hay servicios pendientes con estado 2, envía un mensaje de error
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'No se puede terminar la ruta porque aún tiene servicios pendientes'], ['tipomensaje' => 'error']);
        } else {
            // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
            $ruta = Ruta::find($id);
            $ruta->status_ruta = 3;
            $ruta->hora_fin = now();
            $ruta->ctg_rutas_estado_id = 4;
            $ruta->save();
            $rutaempleados = RutaEmpleados::where('ruta_id', $id)->get();

            foreach ($rutaempleados as $rutaempleado) {
                $rutaempleado->status_ruta_empleados = 2;
                $rutaempleado->save();
            }
            foreach ($rutaempleados as $rutaempleado) {
                RutaEmpleadoReporte::create([
                    'ruta_id' => $rutaempleado->ruta_id,
                    'empleado_id' => $rutaempleado->empleado_id,
                    'status_ruta_empleado_reportes' => 2,
                ]);
            }
            $rutaVehiculos = RutaVehiculo::where('ruta_id', $id)->get();
            foreach ($rutaVehiculos as $rutaVehiculo) {
                $rutaVehiculo->status_ruta_vehiculos = 2;
                $rutaVehiculo->save();
            }
            foreach ($rutaVehiculos as $rutaVehiculo) {
                RutaVehiculoReporte::create([
                    'ruta_id' => $rutaVehiculo->ruta_id,
                    'ctg_vehiculo_id' => $rutaVehiculo->ctg_vehiculo_id,
                    'status_ruta_vehiculo_reportes' => 2,
                ]);
            }


            // Envía un mensaje de éxito
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success']);
        }
    }
    //status servicio ruta en 4 es reporgramnar
    //estatus servicio ruta en 3 es completa
    public function ModalReprogramarServicio($id)
    {
        $this->IdservicioReprogramar = $id;
    }

    public function ModalAceptarReprogramar()
    {
        $this->validate([
            'IdservicioReprogramar' => 'required',
            'motivoReprogramarConcepto' => 'required',
            'photorepro' => 'required',
        ]);
        $servicioRuta = RutaServicio::find($this->IdservicioReprogramar);
        $servicioRuta->status_ruta_servicios = 0;
        $servicioRuta->save();


        RutaServicioReporte::where('ruta_servicio_id',$servicioRuta->id)
        ->where('status_ruta_servicio_reportes',2)->update(['status_ruta_servicio_reportes' => 3]);



        $this->photorepro->storeAs(path: 'evidencias/reprogramacion/', name: 'avatar.png');
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio Sera reprogramado'], ['tipomensaje' => 'success'], ['op' => 1]);
    }
}
